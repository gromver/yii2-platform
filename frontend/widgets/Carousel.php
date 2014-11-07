<?php
/**
 * @link https://github.com/gromver/yii2-cmf.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/gromver/yii2-cmf/blob/master/LICENSE
 * @package yii2-cmf
 * @version 1.0.0
 */

namespace gromver\cmf\frontend\widgets;


use gromver\cmf\common\widgets\Widget;

/**
 * Class Carousel
 * @package yii2-cmf
 * @author Gayazov Roman <gromver5@gmail.com>
 */
class Carousel extends Widget {
    /**
     * @type multiple
     * @fieldType object
     * @object \gromver\cmf\frontend\widgets\CarouselItem
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
    /**
     * @type media
     */
    //public $test;
}