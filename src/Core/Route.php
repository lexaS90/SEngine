<?php


namespace SEngine\Core;


use \SEngine\Controllers\Error;
use \SEngine\Core\Exceptions\NotFound;


class Route
{
    /**
     * Запуск роутера
     * Выбор контроллера и метода по url
     * @param $url
     */
    public static function start($url)
    {
        preg_match('~^/?(\w+)/?(\w+)?(!\?.*)?~', $url, $matches);

        $controllerDefault = Config::instance()->settings->controller_default;
        $actionDefault = Config::instance()->settings->action_default;

        $controllerName = '\\SEngine\\Controllers\\' . (str_replace('/', '\\', ucwords($matches[1])) ?: $controllerDefault);
        $actionName = $matches[2] ? ucfirst($matches[2]): $actionDefault;

        if (class_exists($controllerName)){
            $controller = new $controllerName();
        }
        else{
            throw new NotFound();
        }

        if (method_exists($controller, 'action'. $actionName)){
            $controller->action($actionName);
        }
        else{
            throw new NotFound();
        }

    }
}