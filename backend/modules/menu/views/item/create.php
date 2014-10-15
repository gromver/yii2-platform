<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model menst\cms\common\models\Menu */
/* @var $sourceModel menst\cms\common\models\Menu */
/* @var $linkParamsModel menst\cms\common\models\MenuLinkParams */

$this->title = Yii::t('menst.cms', 'Add Menu Item');
$this->params['breadcrumbs'][] = ['label' => Yii::t('menst.cms', 'Menu Types'), 'url' => ['type/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('menst.cms', 'Menu Items'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
            'model' => $model,
            'sourceModel' => $sourceModel,
            'linkParamsModel' => $linkParamsModel,
        ]) ?>

</div>
