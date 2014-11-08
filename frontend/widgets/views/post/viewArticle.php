<?php
/**
 * @var $this yii\web\View
 * @var $model \gromver\cmf\common\models\Post
 */

use yii\helpers\Html;

if($this->context->showTranslations)
    echo \gromver\cmf\frontend\widgets\Translations::widget([
        'model' => $model,
        'options' => [
            'class' => 'pull-right'
        ]
    ]);

echo Html::tag('h2', Html::encode($model->title));

if($model->detail_image) echo Html::img($model->getFileUrl('detail_image'), [
    'class' => 'text-block img-responsive',
]);

echo Html::tag('div', $model->detail_text);
