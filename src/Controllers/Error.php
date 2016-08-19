<?php


namespace SEngine\Controllers;


use SEngine\Core\Controller;
use SEngine\Core\Message;

class Error extends Controller
{
    protected function action404()
    {
        header('HTTP/1.1 404 Not Found');
        $this->view->title = (new Message('error'))->site->error_404;

    }

    public function __destruct()    {

        $this->view->display(__DIR__ . '/../Templates/Error.php');
    }
}