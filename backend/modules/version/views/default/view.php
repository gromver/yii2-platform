<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model gromver\cmf\common\models\Version */

$this->title = Yii::t('gromver.cmf', 'ID: {id}', [
    'id' => $model->id
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('gromver.cmf', 'Version Manager'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="history-view">

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
            'item_id',
            'item_class',
            'version_note',
            'version_hash',
            'version_data:ntext',
            'character_count',
            'keep_forever',
            'created_at:datetime',
            'created_by',
        ],
    ]) ?>

</div>
