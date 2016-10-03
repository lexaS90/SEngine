<?php


namespace SEngine\Controllers;


use SEngine\Core\Db;
use SEngine\Core\Exceptions\MultiException;
use SEngine\Core\Libs\UploadHandler;
use SEngine\Core\Ui;
use SEngine\Core\View;
use \SEngine\Core\Exceptions\NotFound;

class News extends Base
{
    protected function actionIndex()
    {
        $this->template = 'news.html.twig';

        $this->view->news = \SEngine\Models\News::findAll();
        
        $this->view->addButton = ['href' => 'http://'.$_SERVER['HTTP_HOST']. '/news/insert', 'text' => 'Добавить'];
        $this->view->controls = array(
            'edit' => ['href' => 'http://'.$_SERVER['HTTP_HOST']. '/news/update?id=', 'text' => 'edit'],
            'remove' => ['href' => 'http://'.$_SERVER['HTTP_HOST']. '/news/remove?id=', 'text' => 'remove'],
        );
        
        
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
        if ($this->isAjax){
            $form = new Ui\Form();
            $form->fields = \SEngine\Models\News::formFields();

            if ($_SERVER['REQUEST_METHOD'] == 'POST'){
                $artical = new \SEngine\Models\News();
                $artical->fill($_POST);

                try{
                    $isValid = $artical->validation();
                }catch (MultiException $ex){
                    foreach($ex as $k => $v){
                       $error[$v['field']] = $v['errorText'];
                       $this->ajaxData->error = $error;

                    }

                }
                if ($isValid) {
                    $artical->insert();
                    (new Ui\StatusMessage())->set('Новость сохранена');
                    $this->ajaxData->status = 1;
                    $this->ajaxData->redirect = '/news';
                }
                else {
                    $form->setData($artical);
                    $this->ajaxData->status = 0;
                }
            }
            $this->ajaxData->title = 'Добавление новости';
            $this->ajaxData->body = $form->render(['imgPath' => 'http://'.$_SERVER['HTTP_HOST'].'/files/']);
        }
        else{
            throw new NotFound;
        }
    }

    protected function actionUpdate()
    {
        if ($this->isAjax){
            $form = new Ui\Form();
            $form->fields = \SEngine\Models\News::formFields();

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                $artical = new \SEngine\Models\News();
                $artical->fill($_POST);

                try{
                    $isValid = $artical->validation();
                }catch(MultiException $ex){
                    foreach($ex as $k => $v) {
                        $error[$v['field']] = $v['errorText'];
                        $this->ajaxData->error = $error;
                    }
                }

                if ($isValid){
                    $artical->save();
                    (new Ui\StatusMessage())->set('Новость обновлена');
                    $this->ajaxData->status = 1;
                    $this->ajaxData->redirect = '/news';
                }
                else{
                    $this->ajaxData->status = 0;
                }
            }

            $artical = \SEngine\Models\News::findById($_GET['id']);
            $form->setData($artical);

            $this->ajaxData->title = 'Редактирование новости';
            $this->ajaxData->body =  $form->render(['imgPath' => 'http://'.$_SERVER['HTTP_HOST'].'/files/']);
        }
        else{
            throw new NotFound;
        }
    }

    protected function actionRemove()
    {
        if ($this->isAjax){
            $this->ajaxData->title = 'Удаление новости';
            $this->ajaxData->body =  '<p>Вы действительно хотите удалить новость?</p>';

            if ($_SERVER['REQUEST_METHOD'] == 'POST'){
                $artical = \SEngine\Models\News::findById($_GET['id']);
                $artical->delete();
                (new Ui\StatusMessage())->set('Новость удалена');
                $this->ajaxData->status = 1;
                $this->ajaxData->redirect = '/news';
            }
        }
        else{
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

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $artical = new \SEngine\Models\News();
            $artical->fill($_POST);
            $artical->save();
        }

        $artical = \SEngine\Models\News::findById($_GET['id']);
        $form->setData($artical);

        $this->view->title = 'Редактирование новости';
        $this->view->form =  $form->render(['imgPath' => 'http://'.$_SERVER['HTTP_HOST'].'/files/']);
    }
}