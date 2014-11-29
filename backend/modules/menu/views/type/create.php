<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model gromver\platform\common\models\MenuType */

$this->title = Yii::t('gromver.platform', 'Добавить пункт меню');
$this->params['breadcrumbs'][] = ['label' => Yii::t('gromver.platform', 'Типы меню'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
            'model' => $model,
        ]) ?>

</div>
