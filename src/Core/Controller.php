<?php


namespace SEngine\Core;


class Controller
{
    protected $view;
    protected $display = false;

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

        $this->display = true;

        $methodName = 'action' . $action;
        return $this->$methodName();
    }

    /**
     * Destruct
     */
    public function __destruct()
    {
        if (true === $this->display)
            $this->view->display(__DIR__ . '/../Templates/main.php');
    }
}