<?php
/**
 * @var $this yii\web\View
 * @var $model string|\gromver\cmf\common\models\Category
 */
?>

<h2><?=\yii\helpers\Html::encode($model->title) ?></h2>

<?php echo \gromver\cmf\frontend\widgets\CategoryList::widget([
    'id' => 'cat-cats',
    'category' => $model,
    'listViewOptions' => [
        'emptyTextOptions' => ['class' => 'hidden']
    ]
]);

echo \gromver\cmf\frontend\widgets\PostList::widget([
    'id' => 'cat-posts',
    'category' => $model,
]);