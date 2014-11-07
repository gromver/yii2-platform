<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model gromver\cmf\common\models\Post */

$this->title = Yii::t('gromver.cmf', 'Add Post');
$this->params['breadcrumbs'][] = ['label' => Yii::t('gromver.cmf', 'Posts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="post-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
