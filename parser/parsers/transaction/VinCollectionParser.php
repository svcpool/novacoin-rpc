<?php
/**
 * Created by PhpStorm.
 * User: zvinger
 * Date: 28.03.18
 * Time: 13:16
 */

namespace Svcpool\NovacoinRpc\parser\parsers\transaction;


use Svcpool\NovacoinRpc\parser\parsers\BaseParser;

class VinCollectionParser extends BaseParser
{
    /**
     * @return mixed
     */
    public function parse()
    {
        $result = [];
        foreach ($this->getRawData() as $singleVin) {
            $result[] = (new SingleVInParser($singleVin))->parse();
        }

        return $result;
    }
}