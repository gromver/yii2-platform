<?php
/**
 * @link https://github.com/gromver/yii2-cmf.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/gromver/yii2-grom/blob/master/LICENSE
 * @package yii2-cmf
 * @version 1.0.0
 */

namespace gromver\platform\frontend\widgets;


use gromver\platform\common\widgets\Widget;

/**
 * Class Carousel
 * @package yii2-cmf
 * @author Gayazov Roman <gromver5@gmail.com>
 */
class Carousel extends Widget {
    /**
     * @type multiple
     * @fieldType object
     * @object \gromver\platform\frontend\widgets\CarouselItem
     */
    public $items;

    protected function launch()
    {
        echo \yii\bootstrap\Carousel::widget([
            'items' => $this->items
        ]);
    }
}

class CarouselItem {
    /**
     * @type editor
     */
    public $content;
    public $caption;
}