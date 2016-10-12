<?php

namespace SEngine\Models;


use SEngine\Core\Db;
use SEngine\Core\Model;
use SEngine\Core\Validation;
use SEngine\Core\ImgTools;

class User extends Model
{
    const TABLE = 'users';

    public $login;
    public $password;
    public $email;
    public $img;
    public $hash;

    /**
     * Вывод пользователя по логину
     * @param $login
     * @return static
     */
    public static function findByLogin($login)
    {
        $db = Db::instance();
        $sql = 'SELECT * FROM '. static::TABLE. ' WHERE login = :login';
        $res = $db->query($sql,
            static::class,
            [":login" => $login])[0];

        return $res ?: new static;
    }

    /**
     * Вывод пользователя по hash
     * @param $hash
     * @return static
     */
    public static function findByHash($hash)
    {
        $db = Db::instance();
        $sql = 'SELECT * FROM '. static::TABLE. ' WHERE hash = :hash';
        $res = $db->query($sql,
            static::class,
            [":hash" => $hash])[0];

        return $res ?: new static;
    }
    
    public function logIn(User $user)
    {
        $sId = md5($this->generateCode());

        session_start();
        $_SESSION['sId'] = $sId;

        $user->hash = $sId;
        $user->save();
        
    }

    public static function logOut()
    {
        session_start();

        if (self::isAuth()){
            unset($_SESSION['sId']);
        }
    }

    public static function isAuth()
    {
        session_start();

        if (!isset($_SESSION['sId']))
            return false;

        $hash = $_SESSION['sId'];
        $user = self::findByHash($hash);

        if (null == $user->id)
            return false;

        return $user;
    }

    public static function formFields()
    {
        $fields = [];

        $fields['login'] = array(
            'tag' => 'input',
            'label' => 'Login',
            'attributes' => [
                'type' => 'text',
                'class' => 'form-control',
            ]
        );

        $fields['password'] = array(
            'tag' => 'input',
            'label' => 'Password',
            'attributes' => [
                'type' => 'password',
                'class' => 'form-control',
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
            'login' => ['required' => true, 'mainFilter' => true],
            'password' => ['required' => true, 'mainFilter' => true],
        );

        $validation = new Validation($this, $rule);
        return $validation->run();
    }

    public static function formFieldsProfile()
    {
        $fields = [];

        $fields['login'] = array(
            'tag' => 'input',
            'label' => 'Login',
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

/*        $fields['oldPass'] = array(
            'tag' => 'input',
            'label' => 'oldPassword',
            'attributes' => [
                'type' => 'password',
                'class' => 'form-control',
            ]
        );*/

        $fields['password'] = array(
            'tag' => 'input',
            'label' => 'Password',
            'attributes' => [
                'type' => 'password',
                'class' => 'form-control',
            ]
        );

        $fields['img'] = array(
            'tag' => 'input',
            'attributes' => [
                'type' => 'file',
                'class' => 'fileupload',
                'data-url' => '/user/loadImg',
            ],
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

    public function validationProfile()
    {
        $rule = array(
            'login' => ['required' => true, 'mainFilter' => true],
            'password' => ['required' => true, 'mainFilter' => true],
            'email' => ['required' => true, 'mainFilter' => true, 'email' => true],
        );

        $validation = new Validation($this, $rule);
        return $validation->run();
    }

    private function generateCode($length=6) {

        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHI JKLMNOPRQSTUVWXYZ0123456789";

        $code = "";

        $clen = strlen($chars) - 1;
        while (strlen($code) < $length) {

            $code .= $chars[mt_rand(0,$clen)];
        }

        return $code;

    }

    protected function beforeSave()
    {

        if (!empty($this->img)) {
            $imgTools = ImgTools::instance();

            $imgPath = $_SERVER['DOCUMENT_ROOT'] . '/files/user/' . $this->img;
            $sDir = $_SERVER['DOCUMENT_ROOT'] . '/files/user/' . $this->img;

            $imgTools->fit($imgPath, $sDir, 50, 50);
        }
    }
}