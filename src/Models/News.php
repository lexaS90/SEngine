<?php


namespace SEngine\Models;


use SEngine\Core\imgTools;
use SEngine\Core\Model;
use SEngine\Core\Validation;

class News extends Model
{
    const TABLE = 'news';
    const RELATION = ['author'];

    public $title;
    public $text;
    public $files;
    public $author_id;

    public static function formFields()
    {
        $fields = [];
        $fields['title'] = array(
            'tag' => 'input', 'label' => 'Title', 'attributes' => ['type' => 'text']
        );
        $fields['text'] = array(
            'tag' => 'textarea',  'label' => 'Text'
        );

        $fields['files'] = array(
            'tag' => 'input',
            'label' => 'File',
            'attributes' => [
                'type' => 'file',
                'class' => 'fileupload',
                'data-url' => '/news/loadImg',
            ],
        );

        $fields['s'] = array(
            'tag' => 'input',
            'attributes' => [
                'type' => 'submit'
            ]
        );

        $fields['id'] = array(
            'tag' => 'input',  'attributes' => ['type' => 'hidden']
        );

        return $fields;
    }

    public function validation()
    {
        $rule = array(
            'title' => ['required' => true, 'mainFilter' => true],
            'text' => ['required' => true],
            'files' => ['required' => true],
        );

        $validation = new Validation($this, $rule);
        return $validation->run();
    }

    protected function beforeFill($data)
    {

        if (!file_exists($_SERVER['DOCUMENT_ROOT'].'/files/'.$data['files']))
           $data['files'] = '';

        return $data;
    }

    protected function beforeSave()
    {
        $imgTools = imgTools::instance();

        $imgPath = $_SERVER['DOCUMENT_ROOT'].'/files/'.$this->files;
        $sDir = $_SERVER['DOCUMENT_ROOT'].'/files/100_100/'.$this->files;

        $imgTools->fit($imgPath,$sDir,100,100);

    }

}