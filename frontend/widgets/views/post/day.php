<?php
/**
 * @var $this yii\web\View
 * @var $dataProvider yii\data\ActiveDataProvider
 * @var $itemLayout string
 * @var $prevDayPost null|\menst\cms\common\models\Post
 * @var $nextDayPost null|\menst\cms\common\models\Post
 * @var $category null|\menst\cms\common\models\Category
 * @var $year string
 * @var $month string
 * @var $day string
 * @var $listViewOptions array
 */

use yii\helpers\Html; ?>

<div class="row">
    <div class="col-sm-4 pull-right">
        <?= \menst\cms\frontend\widgets\Calendar::widget([
            'id' => 'news-calendar',
            'categoryId' => $category ? $category->id : null,
            'year' => $year,
            'month' => $month,
            'day' => $day
        ]) ?>

        <h3>Теги</h3>

        <?= \menst\cms\frontend\widgets\TagPostCloud::widget([
            'id' => 'posts-tags',
            'categoryId' => $category ? $category->id : null
        ]) ?>
    </div>
    <div class="col-sm-8">
        <?= \yii\widgets\ListView::widget(array_merge([
            'dataProvider' => $dataProvider,
            'itemView' => $itemLayout,
            'summary' => '',
            'viewParams' => [
                'postListWidget' => $this->context
            ]
        ], $listViewOptions)) ?>

        <div class="pagination">
            <?php if ($prevDayPost) {
                echo Html::tag('li', Html::a('&laquo; ' . Yii::$app->formatter->asDate($prevDayPost->published_at, 'd MMM'), $prevDayPost->getDayLink()));
            }

            if ($nextDayPost) {
                echo Html::tag('li', Html::a(Yii::$app->formatter->asDate($nextDayPost->published_at, 'd MMM') . ' &raquo;', $nextDayPost->getDayLink()));
            } ?>
        </div>
    </div>
</div>