<?php


namespace SEngine\Core;


class Controller
{
    protected $view;
    protected $template = '';

    /**
     * Controller constructor.
     */
    public function __construct()
    {
        $this->view = new View();
    }

    /**
     * Вызываеться перед запуском экшена
     */
    protected function beforeAction()
    {
    }

    /**
     * Запуск экшена
     * @param $action string
     * @return mixed
     */
    public function action($action)
    {
        $this->beforeAction();

        $methodName = 'action' . $action;
        return $this->$methodName();
    }

    /**
     * Destruct
     */
    public function __destruct()
    {
        if ('' !== $this->template)
            $this->view->displayTwig($this->template);
    }
}