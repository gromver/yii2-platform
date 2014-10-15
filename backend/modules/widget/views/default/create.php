<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model menst\cms\common\models\WidgetConfig */

$this->title = Yii::t('menst.cms', 'Create Widget Config');
$this->params['breadcrumbs'][] = ['label' => Yii::t('menst.cms', 'Widget Configs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="widget-config-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
