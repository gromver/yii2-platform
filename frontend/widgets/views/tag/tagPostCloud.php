<?php
/**
 * @var $this yii\web\View
 * @var $tags Tag[]
 * @var $fontBase integer
 * @var $fontSpace integer
 * @var $maxWeight integer
 * @var $route string
 * @var $categoryId string
 */

use yii\helpers\Html;
use menst\cms\common\models\Tag;

echo Html::beginTag('div', ['class'=>'tag-cloud']);

foreach ($tags as $tag) {
    echo Html::a(Html::encode($tag['title']), [$route, 'tag_alias' => $tag['alias']], ['style' => 'font-size: ' . ($fontBase + $fontSpace * $maxWeight / $tag['weight']).'px', 'class'=>'tag']);
}

echo Html::endTag('div');