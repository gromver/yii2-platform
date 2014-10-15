<?php
/**
 * @var $this yii\web\View
 * @var $model string|\menst\cms\common\models\Category
 */ ?>

<h2><?=\yii\helpers\Html::encode($model->title)?></h2>

<?= \menst\cms\frontend\widgets\CategoryList::widget([
    'id' => 'cat-cats',
    'category' => $model,
]) ?>