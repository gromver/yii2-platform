<?php
/**
 * @var $this yii\web\View
 * @var $model \gromver\platform\common\models\Page
 */

use yii\helpers\Html;

if($this->context->showTranslations)
    echo \gromver\platform\frontend\widgets\Translations::widget([
        'model' => $model,
        'options' => [
            'class' => 'pull-right'
        ]
    ]);

echo Html::tag('h2', Html::encode($model->title));

echo Html::tag('div', $model->detail_text);
