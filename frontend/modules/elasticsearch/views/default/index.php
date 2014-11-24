<?php

/* @var $this yii\web\View */
/* @var $query string */

/** @var \gromver\cmf\common\models\MenuItem $menu */
$menu = Yii::$app->menuManager->getActiveMenu();
if ($menu) {
    $this->title = $menu->isProperContext() ? $menu->title : Yii::t('gromver.cmf', 'Search');
    $this->params['breadcrumbs'] = $menu->getBreadcrumbs($menu->isApplicableContext());
} else {
    $this->title = Yii::t('gromver.cmf', 'Search');
}
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="search-default-index">
    <h1><?= $this->title ?></h1>
    <?php echo \gromver\cmf\common\modules\elasticsearch\widgets\SearchForm::widget([
        'id' => 'fSearchForm',
        'query' => $query,
        'showPanel' => false
    ]);

    echo \gromver\cmf\common\modules\elasticsearch\widgets\SearchResults::widget([
        'id' => 'fSearchResult',
        'query' => $query,
        'language' => Yii::$app->language,
        //'debug' => true
    ]); ?>
</div>
