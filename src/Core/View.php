<?php


namespace SEngine\Core;


use SEngine\Core\Libs\Std;

class View
{
    use Std;

    /**
     * Рендер шаблона
     * @param $template
     * @return string
     */
    public function render($template)
    {
        foreach($this->data as $k => $v)
            $$k = $v;

        ob_start();
        include $template;
        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }

    /**
     * Вывод шаблона
     * @param $template
     */
    public function display($template)
    {
        echo $this->render($template);
    }
    
    public function renderTwig($templates)
    {
        $data = [];

        foreach($this->data as $k => $v)
            $data[$k] = $v;
        
        $loader = new \Twig_Loader_Filesystem('src/Templates');
        $twig = new \Twig_Environment($loader);

        return $twig->render($templates, $data);
    }

    public function displayTwig($templates)
    {
        echo $this->renderTwig($templates);
    }
}