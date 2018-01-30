<?php

namespace Svcpool\NovacoinRpc\connector;

use Svcpool\NovacoinRpc\connector\client\RpcClient;

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

    public function listUnspent()
    {
        return $this->_client->callMethod('listunspent');
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


}