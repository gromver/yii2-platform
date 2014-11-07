<?php
/**
 * @var $this yii\web\View
 * @var $model gromver\cmf\common\models\Tag
 */

/** @var \gromver\cmf\common\models\MenuItem $menu */
$menu = Yii::$app->menuManager->getActiveMenu();
if ($menu) {
    $this->title = $menu->isProperContext() ? $menu->title : Yii::t('gromver.cmf', 'Tag: {tag}', ['tag' => $model->title]);
    $this->params['breadcrumbs'] = $menu->getBreadcrumbs($menu->isApplicableContext());
} else {
    $this->title = Yii::t('gromver.cmf', 'Tag: {tag}', ['tag' => $model->title]);
    $this->params['breadcrumbs'][] = ['label' => 'Tag cloud', 'url' => ['/cmf/tag/default/index']];
}
//$this->params['breadcrumbs'][] = $this->title;
//мета теги
if ($model->metakey) {
    $this->registerMetaTag(['name' => 'keywords', 'content' => $model->metakey], 'keywords');
}
if ($model->metadesc) {
    $this->registerMetaTag(['name' => 'description', 'content' => $model->metadesc], 'description');
}


echo \gromver\cmf\frontend\widgets\TagItems::widget([
    'id' => 'tag-items',
    'source' => $model,
]);