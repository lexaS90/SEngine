<?php


namespace SEngine\Controllers;


use SEngine\Core\Controller;

class News extends Controller
{
    protected function actionIndex()
    {
        $this->view->title = 'Новости';
    }
}