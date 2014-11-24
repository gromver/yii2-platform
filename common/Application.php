<?php
/**
 * @link https://github.com/gromver/yii2-cmf.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/gromver/yii2-cmf/blob/master/LICENSE
 * @package yii2-cmf
 * @version 1.0.0
 */

namespace gromver\cmf\common;

use yii\helpers\ArrayHelper;

/**
 * Class Application
 * @package yii2-cmf
 * @author Gayazov Roman <gromver5@gmail.com>
 */
class Application extends \yii\web\Application {
    public $language = 'en';
    public $languages = ['en', 'ru'];
    public $sourceLanguage = 'en';

    private $_modulesHash;

    /**
     * @inheritdoc
     */
    public function __construct($config = [])
    {
        $this->_modulesHash = md5(json_encode(ArrayHelper::getValue($config, 'modules', [])));

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