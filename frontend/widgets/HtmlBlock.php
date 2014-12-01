<?php
/**
 * Created by PhpStorm.
 * User: roman
 * Date: 19.11.14
 * Time: 16:24
 */

namespace gromver\platform\frontend\widgets;


use gromver\platform\common\widgets\Widget;
use yii\helpers\Html;

class HtmlBlock extends Widget {
    /**
     * @translation gromver.platform
     */
    public $title;
    /**
     * @type editor
     * @label Content
     * @translation gromver.platform
     * @var string
     */
    public $html;

    protected function launch()
    {
        if (!empty($this->title)) {
            echo Html::tag('h3', $this->title);
        }

        echo $this->html;
    }
} 