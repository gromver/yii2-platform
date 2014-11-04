<?php

use yii\helpers\Html;

/**
 * @var menst\models\ObjectModel $model
 * @var yii\web\View $this
 */
$this->title = Yii::t('menst.cms', 'Site Configuration');
$this->params['breadcrumbs'][] = $this->title; ?>

<div class="page-heading">
    <h2><?= \yii\helpers\Html::encode($this->title) ?></h2>
</div>

<div class="config-form">

    <?php $form = \yii\bootstrap\ActiveForm::begin([
        'layout' => 'horizontal',
    ]); ?>

    <?= \menst\models\widgets\Fields::widget(['model' => $model]) ?>

    <div>
        <?= Html::submitButton('<i class="glyphicon glyphicon-save"></i> ' . Yii::t('menst.cms', 'Save'), ['class' => 'btn btn-success']) ?>
        <?//= Html::submitButton('<i class="glyphicon glyphicon-refresh"></i> ' . Yii::t('menst.cms', 'Refresh'), ['class' => 'btn btn-default', 'name' => 'task', 'value' => 'refresh']) ?>
    </div>

    <?php \yii\bootstrap\ActiveForm::end(); ?>

</div>

<?
$this->registerJs('$("#'.$form->getId().'").on("refresh.form", function(){
    $(this).find("button[value=\'refresh\']").click()
})');
?>