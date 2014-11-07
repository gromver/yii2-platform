<?php

/* @var $this yii\web\View */

/** @var \gromver\cmf\common\models\MenuItem $menu */
$menu = Yii::$app->menuManager->getActiveMenu();
if ($menu) {
    $this->title = $menu->isProperContext() ? $menu->title : Yii::t('gromver.cmf', 'Contact');
    $this->params['breadcrumbs'] = $menu->getBreadcrumbs($menu->isApplicableContext());
} else {
    $this->title = Yii::t('gromver.cmf', 'Contact');
}

echo \gromver\cmf\frontend\widgets\Contact::widget([
    'id' => 'contact'
]);

