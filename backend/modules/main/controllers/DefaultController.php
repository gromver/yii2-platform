<?php
/**
 * @link https://github.com/menst/yii2-cms.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/menst/yii2-cms/blob/master/LICENSE
 * @package yii2-cms
 * @version 1.0.0
 */

namespace menst\cms\backend\modules\main\controllers;

use menst\cms\common\helpers\ModuleQuery;
use menst\cms\common\models\CmsParams;
use kartik\widgets\Alert;
use menst\models\ObjectModel;
use menst\widgets\ModalIFrame;
use Yii;
use yii\caching\Cache;
use yii\di\Instance;
use yii\filters\AccessControl;
use yii\helpers\FileHelper;
use yii\web\Controller;

/**
 * Class DefaultController
 * @package yii2-cms
 * @author Gayazov Roman <m.e.n.s.t@yandex.ru>
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
                        'actions' => ['index', 'error'],
                        'roles' => ['read'],
                    ]
                ]
            ]
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ]
        ];
    }

    public function actionIndex()
    {
        return $this->render('index', [
                'items' => ModuleQuery::instance()->implement('\menst\cms\backend\interfaces\DesktopInterface')->orderBy('desktopOrder')->execute('getDesktopItem')
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
                file_put_contents($paramsFile, '<?php return '.var_export($model->toArray(), true).';');

                Yii::$app->session->setFlash(Alert::TYPE_SUCCESS, Yii::t('menst.cms', 'Configuration saved.'));
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

        Yii::$app->session->setFlash(Alert::TYPE_SUCCESS, Yii::t('menst.cms', 'Cache flushed.'));

        return $this->redirect(['index']);
    }
}
