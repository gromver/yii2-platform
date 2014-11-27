<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var \gromver\cmf\backend\modules\news\models\CategorySearch $searchModel
 */

$this->title = Yii::t('gromver.cmf', 'Select Category');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-index">

    <?/*<h1><?= Html::encode($this->title) ?></h1>*/?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'id' => 'grid',
        'pjax' => true,
        'pjaxSettings' => [
            'neverTimeout' => true,
        ],
        'columns' => [
            [
                'attribute'=>'id',
                'width'=>'50px'
            ],
            [
                'attribute'=>'language',
                'filter'=>Yii::$app->getLanguagesList()
            ],
            [
                'attribute' => 'title',
                'value' => function($model) {
                        /** @var $model \gromver\cmf\common\models\Category */
                        return $model->title . '<br/>' . Html::tag('small', $model->alias, ['class' => 'text-muted']);
                    },
                'format' => 'html'

            ],
            [
                'attribute' => 'status',
                'value' => function ($model, $index, $widget) {
                    /** @var $model \gromver\cmf\common\models\Category */
                    return $model->getStatusLabel();
                },
                'filter' => \gromver\cmf\common\models\Category::statusLabels()
            ],
            [
                'attribute' => 'published_at',
                'format' => ['date', 'd MMM Y H:mm'],
                'width' => '160px',
                'filterType' => GridView::FILTER_DATE,
                'filterWidgetOptions' => [
                    'options' => ['value' => is_int($searchModel->published_at) ? date('d.m.Y', $searchModel->published_at) : ''],
                    'pluginOptions' => [
                        'format' => 'dd.mm.yyyy'
                    ]
                ]
            ],
            [
                'attribute' => 'tags',
                'value' => function($model){
                        /** @var $model \gromver\cmf\common\models\Category */
                        return implode(', ', \yii\helpers\ArrayHelper::map($model->tags, 'id', 'title'));
                    },
                'filterType' => \dosamigos\selectize\Selectize::className(),
                'filterWidgetOptions' => [
                    'items' => \yii\helpers\ArrayHelper::map(\gromver\cmf\common\models\Tag::find()->where(['id' => $searchModel->tags])->all(), 'id', 'title', 'group'),
                    'clientOptions' => [
                        'maxItems' => 1
                    ],
                    'url' => ['/cmf/default/tag-list']
                ]
            ],
            [
                'value' => function($model) {
                        return Html::a(Yii::t('gromver.cmf', 'Select'), '#', [
                            'class' => 'btn btn-primary btn-xs',
                            'onclick' => \gromver\widgets\ModalIFrame::emitDataJs([
                                    'id' => $model->id,
                                    'description' => Yii::t('gromver.cmf', 'Category: {title}', ['title' => $model->title]),
                                    'value' => $model->id . ':' . $model->alias
                                ]),
                        ]);
                    },
                'format'=>'raw'
            ]
        ],
        'responsive' => true,
        'hover' => true,
        'condensed' => true,
        'floatHeader' => true,
        'floatHeaderOptions' => ['scrollingTop' => 0],
        'bordered' => false,
        'panel' => [
            'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> ' . Html::encode($this->title) . ' </h3>',
            'type' => 'info',
            'after' => Html::a('<i class="glyphicon glyphicon-repeat"></i> ' . Yii::t('gromver.cmf', 'Reset List'), [null], ['class' => 'btn btn-info']),
            'showFooter' => false,
        ],
    ]) ?>

</div>