# Grom Platform - платформа для создания сайтов на Yii2.

Grom Platform позволяет разрабатывать приложение не отвлекаясь на реализацию CMS.

## Демо сайт
http://menst.webfactional.com

## Возможности

* Модули: авторизация, пользователи, меню, страницы, новости, теги, поиск, медиа менеджер и т.д.
* Древовидные категории новостей.
* Встроенная система контроля версий документов.
* Поиск на основе Elastic Search.
* SEO-friendly адреса страниц (ЧПУ)

## Установка##

Cms работает на базе [advanced application template](http://www.yiiframework.com/doc-2.0/guide-tutorial-advanced-app.html). Устанавливаем данный шаблон приложения.

#### Настройка Nginx
```nginx
server {
    charset utf-8;
    client_max_body_size 128M;

    listen 80; ## listen for ipv4
    #listen [::]:80 default_server ipv6only=on; ## listen for ipv6

    server_name yiicms.proj;
    root        /path/to/app/frontend/web;
    index       index.php;

    access_log  /path/to/app/log/access.log;
    error_log   /path/to/app/log/error.log;

    # необходимо добавить в папку frontend/web симлинк на backend/web под названием admin
	location /admin/ {
        try_files $uri $uri/ /admin/index.php?$args;
    }

    location / {
		# Redirect everything that isn't a real file to index.php
        try_files $uri $uri/ /index.php?$args;
    }

    # uncomment to avoid processing of calls to non-existing static files by Yii
    location ~ \.(js|css|png|jpg|gif|swf|ico|pdf|mov|fla|zip|rar)$ {
        try_files $uri =404;
    }
    #error_page 404 /404.html;

	location ~ \.php$ {
        include fastcgi_params;
        fastcgi_pass unix:/var/run/php5-fpm.sock;
        try_files $uri =404;
    }

    location ~ /\.(ht|svn|git) {
        deny all;
    }
}
```

#### Установка Grom Platform
Запускаем через composer

    php composer.phar require --prefer-dist gromver/yii2-platform "*"
    
Или добавляем  

    "gromver/yii2-platform": "*"
    
в require секцию composer.json файла.

#### Настройка Grom Platform
Заменяем фронтенд, бэкенд и консольное приложения на соответсвующие из данного расширения. Для этого правим файлы:

* /backend/web/index.php
```
  $application = new \gromver\platform\backend\Application($config); // yii\web\Application($config);
```
* /frontend/web/index.php   
```
  $application = new \gromver\platform\frontend\Application($config); // yii\web\Application($config);
```
* /yii.php
```
  $application = new \gromver\platform\console\Application($config); // yii\console\Application($config);
```

Нужно отредактировать стандартный конфиг: /frontend/config/main.php, /backend/config/main.php

``` 
[
  'components' => [
      'user' => [
          //'identityClass' => 'common\models\User',  //закоментировать или удалить эту строку
          'enableAutoLogin' => true,
      ],
    ]
]
```
#### Добавляем таблицы в БД

    php yii migrate --migrationPath=@gromver/platform/migrations

#### Подключение поиска(опционально)
* Установить [Elasticsearch](http://www.elasticsearch.org/guide/en/elasticsearch/reference/current/_installation.html)
* Подключаем поисковые модули еластиксерча. Настрайваем консольное приложение, правим /console/config/main.php
```
[
    'modules' => [
        'grom' => [
            'modules' => [
                'search' => [
                    'class' => 'gromver\platform\common\modules\elasticsearch\Module',
                    'elasticsearchIndex' => 'myapp'	//по умолчанию 'cmf'
                ]
            ]
        ]
    ],
]
```
Фронтенд, правим /frontend/config/main.php
```
[
    'modules' => [
        'grom' => [
            'modules' => [
                'search' => [
                    'class' => 'gromver\platform\frontend\modules\elasticsearch\Module',
                    'elasticsearchIndex' => 'myapp'	//по умолчанию 'cmf'
                ]
            ]
        ]
    ],
]
```
Бэкенд, правим /backend/config/main.php
```
[
    'modules' => [
        'grom' => [
            'modules' => [
                'search' => [
                    'class' => 'gromver\platform\backend\modules\elasticsearch\Module',
                    'elasticsearchIndex' => 'myapp'	//по умолчанию 'cmf'
                ]
            ]
        ]
    ],
]
```
* Применяем миграцию для Elasticsearch
```
  php yii migrate --migrationPath=@gromver/platform/migrations/elasticsearch
```
