<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel menst\cms\backend\modules\menu\models\MenuItemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('menst.cms', 'Menu Items');
$this->params['breadcrumbs'][] = ['label' => Yii::t('menst.cms', 'Menu Types'), 'url' => ['type/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-index">

    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php /*<p>
        <?= Html::a(Yii::t('menst.cms', 'Create {modelClass}', [
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
                'value' => function ($model) {
                        /** @var $model \menst\cms\common\models\MenuItem */
                        return \menst\cms\backend\widgets\Translator::widget(['model' => $model]);
                    },
                'format' => 'raw',
                'filter' => Yii::$app->getLanguagesList()

            ],
            [
                'attribute' => 'menu_type_id',
                'width' => '100px',
                'value' => function ($model) {
                        /** @var $model \menst\cms\common\models\MenuItem */
                        return $model->menuType->title;
                    },
                'filter' => \yii\helpers\ArrayHelper::map(\menst\cms\common\models\MenuType::find()->all(), 'id', 'title')
            ],
            [
                'attribute' => 'title',
                'value' => function ($model) {
                        /** @var $model \menst\cms\common\models\MenuItem */
                        return str_repeat(" • ", max($model->level-2, 0)) . $model->title . '<br/>'.Html::tag('small', $model->path);
                    },
                'format' => 'html'
            ],
            'link',
            [
                'attribute' => 'status',
                'value' => function ($model){
                        /** @var $model \menst\cms\common\models\Menu */
                        return $model->status === \menst\cms\common\models\MenuItem::STATUS_PUBLISHED ? Html::a('<i class="glyphicon glyphicon-ok-circle"></i>', \yii\helpers\Url::to(['unpublish', 'id' => $model->id]), ['class' => 'btn btn-default btn-xs', 'data-pjax' => 0, 'data-method' => 'post']) : Html::a('<i class="glyphicon glyphicon-remove-circle"></i>', \yii\helpers\Url::to(['publish', 'id' => $model->id]), ['class' => 'btn btn-default btn-xs', 'data-pjax' => 0, 'data-method' => 'post']);
                    },
                'filter' => \menst\cms\common\models\MenuItem::statusLabels(),
                'width' => '80px',
                'format' => 'raw'
            ],
            [
                'attribute' => 'ordering',
                'value' => function ($model) {
                        /** @var $model \menst\cms\common\models\Menu */
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
            'before' => Html::a('<i class="glyphicon glyphicon-plus"></i> ' . Yii::t('menst.cms', 'Add'), ['create', 'menu_type_id' => $searchModel->menu_type_id], ['class' => 'btn btn-success', 'data-pjax' => '0']),
            'after' =>
                Html::a('<i class="glyphicon glyphicon-sort-by-attributes"></i> ' . Yii::t('menst.cms', 'Ordering'), ['ordering'], ['class' => 'btn btn-default', 'data-pjax' => '0', 'onclick' => 'processOrdering(this); return false']).' '.
                Html::a('<i class="glyphicon glyphicon-trash"></i> ' . Yii::t('menst.cms', 'Delete'), ['bulk-delete'], ['class' => 'btn btn-danger', 'data-pjax' => '0', 'onclick' => 'processAction(this); return false']) . ' ' .
                Html::a('<i class="glyphicon glyphicon-repeat"></i> ' . Yii::t('menst.cms', 'Reset List'), ['index'], ['class' => 'btn btn-info']),
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
            alert(<?= json_encode(Yii::t('menst.cms', 'Select items.')) ?>)
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
            alert(<?= json_encode(Yii::t('menst.cms', 'Select items.')) ?>)
            return
        }

        $.post($el.attr('href'), {data:selection}, function(response){
            $grid.yiiGridView('applyFilter')
        })
    }
</script>