<?php


namespace SEngine\Controllers;


use SEngine\Core\Controller;
use SEngine\Models\User;

class Base extends Controller
{
    /**
     * Base constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->view->base = "http://".$_SERVER['HTTP_HOST'];
        $this->view->baseDir = $_SERVER['DOCUMENT_ROOT'];
        $this->view->menu = array('Home', 'About', 'Photo');
        $this->view->isAuth = User::isAuth();

    }
}