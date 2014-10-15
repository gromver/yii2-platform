<?php
/**
 * @var $this yii\web\View
 * @var $model string|\menst\cms\common\models\Category
 */
?>

<h2><?=\yii\helpers\Html::encode($model->title)?></h2>

<div class="well">
    <?=$model->detail_text ?>
</div>

<h3><?= Yii::t('menst.cms', 'Категории') ?></h3>

<?= \menst\cms\frontend\widgets\CategoryList::widget([
'id' => 'cat-cats',
'category' => $model,
]) ?>

<h3><?= Yii::t('menst.cms', 'Статьи') ?></h3>

<?= \menst\cms\frontend\widgets\PostList::widget([
'id' => 'cat-posts',
'category' => $model,
]) ?>