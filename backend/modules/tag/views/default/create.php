<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model gromver\cmf\common\models\Tag */
/* @var $sourceModel gromver\cmf\common\models\Tag */

$this->title = Yii::t('menst.cms', 'Create Tag');
$this->params['breadcrumbs'][] = ['label' => Yii::t('menst.cms', 'Tags'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tag-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'sourceModel' => $sourceModel,
    ]) ?>

</div>
