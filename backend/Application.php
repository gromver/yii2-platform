<?php
/**
 * @link https://github.com/menst/yii2-cms.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/menst/yii2-cms/blob/master/LICENSE
 * @package yii2-cms
 * @version 1.0.0
 */

namespace menst\cms\backend;

use yii\helpers\ArrayHelper;

/**
 * Class Application
 * @package yii2-cms
 * @author Gayazov Roman <m.e.n.s.t@yandex.ru>
 */
class Application extends \yii\web\Application {
    public $language = 'en';
    public $languages = ['en', 'ru'];
    public $sourceLanguage = 'en';
    public $layout = '@menst/cms/backend/views/layouts/main';

    private $_modulesHash;

    /**
     * @inheritdoc
     */
    public function __construct($config = [])
    {
        $config = ArrayHelper::merge([
            'components' => [
                'request' => [
                    'class' => 'menst\cms\common\components\Request',
                    'csrfParam' => '_csrfBackend',
                ],
                'urlManager' => [
                    'class' => 'menst\cms\common\components\UrlManager',
                    'enablePrettyUrl' => true,
                    'showScriptName' => false,
                ],
                'user' => [
                    'class' => 'menst\cms\common\components\User',
                    'idParam' => '__idBackend',
                    'authTimeoutParam' => '__expireBackend',
                    'absoluteAuthTimeoutParam' => '__absoluteExpireBackend',
                    'returnUrlParam' => '__returnUrlBackend',
                    'identityCookie' => ['name' => '_identityBackend', 'httpOnly' => true]
                ],
                'errorHandler' => [
                    'class' => 'yii\web\ErrorHandler',
                    'errorAction' => 'cms/default/error'
                ],
                'authManager' => [
                    'class' => 'yii\rbac\DbManager',
                    'itemTable' => '{{%cms_auth_item}}',
                    'itemChildTable' => '{{%cms_auth_item_child}}',
                    'assignmentTable' => '{{%cms_auth_assignment}}',
                    'ruleTable' => '{{%cms_auth_rule}}'
                ],
                'cache' => ['class' => 'yii\caching\FileCache'],
                'elasticsearch' => ['class' => 'yii\elasticsearch\Connection'],
                'i18n' => [
                    'translations' => [
                        '*' => [
                            'class' => 'yii\i18n\PhpMessageSource'
                        ],
                    ],
                ],
            ],
            'modules' => [
                'cms' => [
                    'class' => 'menst\cms\backend\modules\main\Module',
                    'modules' => [
                        'user'      => ['class' => 'menst\cms\backend\modules\user\Module'],
                        'auth'      => ['class' => 'menst\cms\backend\modules\auth\Module'],
                        'menu'      => ['class' => 'menst\cms\backend\modules\menu\Module'],
                        'news'      => ['class' => 'menst\cms\backend\modules\news\Module'],
                        'page'      => ['class' => 'menst\cms\backend\modules\page\Module'],
                        'tag'       => ['class' => 'menst\cms\backend\modules\tag\Module'],
                        'version'   => ['class' => 'menst\cms\backend\modules\version\Module'],
                        'widget'    => ['class' => 'menst\cms\backend\modules\widget\Module'],
                        'media'     => ['class' => 'menst\cms\backend\modules\media\Module'],
                        'search'    => ['class' => 'menst\cms\backend\modules\search\Module'],
                    ]
                ],
                'gridview' => ['class' => 'kartik\grid\Module']
            ]
        ], $config);

        $this->_modulesHash = md5(json_encode($config['modules']));

        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    protected function bootstrap()
    {
        $this->bootstrap = array_merge($this->bootstrap, ['cms']);

        parent::bootstrap();
    }

    /**
     * @return string
     */
    public function getModulesHash() {
        return $this->_modulesHash;
    }

    /**
     * @return array
     */
    public function getLanguagesList()
    {
        return array_combine($this->languages, $this->languages);
    }

    /**
     * @return \yii\elasticsearch\Connection
     */
    public function getElasticSearch()
    {
        return $this->get('elasticsearch');
    }
}