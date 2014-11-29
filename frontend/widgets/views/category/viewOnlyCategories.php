<?php
/**
 * @var $this yii\web\View
 * @var $model string|\gromver\platform\common\models\Category
 */ ?>

<h2><?=\yii\helpers\Html::encode($model->title)?></h2>

<?= \gromver\platform\frontend\widgets\CategoryList::widget([
    'id' => 'cat-cats',
    'category' => $model,
]) ?>