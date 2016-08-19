<?php


namespace SEngine\Controllers;


class News extends Base
{
    protected function actionIndex()
    {

        $this->view->news = array(
            array(
                'name' => 'License and terms of use',
                'text' => 'License and terms of use White and Clean Rounded is a CSS template that is free and fully standards compliant. Free CSS templates designed this template.'
            ),
            array(
                'name' => 'Title with a link - Example of heading 2',
                'text' => 'Donec nulla. Aenean eu augue ac nisl tincidunt rutrum. Proin erat justo, pharetra eget, posuere at, malesuada et, nulla.'
            )
        );
    }
}