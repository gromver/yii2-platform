<?php
/**
 * @var $this yii\web\View
 * @var $model \menst\cms\common\models\Post
 */

use yii\helpers\Html;

if($this->context->showTranslations)
    echo \menst\cms\frontend\widgets\Translations::widget([
        'model' => $model,
        'options' => [
            'class' => 'pull-right'
        ]
    ]);

echo Html::tag('small', Yii::$app->formatter->asDatetime($model->published_at));

echo Html::tag('h2', Html::encode($model->title));

if($model->detail_image) echo Html::img($model->getFileUrl('detail_image'), [
    'class' => 'text-block img-responsive',
    //'style' => 'max-width: 200px; margin-right: 15px;'
]);

echo Html::tag('div', $model->detail_text);