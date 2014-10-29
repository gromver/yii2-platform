<?php
/**
 * @var $this yii\web\View
 * @var $dataProvider yii\data\ActiveDataProvider
 * @var $itemLayout string
 * @var $category null|\menst\cms\common\models\Category
 * @var $listViewOptions array
 */
use kartik\icons\Icon;

Icon::map($this, Icon::EL);

echo \yii\helpers\Html::a(Icon::show('rss', [], Icon::EL), $category ? ['/cms/news/post/rss', 'category_id' => $category->id] : ['/cms/news/post/rss'], ['class' => 'btn btn-warning btn-xs pull-right']);

echo \yii\widgets\ListView::widget(array_merge([
    'dataProvider' => $dataProvider,
    'itemView' => $itemLayout,
    'summary' => '',
    'viewParams' => [
        'postListWidget' => $this->context
    ]
], $this->context->listViewOptions));