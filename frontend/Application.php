<?php
/**
 * @link https://github.com/gromver/yii2-cmf.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/gromver/yii2-grom/blob/master/LICENSE
 * @package yii2-cmf
 * @version 1.0.0
 */

namespace gromver\platform\frontend;

use yii\helpers\ArrayHelper;

/**
 * Class Application
 * @package yii2-cmf
 * @author Gayazov Roman <gromver5@gmail.com>
 *
 * @property $cms \gromver\platform\frontend\modules\cmf\Module
 */
class Application extends \gromver\platform\common\Application {
    public $layout = '@gromver/platform/frontend/views/layouts/main';

    /**
     * @inheritdoc
     */
    public function __construct($config = [])
    {
        $config = ArrayHelper::merge([
            'components' => [
                'request' => ['class' => 'gromver\platform\common\components\Request'],
                'urlManager' => [
                    'class' => 'gromver\platform\common\components\UrlManager',
                    'enablePrettyUrl' => true,
                    'showScriptName' => false,
                ],
                'urlManagerBackend' => [
                    'class' => 'gromver\platform\common\components\UrlManager',
                    'enablePrettyUrl' => true,
                    'showScriptName' => false,
                    'baseUrl' => '/admin',
                ],
                'user' => ['class' => 'gromver\platform\common\components\User'],
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
                    'class' => 'gromver\platform\frontend\modules\main\Module',
                    'modules' => [
                        'news'      => ['class' => 'gromver\platform\frontend\modules\news\Module'],
                        'page'      => ['class' => 'gromver\platform\frontend\modules\page\Module'],
                        'auth'      => ['class' => 'gromver\platform\frontend\modules\auth\Module'],
                        'widget'    => ['class' => 'gromver\platform\frontend\modules\widget\Module'],
                        'tag'       => ['class' => 'gromver\platform\frontend\modules\tag\Module'],
                        'user'      => ['class' => 'gromver\platform\frontend\modules\user\Module'],
                        'media'     => ['class' => 'gromver\platform\frontend\modules\media\Module'],
                        //'search'    => ['class' => 'gromver\platform\frontend\modules\elasticsearch\Module'],
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