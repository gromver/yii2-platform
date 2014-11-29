<?php
/**
 * @link https://github.com/gromver/yii2-cmf.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/gromver/yii2-grom/blob/master/LICENSE
 * @package yii2-cmf
 * @version 1.0.0
 */

namespace gromver\platform\frontend\modules\widget\controllers;


use gromver\models\ObjectModel;
use gromver\widgets\ModalIFrame;
use Yii;
use yii\helpers\Json;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use gromver\platform\common\models\WidgetConfig;

/**
 * Class DefaultController implements the CRUD actions for Config model.
 * @package yii2-cmf
 * @author Gayazov Roman <gromver5@gmail.com>
 */
class DefaultController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'configure' => ['post'],
                ],
            ],
        ];
    }

    public function actionConfigure($modal=null)
    {
        if(!($widget_id = Yii::$app->request->getBodyParam('widget_id')))
            throw new BadRequestHttpException(Yii::t('gromver.platform', "Widget ID isn't specified"));

        if(!($widget_class = Yii::$app->request->getBodyParam('widget_class')))
            throw new BadRequestHttpException(Yii::t('gromver.platform', "Widget Class isn't specified"));

        if(($widget_context = Yii::$app->request->getBodyParam('widget_context'))===null)
            throw new BadRequestHttpException(Yii::t('gromver.platform', "Widget Context isn't specified"));

        $selected_context = Yii::$app->request->getBodyParam('selected_context', $widget_context);

        $task = Yii::$app->request->getBodyParam('task');

        if(($url = Yii::$app->request->getBodyParam('url'))===null)
            throw new BadRequestHttpException(Yii::t('gromver.platform', "Widget page url isn't specified"));
        //$url = Yii::$app->request->getBodyParam('url', Yii::$app->request->getReferrer());

        if ($task=='delete') {
            if (Yii::$app->request->getBodyParam('bulk-method')) {
                foreach (WidgetConfig::find()->where('widget_id=:widget_id AND context>=:context AND language=:language', [
                    ':widget_id' => $widget_id,
                    ':context' => $selected_context,
                    ':language' => Yii::$app->language
                ])->each() as $configModel) {
                    $configModel->delete();
                }
            } elseif ($configModel = WidgetConfig::findOne([
                'widget_id'=>$widget_id,
                'context'=>$selected_context,
                'language' => Yii::$app->language
            ])) {
                $configModel->delete();
            }

            if ($modal) {
                ModalIFrame::refreshPage();
            }
        }

        $widget_config = Yii::$app->request->getBodyParam('widget_config', '[]');
        $widgetConfig = Json::decode($widget_config);
        $widgetConfig['id'] = $widget_id;
        $widgetConfig['context'] = $selected_context;
        $widget = new $widget_class($widgetConfig);

        $model = new ObjectModel($widget);

        if (($task == 'save' || $task == 'refresh') && $model->load(Yii::$app->request->post())) {
            if ($model->validate() && $task=='save') {
                $configModel = WidgetConfig::findOne([
                    'widget_id' => $widget_id,
                    'context' => $selected_context,
                    'language' => Yii::$app->language
                ]) or $configModel = new WidgetConfig;

                $configModel->loadDefaultValues();
                $configModel->widget_id = $widget_id;
                $configModel->widget_class = $widget_class;
                $configModel->context = $selected_context;
                $configModel->url = $url;
                $configModel->setParamsArray($model->toArray());

                $configModel->save();

                if (Yii::$app->request->getBodyParam('bulk-method')) {
                    foreach (WidgetConfig::find()->where('widget_id=:widget_id AND context>:context AND language=:language', [
                        ':widget_id' => $widget_id,
                        ':context' => $selected_context,
                        ':language' => Yii::$app->language
                    ])->each() as $configModel) {
                        /** @var $configModel WidgetConfig */
                        $configModel->delete();
                    }
                }

                if ($modal) {
                    ModalIFrame::refreshPage();
                } else {
                    return $this->redirect($url);
                }
            }
        }

        if($modal) {
            Yii::$app->grom->layout = 'modal';
        }

        return $this->render('_formConfig', [
            'model' => $model,
            'widget' => $widget,
            'widget_id' => $widget_id,
            'widget_class' => $widget_class,
            'widget_config' => $widget_config,
            'widget_context' => $widget_context,
            'selected_context' => $selected_context,
            'url' => $url,
        ]);
    }
}
