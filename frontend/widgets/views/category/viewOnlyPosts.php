<?php
/**
 * @var $this yii\web\View
 * @var $model string|\menst\cms\common\models\Category
 */ ?>

<h2><?=\yii\helpers\Html::encode($model->title)?></h2>

<?= \menst\cms\frontend\widgets\PostList::widget([
    'id' => 'cat-posts',
    'category' => $model,
    'layout' => 'post/listDefault'
]) ?>