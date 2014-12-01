<?php
/**
 * @link https://github.com/gromver/yii2-cmf.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/gromver/yii2-grom/blob/master/LICENSE
 * @package yii2-cmf
 * @version 1.0.0
 */

namespace gromver\platform\frontend\widgets;

use gromver\platform\common\models\Post;
use gromver\platform\common\widgets\Widget;

/**
 * Class TagItems
 * @package yii2-cmf
 * @author Gayazov Roman <gromver5@gmail.com>
 */
class TagPostCloud extends Widget {
    /**
     * @label Category
     * @translation gromver.platform
     */
    public $categoryId;
    /**
     * @translation gromver.platform
     */
    public $fontBase = 14;
    /**
     * @translation gromver.platform
     */
    public $fontSpace = 6;

    protected function launch()
    {
        $tags = Post::find()->category($this->categoryId)->published()->innerJoinWith('tags', false)->select([
            'id' => '{{%grom_tag}}.id',
            'title' => '{{%grom_tag}}.title',
            'alias' => '{{%grom_tag}}.alias',
            'weight' => 'count({{%grom_tag}}.id)'
        ])->groupBy('{{%grom_tag}}.id')->asArray()->all();

        $maxWeight = 0;
        array_walk($tags, function ($v) use (&$maxWeight){
            $maxWeight = max($v['weight'], $maxWeight);
        });

        echo $this->render('tag/tagPostCloud', [
            'tags' => $tags,
            'fontBase' => $this->fontBase,
            'fontSpace' => $this->fontSpace,
            'maxWeight' => $maxWeight,
            'categoryId' => $this->categoryId
        ]);
    }
} 