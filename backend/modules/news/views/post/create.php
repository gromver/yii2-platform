<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model gromver\cmf\common\models\Post */
/* @var $sourceModel gromver\cmf\common\models\Post */

$this->title = Yii::t('menst.cms', 'Add Post');
$this->params['breadcrumbs'][] = ['label' => Yii::t('menst.cms', 'Posts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="post-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'sourceModel' => $sourceModel
    ]) ?>

</div>
