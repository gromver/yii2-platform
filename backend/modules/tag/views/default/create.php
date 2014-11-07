<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model gromver\cmf\common\models\Tag */

$this->title = Yii::t('gromver.cmf', 'Create Tag');
$this->params['breadcrumbs'][] = ['label' => Yii::t('gromver.cmf', 'Tags'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tag-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
