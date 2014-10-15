<?php

/* @var $this yii\web\View */
/* @var $query string */

?>
<div class="search-default-index">
    <h1><?= $this->context->action->uniqueId ?></h1>
    <?php echo \menst\cms\common\widgets\SearchForm::widget([
        'id' => 'bSearchForm',
        'query' => $query,
        'showPanel' => false
    ]);

    echo \menst\cms\common\widgets\SearchResults::widget([
        'id' => 'bSearchResult',
        'query' => $query,
    ]); ?>
</div>
