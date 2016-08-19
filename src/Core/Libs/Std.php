<?php


namespace SEngine\Core\Libs;



trait Std
{
    protected $data = array();

    /**
     * Установка значений шаблона
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    /**
     * Извлечение значений шаблона
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        if (is_array($this->data[$name])) {
            return new Arrayable($this->data[$name]);
        }

        return $this->data[$name];
    }

    /**
     * Проверка на существование
     * @param $name
     * @return bool
     */
    public function __isset($name)
    {
        return isset($this->data[$name]);
    }
}