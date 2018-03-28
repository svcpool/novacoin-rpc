<?php
/**
 * Created by PhpStorm.
 * User: zvinger
 * Date: 28.03.18
 * Time: 13:05
 */

namespace Svcpool\NovacoinRpc\parser\parsers\transaction;


use Svcpool\NovacoinRpc\connector\models\transaction\ScriptPubKey;
use Svcpool\NovacoinRpc\parser\parsers\BaseParser;

class ScriptPubKeyParser extends BaseParser
{
    /**
     * @return ScriptPubKey
     */
    public function parse()
    {
        return $this->prepareObject(new ScriptPubKey(), $this->getRawData());
    }
}