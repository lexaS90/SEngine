<?php


namespace SEngine\Controllers;


use SEngine\Core\Controller;

class Base extends Controller
{
    /**
     * Base constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->view->menu = array('Home', 'About', 'Photo');
    }

    /**
     * Destruct
     */
    public function __destruct()
    {
        if (true === $this->display)
            $this->view->displayTwig('news.html.twig');
    }

}