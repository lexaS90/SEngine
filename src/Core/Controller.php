<?php


namespace SEngine\Core;


use SEngine\Core\Ui\StatusMessage;
use SEngine\Models\User;

abstract class Controller
{
    protected $view;
    protected $ajaxData;
    protected $template = '';
    protected $msg;
    public $isAjax = false;

    /**
     * Controller constructor.
     */
    public function __construct()
    {
        $this->view = new View();
        $this->ajaxData = new Ajax();
        $this->msg = new StatusMessage();
        $this->view->isAuth = User::isAuth();

        $this->view->base = $this->base();
        $this->view->baseDir = $this->baseDir();

        $this->isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';

        $this->view->msg = $this->msg->getSessionMsg();


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
     * Базовая директория по HTTP
     * @return string
     */
    public function base()
    {
        return "http://".$_SERVER['HTTP_HOST'];
    }

    /**
     * Базовая директория файловой системы
     * @return string
     */
    public function baseDir()
    {
        return  $_SERVER['DOCUMENT_ROOT'];
    }


    /**
     * Запрос произведен методом GET?
     * @return bool
     */
    protected function IsGet()
    {
        return $_SERVER['REQUEST_METHOD'] == 'GET';
    }

    /**
     * Запрос произведен методом POST?
     * @return bool
     */
    protected function IsPost()
    {
        return $_SERVER['REQUEST_METHOD'] == 'POST';
    }

    /**
     * Destruct
     */
    public function __destruct()
    {
        if ($this->isAjax){
            if (!$this->ajaxData->isEmpty())
                $this->ajaxData->display();
        }
        else{
            if ('' !== $this->template) {
                $this->view->msg .= $this->msg->getMsg();
                $this->view->displayTwig($this->template);
            }
        }
    }
}