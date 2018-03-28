<?php

namespace Svcpool\NovacoinRpc\connector;

use Svcpool\NovacoinRpc\connector\client\RpcClient;
use Svcpool\NovacoinRpc\connector\models\block\RpcBlockModel;
use Svcpool\NovacoinRpc\connector\models\SignTransactionResult;
use Svcpool\NovacoinRpc\connector\models\transaction\RpcTransactionModel;
use Svcpool\NovacoinRpc\connector\models\transaction\ScriptPubKey;
use Svcpool\NovacoinRpc\connector\models\transaction\SingleVOut;
use Svcpool\NovacoinRpc\connector\models\transaction\VInCoinBase;
use Svcpool\NovacoinRpc\connector\models\UnspentTransaction;
use Svcpool\NovacoinRpc\parser\parsers\transaction\TransactionParser;

/**
 * Class RpcConnector
 * @package Svcpool\NovacoinRpc\connector
 */
class RpcConnector
{
    /**
     * @var RpcClient
     */
    protected $_client;

    /**
     * RpcConnector constructor.
     * @param RpcClient $_client
     */
    public function __construct(RpcClient $_client)
    {
        $this->_client = $_client;
    }

    /**
     * @return RpcClient
     */
    public function getClient()
    {
        return $this->_client;
    }

    public function scanInput($txid)
    {
        return $this->_client->scaninput(['txid' => $txid]);
    }

    /**
     * @param bool $asArray
     * @return UnspentTransaction[]
     * @throws exceptions\RpcClientException
     */
    public function listUnspent($asArray = FALSE)
    {
        $data = $this->_client->callMethod('listunspent');
        if ($asArray) {
            return $data;
        }
        $result = [];
        foreach ($data as $datum) {
            $result[] = new UnspentTransaction($datum);
        }

        return $result;
    }

    public function getBalance($account = NULL)
    {
        return $this->_client->getBalance($account);
    }

    public function getAccountForAddress($address)
    {
        return $this->_client->getaccount($address);
    }

    public function getTransationInfo($txId)
    {
        return $this->_client->gettransaction($txId);
    }

    public function getTransactionByHash($hash)
    {
        return (new TransactionParser($this->_client->gettransaction($hash)))->parse();
    }


    public function createAddress($account = NULL)
    {
        $result = $this->_client->getnewaddress($account);

        return $result;
    }

    public function getTransactions($count = 10, $offset = 0)
    {
        return $this->_client->listtransactions('*', $count, $offset);
    }

    public function createRawTransaction($transactions, $addresses)
    {
        return $this->_client->createrawtransaction($transactions, $addresses);
    }

    /**
     * @param $hex
     * @param null $dependTxs
     * @param null $privateKeys
     * @param null $signHashType
     * @return SignTransactionResult
     */
    public function signRawTransaction($hex, $dependTxs = NULL, $privateKeys = NULL, $signHashType = NULL)
    {
        $signrawtransaction = $this->_client->signrawtransaction($hex, $dependTxs, $privateKeys, $signHashType);

        return $this->prepareObject(new SignTransactionResult(), $signrawtransaction);
    }

    /**
     * @param $hex
     * @return string TXid
     */
    public function sendRawTranscation($hex)
    {
        return $this->_client->sendrawtransaction($hex);
    }

    /**
     * @param $number
     * @return RpcBlockModel
     */
    public function getBlockByNumber(int $number)
    {
        return $this->prepareObject(new RpcBlockModel(), $this->_client->getblockbynumber($number));
    }

    /**
     * @param string $hash
     * @return RpcBlockModel
     */
    public function getBlockByHash(string $hash)
    {
        return $this->prepareObject(new RpcBlockModel(), $this->_client->getblock($hash));
    }

    /**
     * @return int
     */
    public function getBlockCount()
    {
        return $this->_client->getblockcount();
    }

    protected function prepareObject($object, array $vars)
    {
        $has = get_object_vars($object);
        foreach ($has as $name => $oldValue) {
            $object->$name = isset($vars[$name]) ? $vars[$name] : NULL;
        }

        return $object;
    }
}