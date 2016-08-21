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
}