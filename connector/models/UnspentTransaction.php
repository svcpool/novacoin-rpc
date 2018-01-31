<?php
/**
 * Created by PhpStorm.
 * User: zvinger
 * Date: 30.01.18
 * Time: 18:19
 */

namespace Svcpool\NovacoinRpc\connector\models;

use yii\base\BaseObject;

class UnspentTransaction extends BaseObject
{
    /** @var string */
    public $txid;

    /** @var double */
    public $vout;

    /** @var string */
    public $address;

    /** @var string */
    public $account;

    /** @var string */
    public $scriptPubKey;

    /** @var double */
    public $amount;

    /** @var int */
    public $confirmations;

    /** @var int */
    public $spendable;
}