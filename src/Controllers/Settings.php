<?php
namespace SEngine\Controllers;


use SEngine\Core\Exceptions\NotFound;
use SEngine\Core\Ui\Form;
//use SEngine\Core\Ui\StatusMessage;
use SEngine\Models\User;
use SEngine\Core\Ui;

class Settings extends Base
{
    public function actionIndex()
    {

        if (User::isAuth()){
            $this->template = 'settings.html.twig';

            $settings = \SEngine\Models\Settings::findById(1);

            $form = new Form();
            $form->fields = \SEngine\Models\Settings::formFields();

            if ($_SERVER['REQUEST_METHOD'] == 'POST'){
                $settings = new \SEngine\Models\Settings();
                $settings->fill($_POST);

                $valid = $settings->validation();

                if ($valid->isValid){
                    $settings->save();
                    $this->msg->setSessionMsg('Настройки сохранены');
                    header('location: http://'.$_SERVER['HTTP_HOST'].'/settings');
                }
                else{
                    foreach ($valid->errorText as $error)
                        $this->msg->setMsg($error, 'danger');

                    $form->setErrors($valid->errorField);
                }
            }

            $form->setData($settings);

            $this->view->form = $form->render();

        }
        else{
            throw new NotFound();
        }
    }
}