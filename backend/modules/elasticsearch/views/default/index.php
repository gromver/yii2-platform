<?php

/* @var $this yii\web\View */
/* @var $query string */

?>
<div class="search-default-index">
    <h1><?= Yii::t('gromver.cmf', 'Search') ?></h1>

    <?php echo \gromver\cmf\backend\modules\elasticsearch\widgets\SearchForm::widget([
        'id' => 'bSearchForm',
        'query' => $query,
        'showPanel' => false
    ]);

    echo \gromver\cmf\backend\modules\elasticsearch\widgets\SearchResults::widget([
        'id' => 'bSearchResult',
        'query' => $query,
        'filters' => [],
        //'debug' => true
    ]); ?>
</div>
