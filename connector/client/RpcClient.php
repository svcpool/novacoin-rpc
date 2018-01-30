<?php
/**
 * Created by PhpStorm.
 * User: zvinger
 * Date: 25.09.17
 * Time: 9:15
 */

namespace common\components\crypto\rpc\client;

use common\components\crypto\rpc\exceptions\RpcClientException;
use liamwli\PHPBitcoin\BitCoin;

/**
 * Class RpcClient
 * @package common\components\crypto\rpc\client
 * @method getBalance($account = NULL)
 * @method getnewaddress($account = NULL)
 * @method scaninput($params)
 * @method gettransaction($txid)
 * @method getaccount($address)
 * @method listtransactions($account, $count, $from)
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