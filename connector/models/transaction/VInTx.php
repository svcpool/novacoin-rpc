<?php
/**
 * Created by PhpStorm.
 * User: zvinger
 * Date: 28.03.18
 * Time: 15:11
 */

namespace Svcpool\NovacoinRpc\connector\models\transaction;


class VInTx extends SingleVIn
{
    /**
     * @var string
     */
    public $txid;

    /**
     * @var int
     */
    public $vout;

    /**
     * @var ScriptSig
     */
    public $scriptSig;

    /**
     * @var int
     */
    public $sequence;


}