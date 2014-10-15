<?php
/**
 * @link https://github.com/menst/yii2-cms.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/menst/yii2-cms/blob/master/LICENSE
 * @package yii2-cms
 * @version 1.0.0
 */

namespace menst\cms\frontend\widgets;


use menst\cms\common\widgets\Widget;

/**
 * Class Carousel
 * @package yii2-cms
 * @author Gayazov Roman <m.e.n.s.t@yandex.ru>
 */
class Carousel extends Widget {
    /**
     * @type multiple
     * @fieldType object
     * @object \menst\cms\frontend\widgets\CarouselItem
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