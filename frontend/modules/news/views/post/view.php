<?php
/**
 * @var $this yii\web\View
 * @var $model gromver\cmf\common\models\Post
 */

/** @var \gromver\cmf\common\models\MenuItem $menu */
$menu = Yii::$app->menuManager->getActiveMenu();
if ($menu) {
    $this->title = $menu->isProperContext() ? $menu->title : $model->title;
    $this->params['breadcrumbs'] = $menu->getBreadcrumbs($menu->isApplicableContext());
    //category breadcrumbs
    if ($menu->isApplicableContext()) {
        //меню ссылается на категорию
        list($route, $params) = $menu->parseUrl();
        if ($route == 'cmf/news/category/view') {
            $_breadcrumbs = $model->category->getBreadcrumbs(true);
            while (($crumb = array_pop($_breadcrumbs)) && $crumb['url']['id'] != $params['id']) {
                $this->params['breadcrumbs'][] = $crumb;
            }
        }
    }
} else {
    $this->title = $model->title;
    $this->params['breadcrumbs'] = $model->category->getBreadcrumbs(true);
}
//$this->params['breadcrumbs'][] = $this->title;
//мета теги
if ($model->metakey) {
    $this->registerMetaTag(['name' => 'keywords', 'content' => $model->metakey], 'keywords');
}
if ($model->metadesc) {
    $this->registerMetaTag(['name' => 'description', 'content' => $model->metadesc], 'description');
}


echo \gromver\cmf\frontend\widgets\PostView::widget([
    'id' => 'post-view',
    'post' => $model,
    'context' =>  Yii::$app->menuManager->activeMenu ? Yii::$app->menuManager->activeMenu->path : null
]);