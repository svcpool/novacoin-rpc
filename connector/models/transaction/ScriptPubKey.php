<?php
/**
 * Created by PhpStorm.
 * User: zvinger
 * Date: 28.03.18
 * Time: 0:08
 */

namespace Svcpool\NovacoinRpc\connector\models\transaction;


class ScriptPubKey
{
    /** @var string */
    public $asm;

    /** @var string */
    public $hex;

    /** @var int */
    public $reqSigs;

    /** @var string */
    public $type;

    /** @var string[] */
    public $addresses;
}