<?php
/**
 * @var $this yii\web\View
 * @var $tags Tag[]
 * @var $fontBase integer
 * @var $fontSpace integer
 * @var $maxWeight integer
 * @var $categoryId string
 */

use yii\helpers\Html;
use gromver\cmf\common\models\Tag;

echo Html::beginTag('div', ['class'=>'tag-cloud']);

foreach ($tags as $tag) {
    echo Html::a(Html::encode($tag['title']), ['/cmf/tag/default/posts', 'tag_id' => $tag['id'], 'tag_alias' => $tag['alias'], 'category_id' => $categoryId], ['style' => 'font-size: ' . ($fontBase + $fontSpace / $maxWeight * $tag['weight']).'px', 'class'=>'tag']);
}

echo Html::endTag('div');