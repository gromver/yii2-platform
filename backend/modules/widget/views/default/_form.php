<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model menst\cms\common\models\WidgetConfig */
/* @var $form yii\bootstrap\ActiveForm */
?>

<div class="widget-config-form">

    <?php $form = ActiveForm::begin([
        'layout' => 'horizontal'
    ]); ?>

    <?= $form->field($model, 'widget_id')->textInput(['maxlength' => 50]) ?>

    <?= $form->field($model, 'widget_class')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'context')->textInput(['maxlength' => 1024]) ?>

    <?= $form->field($model, 'url')->textInput(['maxlength' => 1024]) ?>

    <?= $form->field($model, 'params')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'valid')->dropDownList(['No', 'Yes']) ?>

    <?/*= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <?= $form->field($model, 'created_by')->textInput() ?>

    <?= $form->field($model, 'updated_by')->textInput()*/ ?>

    <?= Html::activeHiddenInput($model, 'lock') ?>

    <div>
        <?= Html::submitButton($model->isNewRecord ? Yii::t('menst.cms', 'Create') : Yii::t('menst.cms', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
