<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model gromver\cmf\common\models\Version */

$this->title = Yii::t('menst.cms', 'Update Version: {id}', [
    'id' => $model->id
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('menst.cms', 'Versions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('menst.cms', 'Update');
?>
<div class="history-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
