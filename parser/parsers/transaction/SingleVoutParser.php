<?php
/**
 * Created by PhpStorm.
 * User: zvinger
 * Date: 28.03.18
 * Time: 13:08
 */

namespace Svcpool\NovacoinRpc\parser\parsers\transaction;


use Svcpool\NovacoinRpc\connector\models\transaction\SingleVOut;
use Svcpool\NovacoinRpc\parser\parsers\BaseParser;

class SingleVoutParser extends BaseParser
{
    /**
     * @return SingleVOut
     */
    public function parse()
    {
        $vars = $this->getRawData();
        $prepareObject = $this->prepareObject(new SingleVOut(), $vars);
        $prepareObject->scriptPubKey = (new ScriptPubKeyParser($vars['scriptPubKey']))->parse();

        return $prepareObject;
    }
}