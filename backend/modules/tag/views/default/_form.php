<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/**
 * @var yii\web\View $this
 * @var gromver\cmf\common\models\Tag $model
 * @var gromver\cmf\common\models\Tag $sourceModel
 * @var yii\bootstrap\ActiveForm $form
 */
?>

<div class="tag-form">

    <?php $form = ActiveForm::begin([
            'layout' => 'horizontal'
        ]); ?>

    <?= $form->errorSummary($model) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => 100, 'placeholder' => isset($sourceModel) ? $sourceModel->title : null]) ?>

    <?= $form->field($model, 'alias')->textInput(['maxlength' => 255, 'placeholder' => Yii::t('gromver.cmf', 'Auto-generate')]) ?>

    <?= $form->field($model, 'language')->dropDownList(Yii::$app->getLanguagesList(), ['prompt' => Yii::t('gromver.cmf', 'Select...')]) ?>

    <?= $form->field($model, 'status')->dropDownList(['' => Yii::t('gromver.cmf', 'Not selected')] + $model->statusLabels()) ?>

    <?= $form->field($model, 'group')->textInput(['maxlength' => 255, 'placeholder' => isset($sourceModel) ? $sourceModel->group : null]) ?>

    <?= $form->field($model, 'metakey')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'metadesc')->textInput(['maxlength' => 2048]) ?>

    <?/*= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <?= $form->field($model, 'created_by')->textInput() ?>

    <?= $form->field($model, 'updated_by')->textInput() */?>

    <?//= $form->field($model, 'hits')->textInput(['maxlength' => 20]) ?>

    <?= Html::activeHiddenInput($model, 'lock') ?>

    <div>
        <?= Html::submitButton($model->isNewRecord ? Yii::t('gromver.cmf', 'Create') : Yii::t('gromver.cmf', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>