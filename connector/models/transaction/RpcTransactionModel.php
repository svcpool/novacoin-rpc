<?php
/**
 * Created by PhpStorm.
 * User: zvinger
 * Date: 28.03.18
 * Time: 0:04
 */

namespace Svcpool\NovacoinRpc\connector\models\transaction;


class RpcTransactionModel
{
    /** @var string */
    public $txid;

    /** @var int */
    public $version;

    /** @var int */
    public $time;

    /** @var int */
    public $locktime;

    /** @var SingleVIn[] */
    public $vin;

    /** @var SingleVOut[] */
    public $vout;

    /** @var string */
    public $blockhash;

    /** @var int */
    public $confirmations;
}