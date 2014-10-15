<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model menst\cms\common\models\Tag */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('menst.cms', 'Tags'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tag-view">

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
            'language',
            'title',
            'alias',
            'status',
            'group',
            'metakey',
            'metadesc',
            'created_at:datetime',
            'updated_at:datetime',
            'created_by',
            'updated_by',
            'hits',
            'lock',
        ],
    ]) ?>

</div>
