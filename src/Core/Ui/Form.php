<?php


namespace SEngine\Core\Ui;


use SEngine\Core\View;

class Form
{
    public $action = '';
    public $method = 'post';
    public $fields = [];

    /**
    $fields = array(
        array('tag' => 'input', 'label' => 'Login', 'attributes' => ['name' => 'name', 'type' => 'text']),
        array('tag' => 'input',  'label' => 'Password', 'attributes' => ['name' => 'email', 'type' => 'password']),
        array('tag' => 'textarea',  'label' => 'Text', 'attributes' => ['name' => 'text']),
        array('tag' => 'select',  'label' => 'Выбор', 'attributes' => ['name' => 'o'], 'default' => 't2',
        'option' => ['t1' =>'item1', 't2' => 'item2', 't3' =>'item3'],
        ),
        array('tag' => 'input', 'label' => 'Выбор1', 'attributes' => ['name' => 'ch', 'type' => 'checkbox', 'value' => 'c1']),
        array('tag' => 'input', 'label' => 'Выбор2', 'attributes' => ['name' => 'ch', 'type' => 'checkbox', 'value' => 'c2']),

        array('tag' => 'input', 'label' => 'Выбор3', 'attributes' => ['name' => 'ch2', 'type' => 'radio', 'value' => 'c2']),
        array('tag' => 'input', 'label' => 'Выбор4', 'attributes' => ['name' => 'ch2', 'type' => 'radio', 'value' => 'c3']),
        array('tag' => 'input', 'label' => 'Выбор5', 'attributes' => ['name' => 'ch2', 'type' => 'radio', 'value' => 'c4', 'checked' => 'checked']),
    );
     *
     *
     * @var View
     */

    private $view;

    public function __construct()
    {
        if ('' === $this->action)
            $this->action = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

        $this->view = new View();
    }

    public function render($formData = [])
    {
        $this->view->action = $this->action;
        $this->view->method = $this->method;
        $this->view->fields = $this->fields;
        $this->view->formData = $formData;

        return $this->view->renderTwig('ui/form.html.twig');
    }

    public function setData($data)
    {
        foreach ($data as $k => $v) {
            if ($this->fields[$k]) {
                if ($this->fields[$k]['tag'] == 'input')
                    $this->fields[$k]['attributes']['value'] = $v;
                if ($this->fields[$k]['tag'] == 'textarea')
                    $this->fields[$k]['value'] = $v;
            }
        }
    }

    public function setErrors($errors)
    {
        foreach ($errors as $k => $error) {
            $this->fields[$error]['error'] = true;
        }
    }
}