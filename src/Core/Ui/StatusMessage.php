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

    public function __construct()
    {
        $this->view = new View();
    }

    public function set($text, $status = 'success')
    {
        session_start();
        $_SESSION['session_messages'][] = array('text' =>  $text, 'status' => $status);
    }

    public function get()
    {
        session_start();
        $msgArray = $_SESSION['session_messages'];
        unset($_SESSION['session_messages']);

        $this->view->msg = $msgArray;

        return $this->view->renderTwig('ui/msg.html.twig');
    }
}