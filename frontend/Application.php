<?php
/**
 * @link https://github.com/menst/yii2-cms.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/menst/yii2-cms/blob/master/LICENSE
 * @package yii2-cms
 * @version 1.0.0
 */

namespace menst\cms\frontend;

use yii\helpers\ArrayHelper;

/**
 * Class Application
 * @package yii2-cms
 * @author Gayazov Roman <m.e.n.s.t@yandex.ru>
 *
 * @property $cms \menst\cms\frontend\modules\cms\Module
 */
class Application extends \yii\web\Application {
    public $languages = ['ru', 'en'];

    public $language = 'ru';

    public $sourceLanguage = 'en';
    
    public $layout = '@menst/cms/frontend/views/layouts/main';

    private $_modulesHash;

    /**
     * @inheritdoc
     */
    public function __construct($config = [])
    {
        $config = ArrayHelper::merge([
            'components' => [
                'request' => ['class' => 'menst\cms\common\components\Request'],
                'urlManager' => [
                    'class' => 'menst\cms\common\components\UrlManager',
                    'enablePrettyUrl' => true,
                    'showScriptName' => false,
                ],
                'urlManagerBackend' => [
                    'class' => 'menst\cms\common\components\UrlManager',
                    'enablePrettyUrl' => true,
                    'showScriptName' => false,
                    'baseUrl' => '/admin',
                ],
                'user' => ['class' => 'menst\cms\common\components\User'],
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
            ],
            'modules' => [
                'cms' => [
                    'class' => 'menst\cms\frontend\modules\main\Module',
                    'modules' => [
                        'news'      => ['class' => 'menst\cms\frontend\modules\news\Module'],
                        'page'      => ['class' => 'menst\cms\frontend\modules\page\Module'],
                        'auth'      => ['class' => 'menst\cms\frontend\modules\auth\Module'],
                        'widget'    => ['class' => 'menst\cms\frontend\modules\widget\Module'],
                        'tag'       => ['class' => 'menst\cms\frontend\modules\tag\Module'],
                        'user'      => ['class' => 'menst\cms\frontend\modules\user\Module'],
                        'search'    => ['class' => 'menst\cms\frontend\modules\search\Module'],
                        //'media'     => ['class' => 'backend\modules\media\Module'],
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
    public function init()
    {
        $this->bootstrap = array_merge($this->bootstrap, ['cms']);

        parent::init();
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