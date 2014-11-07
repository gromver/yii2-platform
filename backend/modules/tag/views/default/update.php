<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model gromver\cmf\common\models\Tag */

$this->title = Yii::t('menst.cms', 'Update Tag: {title}', [
    'title' => $model->title
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('menst.cms', 'Tags'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('menst.cms', 'Update');
?>
<div class="tag-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
