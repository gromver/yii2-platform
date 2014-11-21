<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model gromver\cmf\common\models\WidgetConfig */

$this->title = $model->widget_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('gromver.cmf', 'Widget\'s Settings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="widget-config-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('<i class="glyphicon glyphicon-pencil"></i> ' . Yii::t('gromver.cmf', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-default']) ?>
        <?= Html::a('<i class="glyphicon glyphicon-trash"></i> ' . Yii::t('gromver.cmf', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger pull-right',
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
            'created_at:datetime',
            'updated_at:datetime',
            'created_by',
            'updated_by',
            'lock',
        ],
    ]) ?>

</div>
