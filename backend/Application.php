<?php
/**
 * @link https://github.com/gromver/yii2-cmf.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/gromver/yii2-grom/blob/master/LICENSE
 * @package yii2-cmf
 * @version 1.0.0
 */

namespace gromver\platform\backend;

use yii\helpers\ArrayHelper;

/**
 * Class Application
 * @package yii2-cmf
 * @author Gayazov Roman <gromver5@gmail.com>
 */
class Application extends \gromver\platform\common\Application {
    public $defaultRoute = 'grom/default/index';
    public $layout = '@gromver/platform/backend/views/layouts/main';

    /**
     * @inheritdoc
     */
    public function __construct($config = [])
    {
        $config = ArrayHelper::merge([
            'components' => [
                'request' => [
                    'class' => 'gromver\platform\common\components\Request',
                    'csrfParam' => '_csrfBackend',
                ],
                'urlManager' => [
                    'class' => 'gromver\platform\common\components\UrlManager',
                    'enablePrettyUrl' => true,
                    'showScriptName' => false,
                ],
                'user' => [
                    'class' => 'gromver\platform\common\components\User',
                    'idParam' => '__idBackend',
                    'authTimeoutParam' => '__expireBackend',
                    'absoluteAuthTimeoutParam' => '__absoluteExpireBackend',
                    'returnUrlParam' => '__returnUrlBackend',
                    'identityCookie' => ['name' => '_identityBackend', 'httpOnly' => true]
                ],
                'errorHandler' => [
                    'class' => 'yii\web\ErrorHandler',
                    'errorAction' => 'grom/default/error'
                ],
                'authManager' => [
                    'class' => 'yii\rbac\DbManager',
                    'itemTable' => '{{%grom_auth_item}}',
                    'itemChildTable' => '{{%grom_auth_item_child}}',
                    'assignmentTable' => '{{%grom_auth_assignment}}',
                    'ruleTable' => '{{%grom_auth_rule}}'
                ],
                'cache' => ['class' => 'yii\caching\FileCache'],
                'elasticsearch' => ['class' => 'yii\elasticsearch\Connection'],
                'assetManager' => [
                    'bundles' => [
                        'mihaildev\ckeditor\Assets' => [
                            'sourcePath' => '@gromver/platform/backend/assets/ckeditor',
                        ],
                    ],
                ],
                'i18n' => [
                    'translations' => [
                        '*' => [
                            'class' => 'yii\i18n\PhpMessageSource'
                        ],
                    ],
                ],
            ],
            'modules' => [
                'grom' => [
                    'class' => 'gromver\platform\backend\modules\main\Module',
                    'modules' => [
                        'user'      => ['class' => 'gromver\platform\backend\modules\user\Module'],
                        'auth'      => ['class' => 'gromver\platform\backend\modules\auth\Module'],
                        'menu'      => ['class' => 'gromver\platform\backend\modules\menu\Module'],
                        'news'      => ['class' => 'gromver\platform\backend\modules\news\Module'],
                        'page'      => ['class' => 'gromver\platform\backend\modules\page\Module'],
                        'tag'       => ['class' => 'gromver\platform\backend\modules\tag\Module'],
                        'version'   => ['class' => 'gromver\platform\backend\modules\version\Module'],
                        'widget'    => ['class' => 'gromver\platform\backend\modules\widget\Module'],
                        'media'     => ['class' => 'gromver\platform\backend\modules\media\Module'],
                        //'search'    => ['class' => 'gromver\platform\backend\modules\elasticsearch\Module'],
                    ]
                ],
                'gridview' => ['class' => 'kartik\grid\Module']
            ]
        ], $config);

        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->bootstrap = array_merge($this->bootstrap, ['grom']);

        parent::init();
    }
}