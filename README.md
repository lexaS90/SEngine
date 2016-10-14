# sEngine

## Основное

### Установить шаблон
```php
$this->template = 'settings.html.twig';
```

### Заполнить объект модели значением
```php
$settings->fill($_POST);
```

### Методы конструктора
```php
$this->beforeAction();
$this->base();
$this->baseDir();
$this->IsGet();
$this->IsPost();
```

## Представление

### Передать значение в шаблон
```php
$this->view->title = 'title';
```

### Вывод шаблона
```php
$this->view->displayTwig($this->template);
```

### Получение шаблона
```php
return $this->view->renderTwig('ui/form.html.twig');
```

## Пользователи

### Проверка на авторизацию пользователя
```php
User::isAuth()
```

### Вход в систему
```php
$user->logIn(Model $user);
```

### Выход с системы
```php
\SEngine\Models\User::logOut();
```


## Статусы

### Установить сообщение

```php
$this->msg->setMsg(string $text [, string $status]);
```
#### status:
* success
* info
* warning
* danger

### Считать сообщение

```php
$this->msg->getMsg();
```

### Установить сообщение в сессию

```php
$this->msg->setSessionMsg(string $text [, string $status]);
```

### Считать сообщение с сессии

```php
$this->msg->getSessionMsg();
```

## Настройки

src/config/php:
```php
<?php
return array(
    'db' =>array(
        'db_host' => 'localhost',
        'db_name' => 'sengine',
        'db_user' => 'root',
        'db_pass' => '12345',
    ),

    'site' => array(
        'lang_default' => 'ru',
    ),

    'settings' => array(
        'display_errors' => 1,
        'controller_default' => 'News',
        'action_default' => 'Index',
    ),
);
```

### Считать настройки

```php
Config::instance()->settings->controller_default;
```

## Сообщения

src/message

```php
(new Message('error'))->site->error_404;
```
## Формы

### Инициализация

```php
$form = new Form();
```

### Установить поля формы
```php
$form->fields = array(...);
```

#### text
```php
$fields['title'] = array(
  'tag' => 'input',
  'label' => 'Title',
  'attributes' => [
    'type' => 'text',
    'class' => 'form-control',
  ]
 );
```

#### textarea
```php
$fields['text'] = array(
	'tag' => 'textarea',
	'label' => 'Text'
);
```

#### file
```php
$fields['files'] = array(
  'tag' => 'input',
  'attributes' => [
    'type' => 'file',
    'class' => 'fileupload',
    'data-url' => '/news/loadImg',
  ],
);
```

#### submit
```php
$fields['s'] = array(
  'tag' => 'input',
  'attributes' => [
  'type' => 'submit'
]
```

### Установить значение по умолчанию
```php
$form->setData(Model $obj|Array());
```

### Установить ошибки формы
```php
$form->setErrors(array());
```

## Валидация формы

### Инициализация
```php
$validation = new Validation($obj, $rule);
```

#### Правила
```php
$rule = array(
  'title' => ['required' => true, 'minLength' => 5, 'maxLength' => 50],
  'email' => ['required' => true, 'email' => true],
);
```

### Запуск валидации
```php
$validation->run();
```
#### Return
```php
obj.errors
obj.errorText
obj.errorField
```