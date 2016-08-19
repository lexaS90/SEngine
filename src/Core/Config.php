<?php


namespace SEngine\Core;


use SEngine\Core\Libs\ArrayAccess;
use SEngine\Core\Libs\Singleton;
use SEngine\Core\Libs\Std;

class Config implements \ArrayAccess
{
    use Singleton;
    use Std;
    use ArrayAccess;

    /**
     * Config constructor.
     *
     */
   private function __construct()
    {
        $data = require_once (__DIR__ .'/../config.php');
        $this->data = $data;
    }
}