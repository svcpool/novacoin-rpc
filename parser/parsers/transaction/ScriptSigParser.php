<?php
/**
 * Created by PhpStorm.
 * User: zvinger
 * Date: 28.03.18
 * Time: 15:13
 */

namespace Svcpool\NovacoinRpc\parser\parsers\transaction;


use Svcpool\NovacoinRpc\connector\models\transaction\ScriptSig;
use Svcpool\NovacoinRpc\parser\parsers\BaseParser;

class ScriptSigParser extends BaseParser
{

    /**
     * @return mixed
     */
    public function parse()
    {
        return $this->prepareObject(new ScriptSig(), $this->getRawData());
    }
}