<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model menst\cms\common\models\Post */

$this->title = Yii::t('menst.cms', 'Update Post: {title}', [
    'title' => $model->title
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('menst.cms', 'Posts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->category->title, 'url' => ['index', 'PostSearch' => ['category_id' => $model->category_id]]];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('menst.cms', 'Update');
?>

<div class="post-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <p class="btn-toolbar">
        <?= \menst\widgets\ModalIFrame::widget([
            'modalOptions' => [
                'header' => Yii::t('menst.cms', 'Item Versions Manager - "{title}" (ID:{id})', ['title' => $model->title, 'id' => $model->id]),
                'size' => \yii\bootstrap\Modal::SIZE_LARGE,
            ],
            'buttonContent' => Html::a('<i class="glyphicon glyphicon-hdd"></i> ' . Yii::t('menst.cms', 'Versions'),
                    ['/cms/version/default/item', 'item_id' => $model->id, 'item_class' => $model->className()], [
                        'class'=>'btn btn-default btn-sm',
                    ]),
            ]) ?>
    </p>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
