<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user menst\cms\common\models\User */
/* @var $model menst\models\Model */

$this->title = Yii::t('menst.cms', 'Update User Params: {id}', [
    'id' => $user->id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('menst.cms', 'Users'), 'url' => ['default/index']];
$this->params['breadcrumbs'][] = ['label' => $user->id, 'url' => ['default/view', 'id' => $user->id]];
$this->params['breadcrumbs'][] = Yii::t('menst.cms', 'Update');
?>
<div class="user-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_formParams', [
        'model' => $model,
    ]) ?>

</div>
