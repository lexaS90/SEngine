<?php


namespace SEngine\Models;


use SEngine\Core\Model;

class News extends Model
{
    const TABLE = 'news';
    const RELATION = ['author'];

    public $title;
    public $text;
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
        $fields['id'] = array(
            'tag' => 'input',  'attributes' => ['type' => 'hidden']
        );
        $fields['form_submit'] = array(
            'tag' => 'input', 'attributes' => ['type' => 'submit', 'value' => 'Отправить']
        );

        return $fields;
    }

}