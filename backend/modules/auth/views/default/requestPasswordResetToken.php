<?php

use yii\helpers\Html;
//use yii\widgets\ActiveForm;
use yii\bootstrap\ActiveForm;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var menst\cms\backend\modules\auth\models\User $model
 */

$this->title = \Yii::t('auth.user', 'Reset Password');
$this->params['breadcrumbs'][] = $this->title;?>

<div classs="site-login center-block col-lg-3 col-md-4 col-sm-6" style="float:none;">
	<div class="form-signin-heading">
		<h1><?= Html::encode($this->title) ?></h1>
    </div>

<?php $form = ActiveForm::begin([
    'id' => 'login-form',
    'layout' => 'horizontal',
    //'options' => ['class' => 'form-horizontal'],
    /*'fieldConfig' => [
        //'template' => "{input}",
        'labelOptions' => ['class' => 'col-lg-1 control-label'],
    ],*/
]); ?>

<?//= $form->field($model, 'email', ['options' => ['class' => 'form-group input-group input-group-lg'], 'template' => '<span class="input-group-addon"><i class=" glyphicon glyphicon-envelope"></i></span>{input}{error}'])->textInput(['placeholder' => $model->getAttributeLabel('email')]) ?>
<?= $form->field($model, 'email')->textInput(['placeholder' => $model->getAttributeLabel('email')]) ?>

<div class="col-sm-2 col-sm-offset-7">
    <div classs="text-center">
        <?= Html::submitButton(\Yii::t('auth.user', 'Submit'), ['class' => 'btn btn-primary btn-lg btn-block']) ?>
    </div>
</div>

<?php ActiveForm::end(); ?>
</div>
