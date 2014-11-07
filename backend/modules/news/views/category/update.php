<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model gromver\cmf\common\models\Category */

$this->title = Yii::t('menst.cms', 'Update Category: {title}', [
    'title' => $model->title
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('menst.cms', 'Categories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('menst.cms', 'Update');
?>

<div class="category-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <p class="btn-toolbar">
        <?= \gromver\widgets\ModalIFrame::widget([
            'modalOptions' => [
                'header' => Yii::t('menst.cms', 'Item Versions Manager - "{title}" (ID:{id})', ['title' => $model->title, 'id' => $model->id]),
                'size' => \yii\bootstrap\Modal::SIZE_LARGE,
            ],
            'buttonContent' => Html::a('<i class="glyphicon glyphicon-hdd"></i> ' . Yii::t('menst.cms', 'Versions'),
                    ['/cmf/version/default/item', 'item_id' => $model->id, 'item_class' => $model->className()], [
                        'class'=>'btn btn-default btn-sm',
                    ]),
            ]) ?>
    </p>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
