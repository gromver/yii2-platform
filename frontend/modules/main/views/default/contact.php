<?php

/* @var $this yii\web\View */

/** @var \gromver\platform\common\models\MenuItem $menu */
$menu = Yii::$app->menuManager->getActiveMenu();
if ($menu) {
    $this->title = $menu->isProperContext() ? $menu->title : Yii::t('gromver.platform', 'Contact form');
    $this->params['breadcrumbs'] = $menu->getBreadcrumbs($menu->isApplicableContext());
} else {
    $this->title = Yii::t('gromver.platform', 'Contact form');
}

echo \gromver\platform\frontend\widgets\Contact::widget([
    'id' => 'contact-form'
]);

