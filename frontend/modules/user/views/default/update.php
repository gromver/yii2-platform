<?php

/**
 * @var $this yii\web\View
 * @var \menst\models\ObjectModel $model
 * @var \menst\cms\common\models\User $user
 */

/** @var \menst\cms\common\models\MenuItem $menu */
$menu = Yii::$app->menuManager->getActiveMenu();
if ($menu) {
    $this->title = $menu->isProperContext() ? $menu->title : Yii::t('menst.cms', 'My profile');
    $this->params['breadcrumbs'] = $menu->getBreadcrumbs($menu->isApplicableContext());
} else {
    $this->title = Yii::t('menst.cms', 'My profile');
}
//$this->params['breadcrumbs'][] = $this->title;

echo \yii\helpers\Html::tag('h2', $this->title);

echo \menst\cms\frontend\widgets\UserProfile::widget([
    'id' => 'user-profile',
    'model' => $model
]);