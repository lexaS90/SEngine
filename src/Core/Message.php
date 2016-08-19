<?php


namespace SEngine\Core;

use SEngine\Core\Libs\ArrayAccess;
use SEngine\Core\Libs\Std;

class Message implements \ArrayAccess
{
    use Std;
    use ArrayAccess;

    public function __construct($data, $lang = null)
    {
        $langDefault = $lang ?? Config::instance()->site->lang_default;
        $this->data = require_once (__DIR__ .'/../message/'.$langDefault.'/'.$data.'.php');
    }
}