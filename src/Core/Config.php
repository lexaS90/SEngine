<?php


namespace SEngine\Core;


use SEngine\Core\Libs\ArrayAccess;
use SEngine\Core\Libs\Singleton;
use SEngine\Core\Libs\Std;

/**
 * Class Config
 * Example: Config::instance()->settings->display_errors;
 *
 * @package SEngine\Core
 */
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