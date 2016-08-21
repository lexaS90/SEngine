<?php


namespace SEngine\Controllers;


use SEngine\Core\Db;
use SEngine\Core\Ui;
use SEngine\Core\View;

class News extends Base
{
    protected function actionIndex()
    {
        $this->template = 'news.html.twig';

        $news = \SEngine\Models\News::findAll();
        $this->view->news = $news;
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
        $this->template = 'insert_news.html.twig';

       if ($_SERVER['REQUEST_METHOD'] == 'POST') {
           $artical = new \SEngine\Models\News();
           $artical->fill($_POST);

           $artical->insert();

           header('Location: /news');
       }
        else {
            $form = new Ui\Form();
            $form->fields = \SEngine\Models\News::formFields();
            
            $this->view->title = 'Добавление новости';
            $this->view->form = $form->render();
        }
    }

    protected function actionUpdate()
    {
        $this->template = 'insert_news.html.twig';

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $artical = new \SEngine\Models\News();
            $artical->fill($_POST);
            $artical->save();

            header('Location: /news');
        }
        else {
            $artical = \SEngine\Models\News::findById($_GET['id']);


            $form = new Ui\Form();
            $form->fields = \SEngine\Models\News::formFields();
            $form->setData($artical);

            $this->view->title = 'Редактирование новости';
            $this->view->form =  $form->render();
        }
    }

    protected function actionRemove()
    {
        $artical = \SEngine\Models\News::findById(5);

        $artical->delete();
    }
}