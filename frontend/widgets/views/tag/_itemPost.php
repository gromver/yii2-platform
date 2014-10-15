<?php
/**
 * @var $this yii\web\View
 * @var $model \menst\cms\common\models\Post
 * @var $key string
 * @var $index integer
 * @var $widget \yii\widgets\ListView
 */

use yii\helpers\Html;

echo Html::tag('h4', Html::a(Html::encode($model->title), $model->getViewLink()));