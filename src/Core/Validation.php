<?php


namespace SEngine\Core;




use SEngine\Core\Exceptions\MultiException;

class Validation
{
    private $obj;
    private $rules;
    public $errors = array();
    public $errorText = array();
    public $errorField = array();
    public $isValid = false;


    /**
     * Validation constructor.
     * @param $obj
     * @param $rules
     */
    public function __construct($obj, $rules)
    {
        $this->obj = $obj;
        $this->rules = $rules;
    }


    /**
     * Запуск валидации
     * @return $this
     */
    public function run()
    {
        $errors = array();

        foreach($this->rules as $k => $v) {
            foreach ($v as $ruleKey => $ruleValue) {
                if (method_exists($this, $ruleKey)) {
                    $result = $this->$ruleKey($this->obj->$k, $ruleValue, $k);
                    if (true !== $result) {
                        $errors[] = array('field' => $k, 'errorText' => $result);
                        $this->errorText[] = $result;
                        $this->errorField[] = $k;
                    }
                }
            }
        }
        if (count($errors) > 0) {
            $this->errors = $errors;
            $this->isValid = false;
        }
        else{
            $this->isValid = true;
        }

        return $this;
    }

    /**
     * Проверка на существования поля формы
     * @param $prop
     * @param $value
     * @param string $propName
     * @return bool|mixed
     */
    private function required($prop, $value, $propName = '')
    {
        if (true === $value)
            return (empty($prop) ? str_replace('%name%', $propName, (new Message('error'))->form->empty_field) : true);
        return true;
    }

    /**
     * Поле должно быть emil
     * @param $prop
     * @param $value
     * @param string $propName
     * @return string
     */
    private function email($prop, $value, $propName = '')
    {
        if (true === $value) {
            if (filter_var($prop, FILTER_VALIDATE_EMAIL))
                return true;
            else
                return str_replace('%name%', $propName, (new Message('error'))->form->not_email);
        }
        return true;
    }

    /**
     * Проверка на минимальную длинну поля формы
     * @param $prop
     * @param $value
     * @param string $propName
     * @return bool|mixed
     */
    private function minLength($prop, $value, $propName = '')
    {
        if (strlen($prop) >= $value) {
            return true;
        }
        else {
            $error = (new Message('error'))->form->min_length;
            $error = str_replace('%name%', $propName , $error);
            $error = str_replace('%size%', $value , $error);

            return $error;
        }
    }

    /**
     * Проверка на максимальную длинну поля формы
     * @param $prop
     * @param $value
     * @param string $propName
     * @return bool|mixed
     */
    private function maxLength($prop, $value, $propName = '')
    {
        if (strlen($prop) <= $value) {
            return true;
        }
        else {
            $error = (new Message('error'))->form->max_length;
            $error = str_replace('%name%', $propName , $error);
            $error = str_replace('%size%', $value , $error);

            return $error;
        }
    }

    /**
     * Фильтр данных
     * @param $prop
     * @param $value
     * @param string $propName
     * @return bool
     */
    public function mainFilter($prop, $value, $propName = '')
    {
        if (true === $value) {
            $this->obj->$propName = trim($this->obj->$propName);
            $this->obj->$propName = strip_tags($this->obj->$propName);
        }

        return true;
    }
}