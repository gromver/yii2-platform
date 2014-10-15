<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model menst\cms\common\models\Category */
/* @var $sourceModel menst\cms\common\models\Category */

$this->title = Yii::t('menst.cms', 'Add Category');
$this->params['breadcrumbs'][] = ['label' => Yii::t('menst.cms', 'Categories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="category-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'sourceModel' => $sourceModel
    ]) ?>

</div>
