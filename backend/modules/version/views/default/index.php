<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel gromver\cmf\backend\modules\version\models\VersionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('menst.cms', 'Versions');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="history-index">

    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?php /*// echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('menst.cms', 'Create {modelClass}', [
    'modelClass' => 'Version',
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
                'attribute'=>'id',
                'width'=>'40px'
            ],
            [
                'attribute'=>'item_id',
                'width'=>'40px'
            ],
            'item_class',
            'version_note',
            [
                'attribute' => 'created_at',
                'format' => ['date', 'd MMM Y H:i'],
                'width' => '160px',
                'filterType' => GridView::FILTER_DATE,
                'filterWidgetOptions' => [
                    'options' => ['value' => is_int($searchModel->created_at) ? date('d.m.Y', $searchModel->created_at) : ''],
                    'pluginOptions' => [
                        'format' => 'dd.mm.yyyy'
                    ]
                ]
            ],
            [
                'attribute' => 'created_by',
                'value' => function($model) {
                        /** $model \gromver\cmf\common\models\Version */
                        return $model->user ? $model->user->username : $model->created_by;
                    }
            ],
            [
                'attribute' => 'keep_forever',
                'value'=>function($model) {
                        /** $model \gromver\cmf\common\models\Version */
                        return Html::a($model->keep_forever ? Yii::t('menst.cms', 'Yes') . ' <small class="glyphicon glyphicon-lock"></small>' : Yii::t('menst.cms', 'No'), ['keep-forever', 'id' => $model->id], [
                            'class'=>'btn btn-xs btn-default active btn-keep-forever',
                            'data-method' => 'post',
                            'data-pjax' => 0
                        ]);
                    },
                'format' => 'raw'
            ],
            [
                'class' => 'kartik\grid\ActionColumn',
                'deleteOptions' => ['data-method' => 'delete'],
                'buttons' => [
                    'restore' => function($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-open"></span>', $url, [
                                'title' => Yii::t('menst.cms', 'Restore'),
                                'data-method' => 'post',
                                'data-pjax' => 0
                            ]);
                        },
                ],
                'template'=>'{restore} {view} {update} {delete}'
            ]
        ],
        'responsive' => true,
        'hover' => true,
        'condensed' => true,
        'floatHeader' => true,
        'bordered' => false,
        'panel' => [
            'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> ' . Html::encode($this->title) . ' </h3>',
            'type' => 'info',
            'before' => ' ',
            'after' =>
                Html::a('<i class="glyphicon glyphicon-trash"></i> ' . Yii::t('menst.cms', 'Delete'), ['bulk-delete'], ['class' => 'btn btn-danger', 'data-pjax' => '0', 'onclick' => 'processAction(this); return false']) . ' ' .
                Html::a('<i class="glyphicon glyphicon-repeat"></i> ' . Yii::t('menst.cms', 'Reset List'), ['index'], ['class' => 'btn btn-info']),
            'showFooter' => false
        ],
    ]) ?>

</div>
<script>
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
