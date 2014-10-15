<?php
/**
 * @var $this yii\web\View
 * @var $model \menst\cms\common\models\Page
 */

use yii\helpers\Html;

if($this->context->showTranslations)
    echo \menst\cms\frontend\widgets\Translations::widget([
        'model' => $model,
        'options' => [
            'class' => 'pull-right'
        ]
    ]);

echo Html::tag('h2', Html::encode($model->title));

echo Html::tag('div', $model->detail_text);
