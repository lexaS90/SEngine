<?php

namespace SEngine\Core;

use SEngine\Core\Libs\Std;

class ajax
{
    use Std;

    public function display(){
        $ajaxData = [];
        foreach($this->data as $k => $v){
            $ajaxData[$k] = $v;
        }

        echo json_encode($ajaxData);die();
    }
}