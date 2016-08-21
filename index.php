<?php
include (__DIR__ .'/vendor/autoload.php');

ini_set('display_errors', \SEngine\Core\Config::instance()->settings->display_errors);


$url = $_SERVER['REQUEST_URI'];

try{\SEngine\Core\Db::instance();
    \SEngine\Core\Route::start($url);

}catch (\SEngine\Core\Exceptions\Db $ex){
    (new \SEngine\Controllers\Error())->action('Db');
}

