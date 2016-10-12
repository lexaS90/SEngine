<?php


namespace SEngine\Controllers;


use SEngine\Core\Libs\UploadHandler;
use SEngine\Core\Ui\Form;
use SEngine\Core\Ui\StatusMessage;
use SEngine\Core\Validation;

class User extends Base
{

    public function actionIndex()
    {
        if (\SEngine\Models\User::isAuth()) {
            $this->template = 'user_profile.html.twig';
            $user = \SEngine\Models\User::findById(1);

            $hash = $user->hash;
            $user->password = '';
            
            $form = new Form();
            $form->fields = \SEngine\Models\User::formFieldsProfile();
            
            if ($_SERVER['REQUEST_METHOD'] == 'POST'){
                $user = new \SEngine\Models\User();
                $user->fill($_POST);

                $valid = $user->validationProfile();

                if ($valid->isValid){
                    $user->password = password_hash($user->password, PASSWORD_BCRYPT);
                    $user->hash = $hash;

                    $user->save();

                    $this->msg->setSessionMsg('Профиль успешно обновлен');
                    header('Location: http://'.$_SERVER['HTTP_HOST'].'/user');
                }
                else{
                    foreach ($valid->errorText as $error)
                        $this->msg->setMsg($error, 'danger');
                }
            }


            $form->setData($user);
            $this->view->form = $form->render(['imgPath' => 'http://' . $_SERVER['HTTP_HOST'] . '/files/user/']);
        }
        else{
           header('Location: http://'.$_SERVER['HTTP_HOST'].'/user/login');
        }
    }

    public function actionLogin()
    {
        if (\SEngine\Models\User::isAuth()) {
            header('Location: http://'.$_SERVER['HTTP_HOST'].'/user');
        }
        else{
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
                    $this->msg->setSessionMsg('Неверный логин или пароль', 'danger');
                    header('location: http://'.$_SERVER['HTTP_HOST'].'/user/login');
                }

            }

            $this->view->title = 'Вход пользователя';
            $this->view->form = $form->render();
        }
    }

    public function actionLogout()
    {
        \SEngine\Models\User::logOut();
        header('location: http://'.$_SERVER['HTTP_HOST'].'/user/login');

    }

    protected function actionLoadImg()
    {
        $upload_handler = new UploadHandler(array(
            'upload_dir' => $_SERVER['DOCUMENT_ROOT'].'/files/user/',
            'upload_url' => 'http://'.$_SERVER['HTTP_HOST'].'/files/user/',
            'param_name' => 'img',
        ));
    }
}