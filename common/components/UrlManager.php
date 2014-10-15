<?php
/**
 * @link https://github.com/menst/yii2-cms.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/menst/yii2-cms/blob/master/LICENSE
 * @package yii2-cms
 * @version 1.0.0
 */

namespace menst\cms\common\components;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class UrlManager
 * @package yii2-cms
 * @author Gayazov Roman <m.e.n.s.t@yandex.ru>
 */
class UrlManager extends \yii\web\UrlManager
{
    const LANGUAGE_PARAM = 'lang';

    private $_language;

    /**
     * @param array|string $params
     * @param null|string $language языковой контекст обработки урла, позволяет определить для какого сайта(рускоязычного или допустим англоязычного)
     * нужно сделать урл, используется в MenuManager для определения соответсвующей карты меню
     * @return string
     */
    public function createUrl($params, $language = null)
    {
        $this->_language = isset($language) ? $language : ArrayHelper::getValue($params, static::LANGUAGE_PARAM, Yii::$app->language);

        if(is_array($params)) {
            unset($params[static::LANGUAGE_PARAM]);
        }

        return parent::createUrl($params);
    }

    /**
     * @inheritdoc
     */
    public function getBaseUrl()
    {
        return parent::getBaseUrl().(($this->_language!=Yii::$app->language || $this->_language!=Yii::$app->request->getDefaultLanguage())?'/'.$this->_language:'');
    }

    /**
     * @return string
     */
    public function getLanguageContext()
    {
        return $this->_language;
    }
}