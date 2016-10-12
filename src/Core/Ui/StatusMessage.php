<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 26.09.2016
 * Time: 22:56
 */

namespace SEngine\Core\Ui;


use SEngine\Core\View;

class StatusMessage
{
    private $view;
    private $msg = array();

    public function __construct()
    {
        $this->view = new View();
        session_start();
    }

    public function setSessionMsg($text, $status = 'success')
    {
        $_SESSION['session_messages'][$status][] = $text;
    }

    public function getSessionMsg()
    {
        $this->view->msg = $_SESSION['session_messages'];
        unset($_SESSION['session_messages']);
        return $this->view->renderTwig('ui/msg.html.twig');
    }

    public function setMsg($text, $status = 'success')
    {
        $this->msg[$status][] = $text;
    }

    public function getMsg()
    {
        $this->view->msg = $this->msg;
        return $this->view->renderTwig('ui/msg.html.twig');
    }

   /* public function add($text, $status = 'success')
    {
        $this->msg[] = array('text' =>  $text, 'status' => $status);
    }

    public function setArray($errors, $status = 'success')
    {
        foreach ($errors as $error)
            $this->set($error, $status);
    }*/


}