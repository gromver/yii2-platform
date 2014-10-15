<?php
/**
 * @link https://github.com/menst/yii2-cms.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/menst/yii2-cms/blob/master/LICENSE
 * @package yii2-cms
 * @version 1.0.0
 */

namespace menst\cms\frontend\modules\main\controllers;

use menst\cms\backend\modules\menu\models\MenuTypeSearch;
use menst\cms\backend\modules\news\models\CategorySearch;
use menst\cms\backend\modules\news\models\PostSearch;
use menst\cms\backend\modules\page\models\PageSearch;
use menst\cms\common\models\Tag;
use menst\cms\common\models\CmsParams;
use kartik\widgets\Alert;
use menst\models\ObjectModel;
use menst\widgets\ModalIFrame;
use Yii;
use yii\caching\Cache;
use yii\di\Instance;
use yii\helpers\FileHelper;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Class DefaultController
 * @package yii2-cms
 * @author Gayazov Roman <m.e.n.s.t@yandex.ru>
 *
 * @property $module \menst\cms\frontend\modules\main\Module
 */
class DefaultController extends Controller
{
    //todo access rules
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionMode($mode, $backUrl = null) {
        $this->module->setMode($mode);

        $this->redirect($backUrl ? $backUrl : Yii::$app->request->getReferrer());
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
        } else {
            $this->layout = 'main';
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

    public function actionPageNotFound()
    {
        throw new NotFoundHttpException(Yii::t('yii', 'Page not found.'));
    }

    public function actionSelectMenu()
    {
        $searchModel = new MenuTypeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        $this->layout = 'modal';

        return $this->render('select-menu', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionSelectPage()
    {
        $searchModel = new PageSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        $this->layout = 'modal';

        return $this->render('select-page', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionSelectCategory()
    {
        $searchModel = new CategorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        $this->layout = 'modal';

        return $this->render('select-category', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionSelectPost()
    {
        $searchModel = new PostSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        $this->layout = 'modal';

        return $this->render('select-post', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionTagList($query = null, $language = null) {
        $result = Tag::find()->select('id AS value, title AS text, group AS optgroup')->filterWhere(['like', 'title', urldecode($query)])->andFilterWhere(['language' => $language])->limit(20)->asArray()->all();

        echo Json::encode($result);
    }
}
