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
use Svcpool\NovacoinRpc\connector\models\transaction\VInTx;
use Svcpool\NovacoinRpc\parser\parsers\BaseParser;

class SingleVInParser extends BaseParser
{

    /**
     * @return SingleVIn
     */
    public function parse()
    {
        $rawData = $this->getRawData();

        $classes = [
            VInCoinBase::class => FALSE,
            VInTx::class       => 'handleVInTx',
        ];
        foreach ($classes as $class => $method) {
            $object = new $class;
            $keys = array_keys(get_object_vars($object));
            $rawDataKeys = array_keys($rawData);

            if ($keys == $rawDataKeys) {
                if ($method && method_exists($this, $method)) {
                    $result = $this->$method($object, $rawData);
                } else {
                    $result = $this->prepareObject($object, $rawData);
                }
            }
        }

        return $result;
    }

    /**
     * @param $object VInTx
     * @param $rawData
     * @return VInTx
     */
    private function handleVInTx($object, $rawData)
    {
        /** @var VInTx $object */
        $object = $this->prepareObject($object, $rawData);
        $object->scriptSig = (new ScriptSigParser($object->scriptSig))->parse();

        return $object;
    }
}