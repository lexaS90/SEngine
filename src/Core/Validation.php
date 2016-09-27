<?php


namespace SEngine\Core;




use SEngine\Core\Exceptions\MultiException;

class Validation
{
    private $obj;
    private $rules;


    /**
     * Validation constructor.
     * @param $obj
     * @param $rules
     */
    public function __construct($obj, $rules)
    {
        $this->obj = $obj;
        $this->rules = $rules;

        foreach ($rules as $k => $v) {
            $this->obj->$k = trim($obj->$k);
            $this->obj->$k = strip_tags($obj->$k);
        }
    }


    /**
     * Запуск валидации
     * @return bool
     * @throws MultiException
     * @throws array
     */
    public function run()
    {
        $e = new MultiException();

        foreach($this->rules as $k => $v) {
            foreach ($v as $ruleKey => $ruleValue) {
                if (method_exists($this, $ruleKey)) {
                    $result = $this->$ruleKey($this->obj->$k, $ruleValue, $k);
                    if (true !== $result) {
                        $e[] = array('field' => $k, 'errorText' => $result);

                    }
                }
            }
        }
        if (count($e) > 0) {
            throw $e;
        }

        return true;
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
}