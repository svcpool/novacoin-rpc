<?php
/**
 * Created by PhpStorm.
 * User: zvinger
 * Date: 25.09.17
 * Time: 9:15
 */

namespace Svcpool\NovacoinRpc\connector\client;

use Svcpool\NovacoinRpc\connector\exceptions\RpcClientException;
use liamwli\PHPBitcoin\BitCoin;

/**
 * Class RpcClient
 * @package Svcpool\NovacoinRpc\connector\client
 * @method getBalance($account = NULL)
 * @method getnewaddress($account = NULL)
 * @method scaninput($params)
 * @method gettransaction($txid)
 * @method getaccount($address)
 * @method listtransactions($account, $count, $from)
 * @method createrawtransaction($transactions, $addresses)
 * @method signrawtransaction($hex, $dependTxs, $privateKeys, $signHashType)
 * @method sendrawtransaction($hex)
 * @method getblockbynumber($number)
 * @method getblock($hash)
 * @method getblockcount()
 */
class RpcClient extends BitCoin
{
    public function callMethod($methodName, array $parameters = array())
    {
        $result = parent::callMethod($methodName, $parameters);
        $error = $this->getError();
        if ($result === FALSE && $error) {
            throw new RpcClientException($error);
        }

        return $result;
    }

}