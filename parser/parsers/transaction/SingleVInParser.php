<?php
/**
 * Created by PhpStorm.
 * User: zvinger
 * Date: 28.03.18
 * Time: 13:12
 */

namespace Svcpool\NovacoinRpc\parser\parsers\transaction;


use Svcpool\NovacoinRpc\connector\models\transaction\SingleVIn;
use Svcpool\NovacoinRpc\connector\models\transaction\VInCoinBase;
use Svcpool\NovacoinRpc\parser\parsers\BaseParser;

class SingleVInParser extends BaseParser
{

    /**
     * @return SingleVIn
     */
    public function parse()
    {
        return $this->prepareObject(new VInCoinBase(), $this->getRawData());
    }
}