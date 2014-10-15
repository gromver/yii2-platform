<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model menst\cms\common\models\MenuItem */
/* @var $linkParamsModel menst\cms\common\models\MenuLinkParams */

$this->title = Yii::t('menst.cms', 'Update Menu Item: {title}', [
    'title' => $model->title
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('menst.cms', 'Menu Types'), 'url' => ['type/index']];
$this->params['breadcrumbs'][] = ['label' => $model->menuType->title, 'url' => ['index', 'MenuItemSearch' => ['menu_type_id' => $model->menuType->id]]];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('menst.cms', 'Update');
?>
<div class="menu-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
            'model' => $model,
            'linkParamsModel' => $linkParamsModel,
        ]) ?>

</div>
