<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model gromver\cmf\common\models\MenuType */

$this->title = Yii::t('menst.cms', 'Update Menu Type: {title}', [
    'title' => $model->title
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('menst.cms', 'Menu Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('menst.cms', 'Update');
?>
<div class="menu-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
