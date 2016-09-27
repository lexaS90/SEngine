<?php


namespace SEngine\Core;


use SEngine\Core\Ui\StatusMessage;

class Controller
{
    protected $view;
    protected $ajaxData;
    protected $template = '';
    public $isAjax = false;

    /**
     * Controller constructor.
     */
    public function __construct()
    {
        $this->view = new View();
        $this->ajaxData = new Ajax();
        
        $this->view->msg = (new StatusMessage())->get();

        $this->isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
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
            $this->ajaxData->display();
        }
        else{
            if ('' !== $this->template)
                $this->view->displayTwig($this->template);
        }
    }
}