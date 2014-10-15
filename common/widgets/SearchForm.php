<?php
/**
 * @link https://github.com/menst/yii2-cms.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/menst/yii2-cms/blob/master/LICENSE
 * @package yii2-cms
 * @version 1.0.0
 */

namespace menst\cms\common\widgets;


use yii\helpers\Html;

/**
 * Class SearchForm
 * @package yii2-cms
 * @author Gayazov Roman <m.e.n.s.t@yandex.ru>
 */
class SearchForm extends Widget {
    /**
     * @ignore
     */
    public $url;
    /**
     * @ignore
     */
    public $queryParam = 'q';
    public $query;

    protected function launch()
    {
        echo Html::beginForm($this->url, 'get');

        echo Html::input('text', $this->queryParam, $this->query);

        echo Html::submitButton(\Yii::t('menst.cms', 'Найти'));

        echo Html::endForm();
    }
} 