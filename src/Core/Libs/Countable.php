<?php


namespace SEngine\Core\Libs;


trait Countable
{
    protected $data = [];

    /**
     * @return int
     */
    public function count()
    {
        return count($this->data);
    }
}