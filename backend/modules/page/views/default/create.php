<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model gromver\cmf\common\models\Page */
/* @var $sourceModel gromver\cmf\common\models\Page */

$this->title = Yii::t('gromver.cmf', 'Add Page');
$this->params['breadcrumbs'][] = ['label' => Yii::t('gromver.cmf', 'Pages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="page-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'sourceModel' => $sourceModel
    ]) ?>

</div>
