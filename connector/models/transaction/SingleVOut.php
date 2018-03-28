<?php
/**
 * Created by PhpStorm.
 * User: zvinger
 * Date: 28.03.18
 * Time: 0:07
 */

namespace Svcpool\NovacoinRpc\connector\models\transaction;


class SingleVOut
{
    /**
     * @var double
     */
    public $value;

    /** @var int */
    public $n;

    /** @var ScriptPubKey */
    public $scriptPubKey;
}