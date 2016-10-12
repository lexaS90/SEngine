<?php


namespace SEngine\Controllers;



use SEngine\Core\Exceptions\MultiException;
use SEngine\Core\Libs\UploadHandler;
use SEngine\Core\Ui;
use SEngine\Core\View;
use \SEngine\Core\Exceptions\NotFound;
use SEngine\Models\Slider;
use SEngine\Models\User;

class News extends Base
{
    protected function actionIndex()
    {
        $this->template = 'news.html.twig';        
        $this->view->news = \SEngine\Models\News::findAll();
        $this->view->sliders = Slider::findAll();

        if (User::isAuth()) {

            $this->view->controls = array(
                'add' => ['href' =>'http://' . $_SERVER['HTTP_HOST'] . '/news/insert', 'text' => 'Добавить'],
                'edit' => ['href' => 'http://' . $_SERVER['HTTP_HOST'] . '/news/update?id=', 'text' => 'edit'],
                'remove' => ['href' => 'http://' . $_SERVER['HTTP_HOST'] . '/news/remove?id=', 'text' => 'remove'],
            );
        }
        
    }

    protected function actionArtical()
    {
        $this->template = 'one.html.twig';

        $artical = \SEngine\Models\News::findById($_GET['id']);

        $this->view->artical = $artical;
        $this->view->author = $artical->author;
    }
    
    protected function actionInsert()
    {
       if (User::isAuth()) {
            if ($this->isAjax) {
                $form = new Ui\Form();
                $form->fields = \SEngine\Models\News::formFields();

                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    $artical = new \SEngine\Models\News();
                    $artical->fill($_POST);

                    $valid = $artical->validation();

                    if ($valid->isValid) {
                        $artical->save();
                        $this->msg->setSessionMsg('Новость сохранена');
                        $this->ajaxData->status = 1;
                        $this->ajaxData->redirect = '/news';
                    } else {
                        $this->ajaxData->errors = $valid->errors;
                        $this->ajaxData->status = 0;
                    }
                }

                $this->ajaxData->title = 'Добавление новости';
                $this->ajaxData->body = $form->render(['imgPath' => 'http://' . $_SERVER['HTTP_HOST'] . '/files/']);
            } else {
                throw new NotFound;
            }
        }
        else{
            throw new NotFound;
        }
    }

    protected function actionUpdate()
    {
        if (User::isAuth()) {
            if ($this->isAjax) {
                $form = new Ui\Form();
                $form->fields = \SEngine\Models\News::formFields();

                if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                    $artical = new \SEngine\Models\News();
                    $artical->fill($_POST);


                    $valid = $artical->validation();

                    if ($valid->isValid) {
                        $artical->save();
                        $this->msg->setSessionMsg('Новость обновлена');
                        $this->ajaxData->status = 1;
                        $this->ajaxData->redirect = '/news';
                    } else {
                        $this->ajaxData->errors = $valid->errors;
                        $this->ajaxData->status = 0;
                    }
                }

                $artical = \SEngine\Models\News::findById($_GET['id']);
                $form->setData($artical);

                $this->ajaxData->title = 'Редактирование новости';
                $this->ajaxData->body = $form->render(['imgPath' => 'http://' . $_SERVER['HTTP_HOST'] . '/files/']);
            } else {
                throw new NotFound;
            }
        }
        else{
            throw new NotFound;
        }
    }

    protected function actionRemove()
    {
        if (User::isAuth()) {
            if ($this->isAjax){
                $this->ajaxData->title = 'Удаление новости';
                $this->ajaxData->body =  '<p>Вы действительно хотите удалить новость?</p>';

                if ($_SERVER['REQUEST_METHOD'] == 'POST'){
                    $artical = \SEngine\Models\News::findById($_GET['id']);
                    $artical->delete();
                    $this->msg->setSessionMsg('Новость удалена');
                    $this->ajaxData->status = 1;
                    $this->ajaxData->redirect = '/news';
                }
            }
            else{
                throw new NotFound;
            }
        }
        else
        {
            throw new NotFound;
        }
    }

    protected function actionLoadImg()
    {
        $upload_handler = new UploadHandler(array(
            'param_name' => 'files'
        ));
    }

    public function actionTest()
    {
        $this->template = 'insert_news.html.twig';

        $form = new Ui\Form();
        $form->fields = \SEngine\Models\News::formFields();

        $this->view->form = $form->render(['imgPath' => 'http://' . $_SERVER['HTTP_HOST'] . '/files/']);
    }
}