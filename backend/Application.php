<?php
/**
 * @link https://github.com/gromver/yii2-cmf.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/gromver/yii2-cmf/blob/master/LICENSE
 * @package yii2-cmf
 * @version 1.0.0
 */

namespace gromver\cmf\backend;

use yii\helpers\ArrayHelper;

/**
 * Class Application
 * @package yii2-cmf
 * @author Gayazov Roman <gromver5@gmail.com>
 */
class Application extends \gromver\cmf\common\Application {
    public $defaultRoute = 'cmf/default/index';
    public $layout = '@gromver/cmf/backend/views/layouts/main';

    /**
     * @inheritdoc
     */
    public function __construct($config = [])
    {
        $config = ArrayHelper::merge([
            'components' => [
                'request' => [
                    'class' => 'gromver\cmf\common\components\Request',
                    'csrfParam' => '_csrfBackend',
                ],
                'urlManager' => [
                    'class' => 'gromver\cmf\common\components\UrlManager',
                    'enablePrettyUrl' => true,
                    'showScriptName' => false,
                ],
                'user' => [
                    'class' => 'gromver\cmf\common\components\User',
                    'idParam' => '__idBackend',
                    'authTimeoutParam' => '__expireBackend',
                    'absoluteAuthTimeoutParam' => '__absoluteExpireBackend',
                    'returnUrlParam' => '__returnUrlBackend',
                    'identityCookie' => ['name' => '_identityBackend', 'httpOnly' => true]
                ],
                'errorHandler' => [
                    'class' => 'yii\web\ErrorHandler',
                    'errorAction' => 'cmf/default/error'
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
                'assetManager' => [
                    'bundles' => [
                        'mihaildev\ckeditor\Assets' => [
                            'sourcePath' => '@gromver/cmf/backend/assets/ckeditor',
                        ],
                    ],
                ]
            ],
            'modules' => [
                'cmf' => [
                    'class' => 'gromver\cmf\backend\modules\main\Module',
                    'modules' => [
                        'user'      => ['class' => 'gromver\cmf\backend\modules\user\Module'],
                        'auth'      => ['class' => 'gromver\cmf\backend\modules\auth\Module'],
                        'menu'      => ['class' => 'gromver\cmf\backend\modules\menu\Module'],
                        'news'      => ['class' => 'gromver\cmf\backend\modules\news\Module'],
                        'page'      => ['class' => 'gromver\cmf\backend\modules\page\Module'],
                        'tag'       => ['class' => 'gromver\cmf\backend\modules\tag\Module'],
                        'version'   => ['class' => 'gromver\cmf\backend\modules\version\Module'],
                        'widget'    => ['class' => 'gromver\cmf\backend\modules\widget\Module'],
                        'media'     => ['class' => 'gromver\cmf\backend\modules\media\Module'],
                        //'search'    => ['class' => 'gromver\cmf\backend\modules\elasticsearch\Module'],
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
        $this->bootstrap = array_merge($this->bootstrap, ['cmf']);

        parent::init();
    }
}