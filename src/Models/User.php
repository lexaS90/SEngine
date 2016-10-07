<?php

namespace SEngine\Models;


use SEngine\Core\Db;
use SEngine\Core\Model;

class User extends Model
{
    const TABLE = 'users';

    public $login;
    public $password;
    public $email;
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
        if (self::isAuth()){
            unset($_SESSION['sId']);
        }
    }

    public static function isAuth()
    {
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
            'attributes' => ['type' => 'text']
        );

        $fields['password'] = array(
            'tag' => 'input', 'label' => 'Password', 'attributes' => ['type' => 'password']
        );

        $fields['submit'] = array(
            'tag' => 'input',
            'attributes' => [
                'type' => 'submit'
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

    private function generateCode($length=6) {

        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHI JKLMNOPRQSTUVWXYZ0123456789";

        $code = "";

        $clen = strlen($chars) - 1;
        while (strlen($code) < $length) {

            $code .= $chars[mt_rand(0,$clen)];
        }

        return $code;

    }
}