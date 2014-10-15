<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model menst\cms\common\models\MenuItem */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('menst.cms', 'Menu Types'), 'url' => ['type/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('menst.cms', 'Menu Items'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('<i class="glyphicon glyphicon-plus"></i> ' . Yii::t('menst.cms', 'Add'), ['create', 'menu_type_id' => $model->menu_type_id], ['class' => 'btn btn-success']) ?>
        <?= Html::a('<i class="glyphicon glyphicon-pencil"></i> ' . Yii::t('menst.cms', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-default']) ?>
        <?= Html::a('<i class="glyphicon glyphicon-trash"></i> ' . Yii::t('menst.cms', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger pull-right',
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
            'menu_type_id',
            'parent_id',
            'status',
            'language',
            'title',
            'alias',
            'path',
            'note',
            'link',
            'link_type',
            'link_params:ntext',
            'layout_path',
            'access_rule',
            'metakey',
            'metadesc',
            'robots',
            'secure',
            'created_at:datetime',
            'updated_at:datetime',
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
