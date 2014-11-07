<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model gromver\cmf\common\models\Menu */
/* @var $form yii\bootstrap\ActiveForm */
?>

<div class="menu-form">

    <?php $form = ActiveForm::begin([
            'layout' => 'horizontal',
        ]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => 1024]) ?>

    <?= $form->field($model, 'alias')->textInput(['maxlength' => 255, 'placeholder' => Yii::t('menst.cms', 'Auto-generate')]) ?>

    <?= $form->field($model, 'note')->textInput(['maxlength' => 255]) ?>

    <?= Html::activeHiddenInput($model, 'lock') ?>

    <div>
        <?= Html::submitButton($model->isNewRecord ? Yii::t('menst.cms', 'Create') : Yii::t('menst.cms', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
