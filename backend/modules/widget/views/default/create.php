<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model gromver\cmf\common\models\WidgetConfig */

$this->title = Yii::t('gromver.cmf', 'Create Settings for Widget: {id}', [
    'id' => $model->widget_id
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('gromver.cmf', 'Widget\'s Settings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="widget-config-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
