<?php

/* @var $this yii\web\View */
/* @var $query string */

$this->title = Yii::t('gromver.cmf', 'Search');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="search-default-index">
    <h1><?= Yii::t('gromver.cmf', 'Search') ?></h1>

    <?php echo \gromver\cmf\common\widgets\SearchForm::widget([
        'id' => 'bSearchForm',
        'query' => $query,
        'showPanel' => false
    ]);

    echo \gromver\cmf\common\widgets\SearchResults::widget([
        'id' => 'bSearchResult',
        'query' => $query,
        'filters' => [],
    ]); ?>
</div>
