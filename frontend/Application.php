<?php
/**
 * @link https://github.com/gromver/yii2-cmf.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/gromver/yii2-cmf/blob/master/LICENSE
 * @package yii2-cmf
 * @version 1.0.0
 */

namespace gromver\cmf\frontend;

use yii\helpers\ArrayHelper;

/**
 * Class Application
 * @package yii2-cmf
 * @author Gayazov Roman <gromver5@gmail.com>
 *
 * @property $cms \gromver\cmf\frontend\modules\cmf\Module
 */
class Application extends \gromver\cmf\common\Application {
    public $layout = '@gromver/cmf/frontend/views/layouts/main';

    /**
     * @inheritdoc
     */
    public function __construct($config = [])
    {
        $config = ArrayHelper::merge([
            'components' => [
                'request' => ['class' => 'gromver\cmf\common\components\Request'],
                'urlManager' => [
                    'class' => 'gromver\cmf\common\components\UrlManager',
                    'enablePrettyUrl' => true,
                    'showScriptName' => false,
                ],
                'urlManagerBackend' => [
                    'class' => 'gromver\cmf\common\components\UrlManager',
                    'enablePrettyUrl' => true,
                    'showScriptName' => false,
                    'baseUrl' => '/admin',
                ],
                'user' => ['class' => 'gromver\cmf\common\components\User'],
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
                'i18n' => [
                    'translations' => [
                        '*' => [
                            'class' => 'yii\i18n\PhpMessageSource'
                        ],
                    ],
                ],
            ],
            'modules' => [
                'cmf' => [
                    'class' => 'gromver\cmf\frontend\modules\main\Module',
                    'modules' => [
                        'news'      => ['class' => 'gromver\cmf\frontend\modules\news\Module'],
                        'page'      => ['class' => 'gromver\cmf\frontend\modules\page\Module'],
                        'auth'      => ['class' => 'gromver\cmf\frontend\modules\auth\Module'],
                        'widget'    => ['class' => 'gromver\cmf\frontend\modules\widget\Module'],
                        'tag'       => ['class' => 'gromver\cmf\frontend\modules\tag\Module'],
                        'user'      => ['class' => 'gromver\cmf\frontend\modules\user\Module'],
                        'media'     => ['class' => 'gromver\cmf\frontend\modules\media\Module'],
                        //'search'    => ['class' => 'gromver\cmf\frontend\modules\elasticsearch\Module'],
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