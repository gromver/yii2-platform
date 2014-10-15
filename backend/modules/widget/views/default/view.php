<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model menst\cms\common\models\WidgetConfig */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('menst.cms', 'Widget Configs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="widget-config-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('menst.cms', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('menst.cms', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('menst.cms', 'Are you sure you want to delete this item?'),
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
