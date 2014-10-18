<?php
/**
 * @var $this yii\web\View
 * @var $model Post
 * @var $key string
 * @var $index integer
 * @var $widget \yii\widgets\ListView
 * @var $postListWidget \menst\cms\frontend\widgets\PostList
 */

use yii\helpers\Html;
use menst\cms\common\models\Post;

$urlManager = Yii::$app->urlManager; ?>

<h4 class="issue-title"><?= Html::a(Html::encode($model->title), $urlManager->createUrl($model->getViewLink())) ?></h4>

<?php if($model->preview_image) {
    echo Html::img($model->getFileUrl('preview_image'), [
        'class' => 'pull-left',
        'style' => 'max-width: 200px; margin-right: 15px;'
    ]);
} ?>

<div class="issue-preview"><?= $model->preview_text ?></div>
<div class="clearfix"></div>
<div class="issue-bar">
    <small class="issue-published"><?= Yii::$app->formatter->asDatetime($model->published_at) ?></small>
    <small class="issue-separator">|</small>
    <?php foreach ($model->tags as $tag) {
        /** @var $tag \menst\cms\common\models\Tag */
        echo Html::a($tag->title, ['/cms/tag/default/posts', 'tag_id' => $tag->id, 'tag_alias' => $tag->alias, 'category_id' => $postListWidget->category ? $postListWidget->category->id : null], ['class' => 'issue-tag badge']);
    } ?>
</div>

<style>
    .issue-bar {
        border-top: 1px solid #cccccc;
        padding: 6px;
        margin-bottom: 2em;
        background-color: #FCF4F4;
        font-size: 12px;
    }
    .issue-separator {
        margin: 0 8px;
    }
    .issue-tag {
        font-size: 10px;
        margin-right: 0.8em;
    }
</style>