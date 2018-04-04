<?php
/**
 * Created by PhpStorm.
 * User: zvinger
 * Date: 28.03.18
 * Time: 13:03
 */

namespace Svcpool\NovacoinRpc\parser\parsers;


abstract class BaseParser
{
    private $_raw_data;

    public function __construct($rawData)
    {
        $this->_raw_data = $rawData;
    }

    /**
     * @return mixed
     */
    public function getRawData()
    {
        return $this->_raw_data;
    }

    /**
     * @return mixed
     */
    abstract public function parse();

    protected function prepareObject($object, array $vars)
    {
        $has = get_object_vars($object);
        foreach ($has as $name => $oldValue) {
            $object->$name = isset($vars[$name]) ? $vars[$name] : NULL;
        }

        return $object;
    }
}