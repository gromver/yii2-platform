<?php
/**
 * @var $this yii\web\View
 * @var $model string|\gromver\platform\common\models\Category
 */
?>

<h1 class="page-title title-category"><?=\yii\helpers\Html::encode($model->title)?></h1>

<div class="well">
    <?=$model->detail_text ?>
</div>

<h2><?= Yii::t('gromver.platform', 'Категории') ?></h2>

<?= \gromver\platform\frontend\widgets\CategoryList::widget([
    'id' => 'cat-cats',
    'category' => $model,
]) ?>

<h2><?= Yii::t('gromver.platform', 'Статьи') ?></h2>

<?= \gromver\platform\frontend\widgets\PostList::widget([
    'id' => 'cat-posts',
    'category' => $model,
]) ?>