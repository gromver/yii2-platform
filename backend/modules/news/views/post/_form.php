<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model gromver\cmf\common\models\Post */
/* @var $sourceModel gromver\cmf\common\models\Post */
/* @var $form yii\bootstrap\ActiveForm */
?>

<div class="post-form">

    <?php $form = ActiveForm::begin([
        'layout' => 'horizontal',
        'options' => ['enctype'=>'multipart/form-data']
    ]); ?>

    <?= $form->field($model, 'category_id')->dropDownList(\yii\helpers\ArrayHelper::map(\gromver\cmf\common\models\Category::find()->noRoots()->orderBy('lft')->all(),'id', function($model){
        return str_repeat(" • ", $model->level-1) . $model->title;
    }), ['prompt'=>'Не указано']) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => 1024, 'placeholder' => isset($sourceModel) ? $sourceModel->title : null]) ?>

    <?= $form->field($model, 'alias')->textInput(['maxlength' => 255, 'placeholder' => Yii::t('gromver.cmf', 'Auto-generate')]) ?>

    <?= $form->field($model, 'status')->dropDownList(['' => Yii::t('gromver.cmf', 'Select...')] + $model->statusLabels()) ?>

    <?= $form->field($model, 'published_at')->widget(\kartik\widgets\DateTimePicker::className(), [
        'options' => ['value' => date('d.m.Y H:i', is_int($model->published_at) ? $model->published_at : time())],
        'pluginOptions' => [
            'format' =>  'dd.mm.yyyy hh:ii',
            'autoclose' => true,
        ]
    ]) ?>

    <?= $form->field($model, 'ordering')->textInput(['maxlength' => 11]) ?>

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
        'options' => [
            'multiple' => true
        ],
        'items' => \yii\helpers\ArrayHelper::map($model->tags, 'id', 'title', 'group'),
        'clientOptions' => [
            'maxItems' => 'NaN'
        ],
        'url' => ['/cmf/tag/default/tag-list']
    ]) ?>

    <?= $form->field($model, 'detail_image')->widget(\gromver\cmf\backend\widgets\FileInput::classname(), [
        'options' => ['accept' => 'image/*'],
        'pluginOptions' => ['showUpload' => false]
    ]) ?>

    <?= $form->field($model, 'preview_image')->widget(\gromver\cmf\backend\widgets\FileInput::classname(), [
        'options' => ['accept' => 'image/*'],
        'pluginOptions' => ['showUpload' => false]
    ]) ?>

    <?= Html::activeHiddenInput($model, 'lock') ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('gromver.cmf', 'Create') : Yii::t('gromver.cmf', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
