<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/**
 * @var yii\web\View $this
 * @var \menst\models\ObjectModel $model
 * @var yii\bootstrap\ActiveForm $form
 */
?>

<div class="profile-form">

    <?php $form = ActiveForm::begin([
        'layout' => 'horizontal',
    ]); ?>

    <?= \menst\models\widgets\Fields::widget(['model' => $model]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('menst.cms', 'Update'), ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
