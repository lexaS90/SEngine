<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 09.10.2016
 * Time: 14:04
 */

namespace SEngine\Models;


use SEngine\Core\Model;
use SEngine\Core\Validation;
use SEngine\Core\ImgTools;

class Slider extends Model
{
    const TABLE = 'slider';

    public $title;
    public $files;

    public static function formFields()
    {
        $fields = [];
        $fields['title'] = array(
            'tag' => 'input',
            'label' => 'Title',
            'attributes' => [
                'type' => 'text',
                'class' => 'form-control',
            ]
        );

        $fields['files'] = array(
            'tag' => 'input',
            'attributes' => [
                'type' => 'file',
                'class' => 'fileupload',
                'data-url' => '/slider/loadImg',
            ],
        );

        $fields['id'] = array(
            'tag' => 'input',
            'attributes' => [
                'type' => 'hidden'
            ]
        );

        return $fields;
    }

    public function validation()
    {
        $rule = array(
            'title' => ['required' => true, 'mainFilter' => true],
            'files' => ['required' => true],
        );

        $validation = new Validation($this, $rule);
        return $validation->run();
    }

    protected function beforeSave()
    {
        if (!empty($this->files)) {
            $imgTools = ImgTools::instance();

            $imgPath = $_SERVER['DOCUMENT_ROOT'] . '/files/slider/' . $this->files;
            $sDir = $_SERVER['DOCUMENT_ROOT'] . '/files/slider/' . $this->files;

            $imgTools->fit($imgPath, $sDir, 600, 300);
        }
    }

}