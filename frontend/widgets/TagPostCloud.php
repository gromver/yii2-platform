<?php
/**
 * @link https://github.com/gromver/yii2-cms.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/gromver/yii2-cmf/blob/master/LICENSE
 * @package yii2-cms
 * @version 1.0.0
 */

namespace gromver\cmf\frontend\widgets;

use gromver\cmf\common\models\Post;
use gromver\cmf\common\widgets\Widget;

/**
 * Class TagItems
 * @package yii2-cms
 * @author Gayazov Roman <gromver5@gmail.com>
 */
class TagPostCloud extends Widget {
    /**
     * CategoryId
     */
    public $categoryId;
    public $fontBase = 14;
    public $fontSpace = 6;

    protected function launch()
    {
        $tags = Post::find()->category($this->categoryId)->published()->innerJoinWith('tags', false)->select([
            'id' => '{{%cms_tag}}.id',
            'title' => '{{%cms_tag}}.title',
            'alias' => '{{%cms_tag}}.alias',
            'weight' => 'count({{%cms_tag}}.id)'
        ])->groupBy('{{%cms_tag}}.id')->asArray()->all();

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