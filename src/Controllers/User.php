<?php


namespace SEngine\Controllers;


use SEngine\Core\Ui\Form;
use SEngine\Core\Ui\StatusMessage;

class User extends Base
{
    public function actionLogin()
    {
        $this->template = 'login.html.twig';

        $form = new Form();
        $form->fields =  \SEngine\Models\User::formFields();

        if ($_SERVER['REQUEST_METHOD'] == 'POST'){

            $user = \SEngine\Models\User::findByLogin($_POST['login']);

            if (($user->id !== null) AND (password_verify($_POST['password'], $user->password))){
                $user->logIn($user);
                header('location: http://'.$_SERVER['HTTP_HOST']);
            }
            else{
                (new StatusMessage())->set('Неверный логин или пароль', 'danger');
                header('location: http://'.$_SERVER['HTTP_HOST'].'/user/login');
            }

        }

        $this->view->title = 'Вход пользователя';
        $this->view->form = $form->render();
    }

    public function actionLogout()
    {
        \SEngine\Models\User::logOut();
        header('location: http://'.$_SERVER['HTTP_HOST'].'/user/login');

    }
}