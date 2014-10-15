<?php
/**
 * @var $this yii\web\View
 * @var $model string|\menst\cms\common\models\Category
 */
?>

<h2><?=\yii\helpers\Html::encode($model->title) ?></h2>

<?php echo \menst\cms\frontend\widgets\CategoryList::widget([
    'id' => 'cat-cats',
    'category' => $model,
    'listViewOptions' => [
        'emptyTextOptions' => ['class' => 'hidden']
    ]
]);

echo \menst\cms\frontend\widgets\PostList::widget([
    'id' => 'cat-posts',
    'category' => $model,
]);