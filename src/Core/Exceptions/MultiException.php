<?php


namespace SEngine\Core\Exceptions;


use SEngine\Core\Libs\ArrayAccess;
use SEngine\Core\Libs\Countable;
use SEngine\Core\Libs\Iterator;

class MultiException extends \Exception implements \Iterator, \ArrayAccess, \Countable
{
    use Iterator;
    use ArrayAccess;
    use Countable;
}