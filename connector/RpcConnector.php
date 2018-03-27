<?php

namespace Svcpool\NovacoinRpc\connector;

use Svcpool\NovacoinRpc\connector\client\RpcClient;
use Svcpool\NovacoinRpc\connector\models\SignTransactionResult;
use Svcpool\NovacoinRpc\connector\models\UnspentTransaction;

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

        return new SignTransactionResult($signrawtransaction);
    }

    /**
     * @param $hex
     * @return string TXid
     */
    public function sendRawTranscation($hex)
    {
        return $this->_client->sendrawtransaction($hex);
    }

    public function getBlockByNumber($number)
    {
        $getblockbynumber = $this->_client->getblockbynumber($number);
        return $getblockbynumber;
    }
}