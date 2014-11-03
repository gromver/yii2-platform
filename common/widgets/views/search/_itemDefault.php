<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model \menst\cms\common\models\search\ActiveDocument */

echo Html::beginTag('div', ['class' => 'search-result-item']);

echo Html::a($model->highlight['title'][0], $model->getViewLink(), ['class' => 'h4 title']);
echo Html::tag('p', ($model->hasAttribute('date') ? Html::tag('small', Yii::$app->formatter->asDate($model->date, 'd MMMM Y'), ['class' => 'date']) . ' - ' : '') . implode(' ... ', $model->highlight['text']), ['class' => 'text']);

echo Html::endTag('div');