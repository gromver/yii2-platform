<?php
/**
 * @link https://github.com/gromver/yii2-cmf.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/gromver/yii2-cmf/blob/master/LICENSE
 * @package yii2-cmf
 * @version 1.0.0
 */

namespace gromver\cmf\common\widgets;


use yii\helpers\Html;

/**
 * Class SearchForm
 * @package yii2-cmf
 * @author Gayazov Roman <gromver5@gmail.com>
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

        echo Html::submitButton(\Yii::t('gromver.cmf', 'Найти'));

        echo Html::endForm();
    }
} 