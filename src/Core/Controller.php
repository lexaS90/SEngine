<?php


namespace SEngine\Core;


use SEngine\Core\Ui\StatusMessage;
use SEngine\Models\User;

class Controller
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

        $this->view->base = "http://".$_SERVER['HTTP_HOST'];
        $this->view->baseDir = $_SERVER['DOCUMENT_ROOT'];

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