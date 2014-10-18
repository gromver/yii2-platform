<?php

/* @var $this yii\web\View */
/* @var $query string */

/** @var \menst\cms\common\models\MenuItem $menu */
$menu = Yii::$app->menuManager->getActiveMenu();
if ($menu) {
    $this->title = $menu->isProperContext() ? $menu->title : Yii::t('menst.cms', 'Search');
    $this->params['breadcrumbs'] = $menu->getBreadcrumbs($menu->isApplicableContext());
} else {
    $this->title = Yii::t('menst.cms', 'Search');
}
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="search-default-index">
    <h1><?= $this->title ?></h1>
    <?php echo \menst\cms\common\widgets\SearchForm::widget([
        'id' => 'fSearchForm',
        'query' => $query,
        'showPanel' => false
    ]);

    echo \menst\cms\common\widgets\SearchResults::widget([
        'id' => 'fSearchResult',
        'query' => $query,
        //'debug' => true
    ]); ?>
</div>
