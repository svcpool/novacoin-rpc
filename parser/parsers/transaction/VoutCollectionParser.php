<?php
/**
 * Created by PhpStorm.
 * User: zvinger
 * Date: 28.03.18
 * Time: 13:17
 */

namespace Svcpool\NovacoinRpc\parser\parsers\transaction;


use Svcpool\NovacoinRpc\parser\parsers\BaseParser;

class VoutCollectionParser extends BaseParser
{
    /**
     * @return mixed
     */
    public function parse()
    {
        $result = [];
        foreach ($this->getRawData() as $singleVout) {
            $result[] = (new SingleVoutParser($singleVout))->parse();
        }

        return $result;
    }
}