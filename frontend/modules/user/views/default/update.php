<?php

/**
 * @var $this yii\web\View
 * @var \gromver\models\ObjectModel $model
 * @var \gromver\cmf\common\models\User $user
 */

/** @var \gromver\cmf\common\models\MenuItem $menu */
$menu = Yii::$app->menuManager->getActiveMenu();
if ($menu) {
    $this->title = $menu->isProperContext() ? $menu->title : Yii::t('menst.cms', 'My profile');
    $this->params['breadcrumbs'] = $menu->getBreadcrumbs($menu->isApplicableContext());
} else {
    $this->title = Yii::t('menst.cms', 'My profile');
}
//$this->params['breadcrumbs'][] = $this->title;

echo \yii\helpers\Html::tag('h2', $this->title);

echo \gromver\cmf\frontend\widgets\UserProfile::widget([
    'id' => 'user-profile',
    'model' => $model
]);