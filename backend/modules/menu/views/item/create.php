<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model gromver\cmf\common\models\Menu */
/* @var $linkParamsModel gromver\cmf\common\models\MenuLinkParams */

$this->title = Yii::t('gromver.cmf', 'Add Menu Item');
$this->params['breadcrumbs'][] = ['label' => Yii::t('gromver.cmf', 'Menu Types'), 'url' => ['type/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('gromver.cmf', 'Menu Items'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
            'model' => $model,
            'linkParamsModel' => $linkParamsModel,
        ]) ?>

</div>
