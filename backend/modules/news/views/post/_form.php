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

        <?= $form->errorSummary($model) ?>

        <?= $form->field($model, 'title')->textInput(['maxlength' => 1024, 'placeholder' => isset($sourceModel) ? $sourceModel->title : null]) ?>

        <?= $form->field($model, 'alias')->textInput(['maxlength' => 255, 'placeholder' => Yii::t('gromver.cmf', 'Auto-generate')]) ?>

        <?= $form->field($model, 'versionNote')->textInput() ?>

        <ul class="nav nav-tabs">
            <li class="active"><a href="#main-options" data-toggle="tab"><?= Yii::t('gromver.cmf', 'Main') ?></a></li>
            <li><a href="#advanced-options" data-toggle="tab"><?= Yii::t('gromver.cmf', 'Advanced') ?></a></li>
            <li><a href="#meta-options" data-toggle="tab"><?= Yii::t('gromver.cmf', 'Metadata') ?></a></li>
        </ul>
        <br/>
        <div class="tab-content">
            <div id="main-options" class="tab-pane active">
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
                <?= $form->field($model, 'language')->dropDownList(Yii::$app->getLanguagesList(), ['prompt' => Yii::t('gromver.cmf', 'Select...'), 'id' => 'language']) ?>

                <?= $form->field($model, 'category_id')->widget(\kartik\widgets\DepDrop::className(), [
                    'pluginOptions' => [
                        //'initialize' => true,
                        'depends' => ['language'],
                        'placeholder' => Yii::t('gromver.cmf', 'Select...'),
                        'url' => \yii\helpers\Url::to(['categories', 'selected' => $model->category_id]),
                    ]
                ]) ?>

                <?= $form->field($model, 'status')->dropDownList(['' => Yii::t('gromver.cmf', 'Select...')] + $model->statusLabels()) ?>

                <?= $form->field($model, 'published_at')->widget(\kartik\widgets\DateTimePicker::className(), [
                    'options' => ['value' => date('d.m.Y H:i', is_int($model->published_at) ? $model->published_at : time())],
                    'pluginOptions' => [
                        'format' =>  'dd.mm.yyyy hh:ii',
                        'autoclose' => true,
                    ]
                ]) ?>
            </div>

            <div id="advanced-options" class="tab-pane">
                <?= $form->field($model, 'preview_text')->textarea(['rows' => 6]) ?>

                <?= $form->field($model, 'ordering')->textInput(['maxlength' => 11]) ?>

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
            </div>
            <div id="meta-options" class="tab-pane">
                <?= $form->field($model, 'metakey')->textInput(['maxlength' => 255]) ?>

                <?= $form->field($model, 'metadesc')->textarea(['maxlength' => 2048]) ?>
            </div>
        </div>

        <?= Html::activeHiddenInput($model, 'lock') ?>

        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('gromver.cmf', 'Create') : Yii::t('gromver.cmf', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
<?php $this->registerJs('$("#language").change()', \yii\web\View::POS_READY);