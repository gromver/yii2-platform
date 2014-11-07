<?php
/**
 * @var $this yii\web\View
 * @var $model \gromver\cmf\common\models\Page
 */

use yii\helpers\Html;

echo Html::tag('h2', Html::encode($model->title));

echo Html::tag('div', $model->detail_text);
