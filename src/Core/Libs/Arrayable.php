<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 19.08.2016
 * Time: 12:27
 */

namespace SEngine\Core\Libs;


class Arrayable implements \ArrayAccess, \Iterator, \Countable
{
    use Std;
    use ArrayAccess;
    use Iterator;
    use Countable;

    /**
     * Arrayable constructor.
     * @param array $data
     *
     * Objects can be constructed from array
     */
    public function __construct($data = [])
    {
        $this->data = $data;
    }
    
    /**
     * @return string
     */
    public function __toString()
    {
        return json_encode($this->data);
    }
}