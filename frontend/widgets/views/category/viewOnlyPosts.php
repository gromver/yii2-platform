<?php
/**
 * @var $this yii\web\View
 * @var $model string|\gromver\platform\common\models\Category
 */ ?>

<h1 class="page-title title-category"><?=\yii\helpers\Html::encode($model->title)?></h1>

<?= \gromver\platform\frontend\widgets\PostList::widget([
    'id' => 'cat-posts',
    'category' => $model,
    'layout' => 'post/listDefault'
]) ?>