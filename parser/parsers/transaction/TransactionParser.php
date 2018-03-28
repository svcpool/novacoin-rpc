<?php
/**
 * Created by PhpStorm.
 * User: zvinger
 * Date: 28.03.18
 * Time: 13:02
 */

namespace Svcpool\NovacoinRpc\parser\parsers\transaction;

use Svcpool\NovacoinRpc\connector\models\transaction\RpcTransactionModel;
use Svcpool\NovacoinRpc\parser\parsers\BaseParser;

class TransactionParser extends BaseParser
{
    /**
     * @return RpcTransactionModel
     */
    public function parse()
    {
        $rawData = $this->getRawData();
        /** @var RpcTransactionModel $prepareObject */
        $prepareObject = $this->prepareObject(new RpcTransactionModel(), $rawData);
        $prepareObject->vin = (new VinCollectionParser($rawData['vin']))->parse();
        $prepareObject->vout = (new VoutCollectionParser($rawData['vout']))->parse();

        return $prepareObject;
    }
}