<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model menst\cms\common\models\WidgetConfig */

$this->title = Yii::t('menst.cms', 'Update Widget Config: ', [
    'id' => $model->id
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('menst.cms', 'Widget Configs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('menst.cms', 'Update');
?>
<div class="widget-config-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
