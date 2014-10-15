<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model menst\cms\common\models\User */
/* @var $form yii\bootstrap\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin([
        'layout' => 'horizontal'
    ]); ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => 64, 'disabled' => ($model->scenario !== 'create' ? true : false)]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => 128, 'disabled' => ($model->scenario !== 'create' ? true : false)]) ?>

    <?= $form->field($model, 'status')->dropDownList(\menst\cms\common\models\User::statusLabels()) ?>

    <?= $form->field($model, 'password')->passwordInput(['autocomplete'=>'off']) ?>

    <?= $form->field($model, 'password_confirm')->passwordInput(['autocomplete'=>'off']) ?>

    <?php if ($model->getIsSuperAdmin()) {
        echo $form->field($model, 'roles')->textInput(['value' => Yii::t('menst.cms', 'Super Administrator'), 'disabled' => true]);
    } else {
        echo $form->field($model, 'roles')->listBox(\yii\helpers\ArrayHelper::map(Yii::$app->authManager->getRoles(), 'name', 'name'), [
            'multiple' => 'multiple'
        ]);
    } ?>

    <div>
        <?= Html::submitButton($model->isNewRecord ? Yii::t('menst.cms', 'Create') : Yii::t('menst.cms', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?php if (!$model->isNewRecord) {
            echo Html::a(Yii::t('menst.cms', 'Params'), ['params', 'id' => $model->id], ['class' => 'btn btn-default']);
        } ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
