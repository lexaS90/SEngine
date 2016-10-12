<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 09.10.2016
 * Time: 14:03
 */

namespace SEngine\Controllers;


use SEngine\Core\Ui\Form;
use SEngine\Models\User;
use SEngine\Core\Libs\UploadHandler;
use SEngine\Core\Ui;
use \SEngine\Core\Exceptions\NotFound;


class Slider extends Base
{
    public function actionList()
    {

       if (User::isAuth()) {

           $this->template = 'list_slider.html.twig';
           $sliders = \SEngine\Models\Slider::findAll();

            $this->view->controls = array(
                'add' => ['href' =>'http://' . $_SERVER['HTTP_HOST'] . '/slider/insert', 'text' => 'Добавить'],
                'edit' => ['href' => 'http://' . $_SERVER['HTTP_HOST'] . '/slider/update?id=', 'text' => 'edit'],
                'remove' => ['href' => 'http://' . $_SERVER['HTTP_HOST'] . '/slider/remove?id=', 'text' => 'remove'],
            );

           $this->view->sliders = $sliders;

       }
        else{
            throw new NotFound;
        }
    }

    public function actionInsert()
    {
        if (User::isAuth()) {
            if ($this->isAjax) {
                $form = new Form();
                $form->fields = \SEngine\Models\Slider::formFields();

                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    $slider = new \SEngine\Models\Slider();
                    $slider->fill($_POST);

                    $valid = $slider->validation();

                    if ($valid->isValid) {
                        $slider->save();
                        $this->msg->setSessionMsg('Слайд добавлен');
                        $this->ajaxData->status = 1;
                        $this->ajaxData->redirect = 'http://' . $_SERVER['HTTP_HOST'] . '/slider/list';
                    } else {
                        $this->ajaxData->errors = $valid->errors;
                        $this->ajaxData->status = 0;
                        $form->setData($_POST);
                    }
                }

                $this->ajaxData->title = 'Добавление слайдера';
                $this->ajaxData->body = $form->render(['imgPath' => 'http://' . $_SERVER['HTTP_HOST'] . '/files/slider/']);

            } else {
                throw new NotFound;
            }
        }
        else{
            throw new NotFound;
        }
    }

    public function actionUpdate()
    {
        if (User::isAuth()) {
            if ($this->isAjax) {
                $form = new Form();
                $form->fields = \SEngine\Models\Slider::formFields();

                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    $sliders = new \SEngine\Models\Slider();
                    $sliders->fill($_POST);

                    $valid = $sliders->validation();

                    if ($valid->isValid) {
                        $sliders->save();
                        $this->msg->setSessionMsg('Слайдер обновлен');
                        $this->ajaxData->status = 1;
                        $this->ajaxData->redirect = 'http://' . $_SERVER['HTTP_HOST'] . '/slider/list';
                    } else {
                        $this->ajaxData->errors = $valid->errors;
                        $this->ajaxData->status = 0;
                    }

                }

                $slider = \SEngine\Models\Slider::findById($_GET['id']);
                $form->setData($slider);

                $this->ajaxData->title = 'Редактирование слайдера';
                $this->ajaxData->body = $form->render(['imgPath' => 'http://' . $_SERVER['HTTP_HOST'] . '/files/slider/']);

            } else {
                throw new NotFound;
            }
        }
        else{
            throw new NotFound;
        }
    }

    public function actionRemove()
    {
        if (User::isAuth()) {
            if ($this->isAjax) {
                $this->ajaxData->title = 'Удаление слайда';
                $this->ajaxData->body = '<p>Вы действительно хотите удалить слайд?</p>';

                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    $slide = \SEngine\Models\Slider::findById($_GET['id']);
                    $slide->delete();
                    $this->msg->setSessionMsg('Слайд удален');
                    $this->ajaxData->status = 1;
                    $this->ajaxData->redirect = 'http://' . $_SERVER['HTTP_HOST'] . '/slider/list';
                }
            } else {
                throw new NotFound;
            }
        }
        else{
            throw new NotFound;
        }
    }

    protected function actionLoadImg()
    {
        $upload_handler = new UploadHandler(array(
            'upload_dir' => $_SERVER['DOCUMENT_ROOT'].'/files/slider/',
            'upload_url' => 'http://'.$_SERVER['HTTP_HOST'].'/files/slider/',
        ));
    }
}