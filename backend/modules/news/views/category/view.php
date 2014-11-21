<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model gromver\cmf\common\models\Category */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('gromver.cmf', 'Categories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="category-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('<i class="glyphicon glyphicon-plus"></i> ' . Yii::t('gromver.cmf', 'Add'), ['create'], ['class' => 'btn btn-success']) ?>
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
            'translation_id',
            'parent_id',
            'language',
            'title',
            'alias',
            'path',
            'preview_text:ntext',
            'preview_image',
            'detail_text:ntext',
            'detail_image',
            'metakey',
            'metadesc',
            'created_at:datetime',
            'updated_at:datetime',
            'published_at:datetime',
            'status',
            'created_by',
            'updated_by',
            'lft',
            'rgt',
            'level',
            'ordering',
            'hits',
            'lock',
        ],
    ]) ?>

</div>
