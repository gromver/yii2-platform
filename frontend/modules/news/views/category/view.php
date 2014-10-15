<?php
/**
 * @var $this yii\web\View
 * @var $model string | menst\cms\common\models\Category
 */

/** @var \menst\cms\common\models\MenuItem $menu */
$menu = Yii::$app->menuManager->getActiveMenu();
if ($menu) {
    $this->title = $menu->isProperContext() ? $menu->title : $model->title;
    $this->params['breadcrumbs'] = $menu->getBreadcrumbs($menu->isApplicableContext());
    //category breadcrumbs
    if ($menu->isApplicableContext()) {
        //меню ссылается на категорию
        list($route, $params) = $menu->parseUrl();
        $_breadcrumbs = $model->getBreadcrumbs(false);
        while (($crumb = array_pop($_breadcrumbs)) && $crumb['url']['id'] != $params['id']) {
            $this->params['breadcrumbs'][] = $crumb;
        }
    }
} else {
    $this->title = $model->title;
    $this->params['breadcrumbs'] = $model->getBreadcrumbs(false);
}
//$this->params['breadcrumbs'][] = $this->title;
//мета теги
if ($model->metakey) {
    $this->registerMetaTag(['name' => 'keywords', 'content' => $model->metakey], 'keywords');
}
if ($model->metadesc) {
    $this->registerMetaTag(['name' => 'description', 'content' => $model->metadesc], 'description');
}


echo \menst\cms\frontend\widgets\CategoryView::widget([
    'id' => 'cat-view',
    'source' => $model,
]);