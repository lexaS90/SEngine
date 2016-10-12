<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 09.10.2016
 * Time: 17:56
 */

namespace SEngine\Models;


use SEngine\Core\Model;
use SEngine\Core\Validation;

class Settings extends Model
{
    const TABLE = 'settings';

    public $site;
    public $email;
    public $phone;
    public $siteName;
    public $siteFooter;

    public static function formFields()
    {
        $fields = [];
        $fields['site'] = array(
            'tag' => 'input',
            'label' => 'Site',
            'attributes' => [
                'type' => 'text',
                'class' => 'form-control',
            ]
        );

        $fields['email'] = array(
            'tag' => 'input',
            'label' => 'Email',
            'attributes' => [
                'type' => 'text',
                'class' => 'form-control',
            ]
        );

        $fields['phone'] = array(
            'tag' => 'input',
            'label' => 'Phone',
            'attributes' => [
                'type' => 'text',
                'class' => 'form-control',
            ]
        );

        $fields['siteName'] = array(
            'tag' => 'input',
            'label' => 'SiteName',
            'attributes' => [
                'type' => 'text',
                'class' => 'form-control',
            ]
        );

        $fields['siteFooter'] = array(
            'tag' => 'textarea',
            'label' => 'SiteFooter',
            'attributes' => [
                'type' => 'text',
                'class' => 'form-control',
            ]
        );

        $fields['id'] = array(
            'tag' => 'input',
            'attributes' => [
                'type' => 'hidden'
            ]
        );

        $fields['submit'] = array(
            'tag' => 'input',
            'attributes' => [
                'type' => 'submit',
                'class' => 'btn btn-primary',
            ]
        );

        return $fields;
    }

    public function validation()
    {
        $rule = array(
            'site' => ['required' => true, 'mainFilter' => true],
            'email' => ['required' => true, 'mainFilter' => true, 'email' => true],
            'phone' => ['required' => true, 'mainFilter' => true],
            'siteName' => ['required' => true, 'mainFilter' => true],
            'siteFooter' => ['required' => true],
        );

        $validation = new Validation($this, $rule);
        return $validation->run();
    }
}