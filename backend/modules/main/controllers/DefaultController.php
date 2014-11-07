<?php
/**
 * @link https://github.com/gromver/yii2-cmf.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/gromver/yii2-cmf/blob/master/LICENSE
 * @package yii2-cmf
 * @version 1.0.0
 */

namespace gromver\cmf\backend\modules\main\controllers;

use gromver\modulequery\ModuleQuery;
use gromver\cmf\common\models\CmsParams;
use kartik\widgets\Alert;
use gromver\cmf\common\models\ContactForm;
use gromver\models\ObjectModel;
use gromver\widgets\ModalIFrame;
use Yii;
use yii\caching\Cache;
use yii\di\Instance;
use yii\filters\AccessControl;
use yii\helpers\FileHelper;
use yii\web\Controller;

/**
 * Class DefaultController
 * @package yii2-cmf
 * @author Gayazov Roman <gromver5@gmail.com>
 */
class DefaultController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['params', 'flush-cache'],
                        'roles' => ['update'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'error', 'contact', 'captcha'],
                        'roles' => ['read'],
                    ],
                ]
            ]
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => 'yii\captcha\CaptchaAction'
        ];
    }

    public function actionIndex()
    {
        return $this->render('index', [
                'items' => ModuleQuery::instance()->implement('\gromver\cmf\backend\interfaces\DesktopInterface')->orderBy('desktopOrder')->execute('getDesktopItem')
            ]);
    }

    public function actionParams($modal = null)
    {
        $paramsPath = Yii::getAlias($this->module->paramsPath);
        $paramsFile = $paramsPath . DIRECTORY_SEPARATOR . 'params.php';

        $params = $this->module->params;

        $model = new ObjectModel(CmsParams::className());
        $model->setAttributes($params);

        if($model->load(Yii::$app->request->post())) {
            if($model->validate() && Yii::$app->request->getBodyParam('task') !== 'refresh') {

                FileHelper::createDirectory($paramsPath);
                file_put_contents($paramsFile, '<?php return ' . var_export($model->toArray(), true) . ';');

                Yii::$app->session->setFlash(Alert::TYPE_SUCCESS, Yii::t('gromver.cmf', 'Configuration saved.'));
                if ($modal) {
                    ModalIFrame::refreshPage();
                }
                return $this->redirect(['params']);
            }
        }

        if ($modal) {
            $this->layout = 'modal';
        }

        return $this->render('params', [
            'model' => $model
        ]);
    }

    public function actionFlushCache($component = 'cache')
    {
        /** @var Cache $cache */
        $cache = Instance::ensure($component, Cache::className());

        $cache->flush();

        Yii::$app->session->setFlash(Alert::TYPE_SUCCESS, Yii::t('gromver.cmf', 'Cache flushed.'));

        return $this->redirect(['index']);
    }

    public function actionContact()
    {
        $model = new ContactForm();

        if (!Yii::$app->user->isGuest) {
            /** @var \gromver\cmf\common\models\User $user */
            $user = Yii::$app->user->identity;
            $userParams = $user->getParamsArray();
            $model->name = $userParams['name'] ? $userParams['name'] : $user->username;
            $model->email = $user->email;
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->cmf->params['adminEmail'])) {
                Yii::$app->session->setFlash(Alert::TYPE_SUCCESS, Yii::t('gromver.cmf', 'Email is sent.'));
                return $this->render('contactSuccess');
            } else {
                throw new \HttpRuntimeException(Yii::t('gromver.cmf', 'Email sending is failed.'));
            }
        }

        return $this->render('contact', [
            'model' => $model
        ]);
    }
}
