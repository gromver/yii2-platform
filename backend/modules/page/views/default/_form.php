<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model gromver\cmf\common\models\Page */
/* @var $sourceModel gromver\cmf\common\models\Page */
/* @var $form yii\bootstrap\ActiveForm */
?>

<div class="page-form">

    <?php $form = ActiveForm::begin([
        'layout' => 'horizontal'
    ]); ?>

    <?= $form->field($model, 'language')->dropDownList(Yii::$app->getLanguagesList(), ['prompt' => Yii::t('menst.cms', 'Select...')]) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => 1024, 'placeholder' => isset($sourceModel) ? $sourceModel->title : null]) ?>

    <?= $form->field($model, 'alias')->textInput(['maxlength' => 255, 'placeholder' => Yii::t('menst.cms', 'Auto-generate')]) ?>

    <?= $form->field($model, 'status')->dropDownList($model->statusLabels()) ?>

    <?= $form->field($model, 'preview_text')->textarea(['rows' => 6]) ?>

    <div class="form-group container">
        <?= Html::activeLabel($model, 'detail_text') ?>
        <div>
            <?= \mihaildev\ckeditor\CKEditor::widget([
                'model' => $model,
                'attribute' => 'detail_text',
                'editorOptions' => \mihaildev\elfinder\ElFinder::ckeditorOptions('cmf/media/manager', [
                    'extraPlugins' => 'codesnippet'
                ])
            ]) ?>
        </div>
    </div>

    <?= $form->field($model, 'versionNote')->textInput() ?>

    <?= $form->field($model, 'metakey')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'metadesc')->textarea(['maxlength' => 2048]) ?>

    <?= $form->field($model, 'tags')->widget(\dosamigos\selectize\Selectize::className(), [
        'options'=>[
            'multiple'=>true
        ],
        'items' => \yii\helpers\ArrayHelper::map($model->tags, 'id', 'title', 'group'),
        'clientOptions' => [
            'maxItems' => 'NaN'
        ],
        'url' => ['/cmf/tag/default/tag-list']
    ]) ?>

    <?/*= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <?= $form->field($model, 'created_by')->textInput() ?>

    <?= $form->field($model, 'updated_by')->textInput() ?>

    <?= $form->field($model, 'hits')->textInput(['maxlength' => 20])*/ ?>

    <?= Html::activeHiddenInput($model, 'lock') ?>

    <div>
        <?= Html::submitButton($model->isNewRecord ? Yii::t('menst.cms', 'Create') : Yii::t('menst.cms', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
