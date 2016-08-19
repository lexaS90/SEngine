<?php
include (__DIR__ .'/vendor/autoload.php');

ini_set('display_errors', \SEngine\Core\Config::instance()->settings->display_errors);

$url = $_SERVER['REQUEST_URI'];
\SEngine\Core\Route::start($url);
