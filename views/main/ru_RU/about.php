<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = Yii::t('common', 'About');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>

    <pre>
Yet another task tracker
============================

Я заметил, что практически нет адекватного багтреккера, написанного на пхп.
И решил написать свой велосипед. Скорее для изучения Yii, чем в ожиданиии
создать полезный и востребованный продукт.

DIRECTORY STRUCTURE
-------------------

      assets/             contains assets definition
      commands/           contains console commands (controllers)
      config/             contains application configurations
      controllers/        contains Web controller classes
      mail/               contains view files for e-mails
      models/             contains model classes
      runtime/            contains files generated during runtime
      tests/              contains various tests for the basic application
      vendor/             contains dependent 3rd-party packages
      views/              contains view files for the Web application
      web/                contains the entry script and Web resources



REQUIREMENTS
------------

* PHP 5.4.0.


INSTALLATION
------------

### C github

Когда он будет настолько минимально функционален, чтобы его туда выложить.

### Скопировав и распаковав архив


CONFIGURATION
-------------

### Database

Edit the file `config/db.php` with real data, for example:

```php
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=yii2basic',
    'username' => 'root',
    'password' => '1234',
    'charset' => 'utf8',
];
```

И затем применив миграции:
```bash
$ php yii migrate/up
```

    </pre>

    <code><?= __FILE__ ?></code>
</div>
