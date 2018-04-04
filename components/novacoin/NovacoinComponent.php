<?php
/**
 * Created by PhpStorm.
 * User: zvinger
 * Date: 04.10.17
 * Time: 16:27
 */

namespace Svcpool\NovacoinRpc\components\novacoin;

use Bymorev\helpers\traits\LoggerTrait;
use common\components\crypto\config\NovacoinRpcConfig;
use common\components\crypto\config\WatchAddressConfig;
use common\components\crypto\handlers\blockchain\transaction\listinfo\TransactionListGetter;
use common\components\crypto\helpers\address\UserCryptoAddressHelper;
use common\components\crypto\helpers\address\UserCryptoBalanceHelper;
use common\components\crypto\models\address\NvcAddressObject;
use common\components\crypto\models\transaction\filter\TransactionsFilter;
use common\components\crypto\models\transaction\object\NvcTransactionObject;
use common\components\crypto\models\transaction\result\TransactionResult;
use Svcpool\NovacoinRpc\components\novacoin\models\send\AddressToSend;
use Svcpool\NovacoinRpc\components\novacoin\models\send\TransactionToSpent;
use Svcpool\NovacoinRpc\connector\client\RpcClient;
use Svcpool\NovacoinRpc\connector\models\UnspentTransaction;
use Svcpool\NovacoinRpc\connector\RpcConnector;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\httpclient\Client;

class NovacoinComponent
{
    use LoggerTrait;

    protected static $_log_category = LOG_TRANSACTIONS_HANDLER_CATEGORY;

    public $changeAddress;

    /**
     * @var RpcConnector
     */
    private $_connector;

    public function getUserAddress($user_id, $type = NvcAddressObject::TYPE_REAL)
    {
        $helper = new UserCryptoAddressHelper();
        $helper->setUserId($user_id);
        $addressObject = $helper->getAddressObject($type);
        if (empty($addressObject)) {
            $addressObject = $helper->createAddress($type);
        }

        return $addressObject->address;
    }

    public function getUserBalance($user_id, $calculated = FALSE)
    {
        $helper = new UserCryptoBalanceHelper();
        $helper->setUserId($user_id);

        $realBalance = $helper->getUserBalance($calculated);

        return $realBalance;
    }

    public function getAddressBalance($address)
    {
        $client = new Client();
        $result = $client->get('https://api.novaco.in/getbalance/' . $address)->send()->getData();

        return ArrayHelper::getValue($result, 'outval', 0) / NOVACOIN_API_MULTIPLIER;
    }

    public function getUserAddressBalance($user_id)
    {
        return $this->getAddressBalance($this->getUserAddress($user_id));
    }

    /**
     * @return RpcConnector
     * @throws \Exception
     */
    public function getRpcConnector()
    {
        if (empty($this->_connector)) {
            $config = NovacoinRpcConfig::getInstance();
            $bitcoin = new RpcClient($config->user, $config->password, $config->host, $config->port);
            $this->_connector = new RpcConnector($bitcoin);
        }

        return $this->_connector;
    }

    /**
     * @param TransactionsFilter|NULL $filter
     * @return TransactionResult[]|mixed
     */
    public function getTransactions(TransactionsFilter $filter = NULL)
    {
        $key = 'NVC_transactions_' . ($filter ? $filter->getCacheKey() : 'all');
        $cache = \Yii::$app->cacheNovacoin;
        $result = $cache->get($key);
        if (empty($result)) {
            $result = (new TransactionListGetter($filter))->getTransactionsList();
            $cache->set($key, $result, MINUTE * 7);
        }

        return $result;
    }

    /**
     * @param TransactionsFilter|NULL $filter
     * @return TransactionResult[]|mixed
     */
    public function getTransactionsCount(TransactionsFilter $filter = NULL)
    {
        $key = 'NVC_transactions_count_' . ($filter ? $filter->getCacheKey() : 'all');
        $cache = \Yii::$app->cacheNovacoin;
        $result = $cache->get($key);
        if (empty($result)) {
            $result = (new TransactionListGetter($filter))->getTransactionsCount();
            $cache->set($key, $result, MINUTE * 7);
        }

        return (int) $result;
    }

    public function isWatchingAddress($address)
    {
        return in_array($address, WatchAddressConfig::getInstance()->watchingAddresses);
    }

    public function generateTransactionKey($txid, $address)
    {
        return $txid . '--' . $address;
    }


    private $_tx_keys;

    private function getTxKeys()
    {
        if (empty($this->_tx_keys)) {
            $currentTransactionIds = NvcTransactionObject::find()->select(['txid', 'address'])->asArray()->all();
            $keys = [];
            foreach ($currentTransactionIds as $currentTransactionId) {
                $keys[] = \Yii::$app->novacoinComponent->generateTransactionKey($currentTransactionId['txid'], $currentTransactionId['address']);
            }
            $this->_tx_keys = $keys;
        }

        return $this->_tx_keys;
    }

    public function isDuplicateTransaction($txid, $address)
    {
        $keys = $this->getTxKeys();
        $key = $this->generateTransactionKey($txid, $address);
        $in_array = in_array($key, $keys);
        static::Log("TX " . $txid . '--' . $address . ' duplicate test: ' . $in_array);

        return $in_array;
    }

    public function getNvcPrice()
    {
        $key = 'nvc_price_key';
        $cache = \Yii::$app->cache;
        $cachedPrice = $cache->get($key);
        if (empty($cachedPrice)) {
            $apiPrice = (double)ArrayHelper::getValue(Json::decode((new Client())->get('https://api.coinmarketcap.com/v1/ticker/novacoin/')->send()->content), '0.price_usd');
            $cachedPrice = $apiPrice;
            $cache->set($key, $apiPrice, 60);
        }

        return $cachedPrice;
    }

    /**
     * @param $unspentTransactions UnspentTransaction[]
     * @param $addresses AddressToSend[]
     * @return array
     * @throws \Exception
     */
    public function getRawTransactionData($unspentTransactions, $addresses)
    {
        $transactions = [];
        if (empty($this->changeAddress)) {
            throw new \Exception("Empty change address");
        }
        $sumOut = array_sum(ArrayHelper::getColumn($unspentTransactions, 'amount'));
        $sumIn = array_sum(ArrayHelper::getColumn($addresses, 'amount'));

        $inputs = count($unspentTransactions);
        $outputs = count($addresses);
        $txSize = $inputs * 180 + $outputs * 34 + 10;
        $txFee = 0.000001 * $txSize;
        $delta = $sumOut - $sumIn;
        $change = $delta - $txFee;
        $addresses[] = new AddressToSend([
            'amount'  => $change,
            'address' => $this->changeAddress,
        ]);
        foreach ($unspentTransactions as $unspentTransaction) {
            $transactionObject = new TransactionToSpent();
            $transactionObject->txid = $unspentTransaction->txid;
            $transactionObject->vout = $unspentTransaction->vout;
            $transactions[] = (array)$transactionObject;
        }
        $addressesResult = [];

        foreach ($addresses as $address) {
            if (empty($addressesResult[$address->address])) {
                $addressesResult[$address->address] = 0;
            }
            $addressesResult[$address->address] += $address->amount;
        }

        return [$transactions, $addressesResult];
    }

    /**
     * @param $unspentTransactions UnspentTransaction[]
     * @param $addresses AddressToSend[]
     * @return array
     * @throws \Exception
     */
    public function selectTransactionsToSpent($unspentTransactions, $addresses)
    {
        $amounts = ArrayHelper::getColumn($addresses, 'amount');
        $amountsSum = array_sum($amounts);
        ArrayHelper::multisort($unspentTransactions, 'amount', SORT_ASC);
        $selectedTXs = [];
        $collectedSum = 0;
        foreach ($unspentTransactions as $unspentTransaction) {
            $selectedTXs[] = $unspentTransaction;
            $collectedSum += $unspentTransaction->amount;
            if ($collectedSum >= $amountsSum) {
                break;
            }
        }
        if (empty($selectedTXs) || $collectedSum < $amountsSum) {
            throw new \Exception("There is no enough TX Money");
        }

        return $selectedTXs;
    }

    /**
     * @param $unspent
     * @param $addresses
     * @return string
     * @throws \Exception
     */
    public function sendMoney($unspent, $addresses): string
    {
        $selected = $this->selectTransactionsToSpent($unspent, $addresses);
        $data = $this->getRawTransactionData($selected, $addresses);
        $raw = $this->getRpcConnector()->createRawTransaction($data[0], $data[1]);
        $signInfo = $this->getRpcConnector()->signRawTransaction($raw);
        $hash = $this->getRpcConnector()->sendRawTranscation($signInfo->hex);

        return $hash;
    }
}