<?php
/**
 * Created by PhpStorm.
 * User: zvinger
 * Date: 27.03.18
 * Time: 16:58
 */

namespace Svcpool\NovacoinRpc\connector\models\block;


class RpcBlockModel
{
    /** @var string */
    public $hash;

    /** @var int */
    public $confirmations; //: 409642,

    /** @var int */
    public $size; //: 191,

    /** @var int */
    public $height; //: 1,

    /** @var int */
    public $version; //: 2,

    /** @var string */
    public $merkleroot; //: "a742b6bcd8b947b0aa98f44a5d20c15e75fdf790114445e10f899b5cad92568c",

    /** @var double */
    public $mint; //: 100.00000000,

    /** @var int */
    public $time; //: 1360426882,

    /** @var int */
    public $nonce; //: 1789596956,

    /** @var string */
    public $bits; //: "1e0fffff",

    /** @var double */
    public $difficulty; //: 0.00024414,

    /** @var string */
    public $blocktrust; //: "1",

    /** @var string */
    public $chaintrust; //: "2",

    /** @var string */
    public $previousblockhash; //: "00000a060336cbb72fe969666d337b87198b1add2abaa59cca226820b32933a4",

    /** @var string */
    public $nextblockhash; //: "000004a7c2e38628f798a6b5e6170dd796762d4740465a64dff269ab318fab0e",

    /** @var string */
    public $flags; //: "proof-of-work",

    /** @var string */
    public $proofhash; //: "00000a79e4cfd4725f3e8581ff205e9d2c2a7c22ff05a18b348977324d117e9f",

    /** @var int */
    public $entropybit; //: 1,

    /** @var string */
    public $modifier; //: "0000000000000000",

    /** @var string */
    public $modifierchecksum; //: "bc4b99b6",

    /** @var string[] */
    public $tx; //: ["a742b6bcd8b947b0aa98f44a5d20c15e75fdf790114445e10f899b5cad92568c"]
}