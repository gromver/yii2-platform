<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model menst\cms\common\models\User */

$this->title = Yii::t('menst.cms', 'Create User');
$this->params['breadcrumbs'][] = ['label' => Yii::t('menst.cms', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
