<?php


namespace SEngine\Controllers;


use SEngine\Core\Controller;
use SEngine\Models\Settings;


class Base extends Controller
{
    /**
     * Base constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $settings = Settings::findById(1);


        $this->view->menu = array('Home', 'About', 'Photo');
        $this->view->settings = $settings;

    }
}