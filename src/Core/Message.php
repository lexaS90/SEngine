<?php


namespace SEngine\Core;

use SEngine\Core\Libs\ArrayAccess;
use SEngine\Core\Libs\Std;

/**
 * Class Message
 * Example (new Message('error'))->site->error_404;
 * @package SEngine\Core
 */
class Message implements \ArrayAccess
{
    use Std;
    use ArrayAccess;

    public function __construct($data, $lang = null)
    {
        $langDefault = (null !== $lang) ? $lang : Config::instance()->site->lang_default;
        $this->data = require (__DIR__ .'/../message/'.$langDefault.'/'.$data.'.php');
    }
}