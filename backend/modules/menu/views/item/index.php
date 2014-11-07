<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel gromver\cmf\backend\modules\menu\models\MenuItemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('gromver.cmf', 'Menu Items');
$this->params['breadcrumbs'][] = ['label' => Yii::t('gromver.cmf', 'Menu Types'), 'url' => ['type/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-index">

    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php /*<p>
        <?= Html::a(Yii::t('gromver.cmf', 'Create {modelClass}', [
    'modelClass' => 'Menu',
]), ['create'], ['class' => 'btn btn-success']) ?>
    </p>*/?>

    <?= GridView::widget([
        'id' => 'table-grid',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'pjax' => true,
        'pjaxSettings' => [
            'neverTimeout' => true,
        ],
        'columns' => [
            ['class' => 'yii\grid\CheckboxColumn'],
            [
                'attribute' => 'id',
                'width' => '50px'
            ],
            [
                'attribute' => 'language',
                'width' => '50px',
                'filter' => Yii::$app->getLanguagesList()
            ],
            [
                'attribute' => 'menu_type_id',
                'width' => '100px',
                'value' => function ($model) {
                    /** @var $model \gromver\cmf\common\models\MenuItem */
                    return $model->menuType->title;
                },
                'filter' => \yii\helpers\ArrayHelper::map(\gromver\cmf\common\models\MenuType::find()->all(), 'id', 'title')
            ],
            /*[
                'attribute' => 'parent_id',
                'width' => '100px',
                'value' => function ($model) {
                    /** @var $model \gromver\cmf\common\models\MenuItem * /
                    return $model->level > 2 ? $model->parent->title : '';
                },
                'filter' => \yii\helpers\ArrayHelper::map(\gromver\cmf\common\models\MenuItem::find()->noRoots()->orderBy('lft')->all(), 'id', function($model){
                    /** @var $model \gromver\cmf\common\models\MenuItem * /
                    return str_repeat(" • ", max($model->level-2, 0)) . $model->title;
                })
            ],*/
            [
                'attribute' => 'title',
                'value' => function ($model) {
                    /** @var $model \gromver\cmf\common\models\MenuItem */
                    return str_repeat(" • ", max($model->level-2, 0)) . $model->title . '<br/>'.Html::tag('small', $model->path);
                },
                'format' => 'html'
            ],
            'link',
            [
                'attribute' => 'status',
                'value' => function ($model){
                    /** @var $model \gromver\cmf\common\models\MenuItem */
                    return Html::beginTag('div', ['class' => 'btn-group']) .
                    Html::a('<i class="glyphicon glyphicon-star"></i>', \yii\helpers\Url::to(['status', 'id' => $model->id, 'status' => $model::STATUS_MAIN_PAGE]), ['class' => 'btn btn-xs' . ($model::STATUS_MAIN_PAGE == $model->status ? ' btn-success active' : ' btn-default'), 'data-pjax' => 0, 'data-method' => 'post']) .
                    Html::a('<i class="glyphicon glyphicon-ok-circle"></i>', \yii\helpers\Url::to(['status', 'id' => $model->id, 'status' => $model::STATUS_PUBLISHED]), ['class' => 'btn btn-xs' . ($model::STATUS_PUBLISHED == $model->status ? ' btn-primary active' : ' btn-default'), 'data-pjax' => 0, 'data-method' => 'post']) .
                    Html::a('<i class="glyphicon glyphicon-remove-circle"></i>', \yii\helpers\Url::to(['status', 'id' => $model->id, 'status' => $model::STATUS_UNPUBLISHED]), ['class' => 'btn btn-xs' . ($model::STATUS_UNPUBLISHED == $model->status ? ' btn-default active' : ' btn-default'), 'data-pjax' => 0, 'data-method' => 'post']) .
                    Html::endTag('div');
                },
                'filter' => \gromver\cmf\common\models\MenuItem::statusLabels(),
                'width' => '80px',
                'format' => 'raw'
            ],
            [
                'attribute' => 'ordering',
                'value' => function ($model) {
                    /** @var $model \gromver\cmf\common\models\MenuItem */
                    return Html::input('text', 'order', $model->ordering, ['class' => 'form-control']);
                },
                'format' => 'raw',
                'width' => '50px'
            ],
            [
                'class' => 'kartik\grid\ActionColumn',
                'deleteOptions' => ['data-method' => 'delete']
            ],
        ],
        'responsive' => true,
        'hover' => true,
        'condensed' => true,
        'floatHeader' => true,
        'bordered' => false,
        'panel' => [
            'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> ' . Html::encode($this->title) . ' </h3>',
            'type' => 'info',
            'before' => Html::a('<i class="glyphicon glyphicon-plus"></i> ' . Yii::t('gromver.cmf', 'Add'), ['create', 'menu_type_id' => $searchModel->menu_type_id], ['class' => 'btn btn-success', 'data-pjax' => '0']),
            'after' =>
                Html::a('<i class="glyphicon glyphicon-sort-by-attributes"></i> ' . Yii::t('gromver.cmf', 'Ordering'), ['ordering'], ['class' => 'btn btn-default', 'data-pjax' => '0', 'onclick' => 'processOrdering(this); return false']).' '.
                Html::a('<i class="glyphicon glyphicon-trash"></i> ' . Yii::t('gromver.cmf', 'Delete'), ['bulk-delete'], ['class' => 'btn btn-danger', 'data-pjax' => '0', 'onclick' => 'processAction(this); return false']) . ' ' .
                Html::a('<i class="glyphicon glyphicon-repeat"></i> ' . Yii::t('gromver.cmf', 'Reset List'), ['index'], ['class' => 'btn btn-info']),
            'showFooter' => false
        ],
    ]); ?>

</div>

<script>
    function processOrdering(el) {
        var $el = $(el),
            $grid = $('#table-grid'),
            selection = $grid.yiiGridView('getSelectedRows'),
            data = {}
        if(!selection.length) {
            alert(<?= json_encode(Yii::t('gromver.cmf', 'Select items.')) ?>)
            return
        }
        $.each(selection, function(index, value){
            data[value] = $grid.find('tr[data-key="'+value+'"] input[name="order"]').val()
        })

        $.post($el.attr('href'), {data:data}, function(response){
            $grid.yiiGridView('applyFilter')
        })
    }
    function processAction(el) {
        var $el = $(el),
            $grid = $('#table-grid'),
            selection = $grid.yiiGridView('getSelectedRows')
        if(!selection.length) {
            alert(<?= json_encode(Yii::t('gromver.cmf', 'Select items.')) ?>)
            return
        }

        $.post($el.attr('href'), {data:selection}, function(response){
            $grid.yiiGridView('applyFilter')
        })
    }
</script>