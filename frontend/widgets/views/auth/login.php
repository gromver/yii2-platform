<?php
/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var menst\cms\common\models\User $model
 */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;
?>

<?php $form = ActiveForm::begin([
    'id' => 'login-form',
    'options' => ['class' => 'form-horizontal'],
    'fieldConfig' => [
        'template' => "{input}",
        'labelOptions' => ['class' => 'col-lg-1 control-label'],
    ],
]); ?>
<?//= $form->errorSummary($model) ?>

<?= $form->field($model, 'username', ['options' => ['class' => 'form-group input-group input-group-lg'], 'template' => '<span class="input-group-addon"><i class=" glyphicon glyphicon-user"></i></span>{input}'])->textInput(['placeholder' => $model->getAttributeLabel('username')]) ?>

<?= $form->field($model, 'password', ['options' => ['class' => 'form-group input-group input-group-lg'], 'template' => '<span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>{input}'])->passwordInput(['placeholder' => $model->getAttributeLabel('password')]) ?>

<?php if ($model->scenario == 'withCaptcha') {
    echo $form->field($model, 'verifyCode', ['options' => ['class' => 'form-group input-group input-group-lg captcha-group'], 'template' => '<span class="input-group-addon"><i class="glyphicon glyphicon-wrench"></i></span>{input}'])->widget(Captcha::className(), ['captchaAction' => 'default/captcha', 'options' => ['placeholder' => $model->getAttributeLabel('verifyCode'), 'class' => 'form-control']/*, 'template' => '{input}{image}'*/]);
} ?>

<?= $form->field($model, 'rememberMe')->checkbox() ?>

<div class="form-group">
    <div class="text-center">
        <?= Html::submitButton(\Yii::t('auth.user', 'Login'), ['class' => 'btn btn-primary btn-lg btn-block']) ?>
    </div>
</div>

<?php ActiveForm::end(); ?>

<style>
    .captcha-group {
        position: relative;
    }
    .captcha-group > img {
        position: absolute;
        top: 1px;
        right: 5px;
        z-index: 10;
    }
    .captcha-group .form-control {
        padding-right: 90px!important;
    }
</style>
