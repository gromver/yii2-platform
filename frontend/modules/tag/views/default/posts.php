<?php
/**
 * @var $this yii\web\View
 * @var $model \menst\cms\common\models\Tag
 */

/** @var \menst\cms\common\models\MenuItem $menu */
$menu = Yii::$app->menuManager->getActiveMenu();
if ($menu) {
    $this->title = $menu->isProperContext() ? $menu->title : $model->title;
    $this->params['breadcrumbs'] = $menu->getBreadcrumbs($menu->isApplicableContext());
} else {
    $this->title = $model->title;
}
//$this->params['breadcrumbs'][] = $this->title;
//мета теги
if ($model->metakey) {
    $this->registerMetaTag(['name' => 'keywords', 'content' => $model->metakey], 'keywords');
}
if ($model->metadesc) {
    $this->registerMetaTag(['name' => 'description', 'content' => $model->metadesc], 'description');
}


echo \menst\cms\frontend\widgets\TagPosts::widget([
    'id' => 'tag-posts',
    'source' => $model,
    'context' =>  Yii::$app->menuManager->activeMenu ? Yii::$app->menuManager->activeMenu->path : null,
]) ?>