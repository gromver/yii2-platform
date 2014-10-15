<?php
/**
 * @var $this yii\web\View
 */

use yii\helpers\Html;

/** @var \menst\cms\common\models\MenuItem $menu */
$menu = Yii::$app->menuManager->getActiveMenu();
if ($menu) {
    $this->title = $menu->isProperContext() ? $menu->title : Yii::t('menst.cms', 'News');
    $this->params['breadcrumbs'] = $menu->getBreadcrumbs($menu->isApplicableContext());
} else {
    $this->title = Yii::t('menst.cms', 'News');
}
//$this->params['breadcrumbs'][] = $this->title;

echo Html::tag('h2', Html::encode($this->title));

echo \menst\cms\frontend\widgets\PostList::widget([
    'id' => 'post-index',
    'context' =>  Yii::$app->menuManager->activeMenu ? Yii::$app->menuManager->activeMenu->path : null,
]) ?>