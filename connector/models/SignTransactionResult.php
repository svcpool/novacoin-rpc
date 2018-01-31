<?php
/**
 * Created by PhpStorm.
 * User: zvinger
 * Date: 31.01.18
 * Time: 14:29
 */

namespace Svcpool\NovacoinRpc\connector\models;

use yii\base\BaseObject;

class SignTransactionResult extends BaseObject
{
    /** @var string */
    public $hex;

    /** @var bool */
    public $complete;
}