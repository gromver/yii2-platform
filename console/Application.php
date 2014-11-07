<?php
/**
 * @link https://github.com/gromver/yii2-cmf.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/gromver/yii2-cmf/blob/master/LICENSE
 * @package yii2-cmf
 * @version 1.0.0
 */

namespace gromver\cmf\console;

use yii\helpers\ArrayHelper;

/**
 * Class Application
 * @package yii2-cmf
 * @author Gayazov Roman <gromver5@gmail.com>
 */
class Application extends \yii\console\Application {
    public $language = 'en';
    public $languages = ['en', 'ru'];
    public $sourceLanguage = 'en';
    public $elasticsearchIndex = 'cmf';

    private $_modulesHash;

    /**
     * @inheritdoc
     */
    public function __construct($config = [])
    {
        $config = ArrayHelper::merge([
            'components' => [
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
            ]
        ], $config);

        $this->_modulesHash = md5(json_encode($config['modules']));

        parent::__construct($config);
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