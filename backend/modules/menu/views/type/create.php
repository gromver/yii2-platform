<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model menst\cms\common\models\MenuType */

$this->title = Yii::t('menst.cms', 'Добавить пункт меню');
$this->params['breadcrumbs'][] = ['label' => Yii::t('menst.cms', 'Типы меню'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
            'model' => $model,
        ]) ?>

</div>
