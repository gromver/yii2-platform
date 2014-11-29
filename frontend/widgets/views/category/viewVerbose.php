<?php
/**
 * @var $this yii\web\View
 * @var $model string|\gromver\platform\common\models\Category
 */
?>

<h2><?=\yii\helpers\Html::encode($model->title)?></h2>

<div class="well">
    <?=$model->detail_text ?>
</div>

<h3><?= Yii::t('gromver.platform', 'Категории') ?></h3>

<?= \gromver\platform\frontend\widgets\CategoryList::widget([
'id' => 'cat-cats',
'category' => $model,
]) ?>

<h3><?= Yii::t('gromver.platform', 'Статьи') ?></h3>

<?= \gromver\platform\frontend\widgets\PostList::widget([
'id' => 'cat-posts',
'category' => $model,
]) ?>