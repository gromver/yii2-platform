<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model gromver\cmf\common\models\WidgetConfig */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('gromver.cmf', 'Widget Configs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="widget-config-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('gromver.cmf', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('gromver.cmf', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('gromver.cmf', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'widget_id',
            'widget_class',
            'language',
            'context',
            'url:url',
            'params:ntext',
            'valid',
            'created_at',
            'updated_at',
            'created_by',
            'updated_by',
            'lock',
        ],
    ]) ?>

</div>
