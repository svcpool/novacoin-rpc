<?php
/**
 * Created by PhpStorm.
 * User: zvinger
 * Date: 30.01.18
 * Time: 18:49
 */

namespace Svcpool\NovacoinRpc\components\novacoin\models\send;

use yii\base\BaseObject;

class AddressToSend extends BaseObject
{
    public $address;

    public $amount;
}