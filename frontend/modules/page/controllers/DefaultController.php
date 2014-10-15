<?php
/**
 * @link https://github.com/menst/yii2-cms.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/menst/yii2-cms/blob/master/LICENSE
 * @package yii2-cms
 * @version 1.0.0
 */

namespace menst\cms\frontend\modules\page\controllers;

use menst\cms\common\models\Page;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use Yii;

/**
 * Class DefaultController
 * @package yii2-cms
 * @author Gayazov Roman <m.e.n.s.t@yandex.ru>
 */
class DefaultController extends Controller
{
    public function actionIndex()
    {
        throw new NotFoundHttpException(Yii::t('menst.cms', 'The requested page does not exist..'));
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->loadModel($id)
        ]);
    }

    public function loadModel($id)
    {
        if(!($model = Page::findOne($id))) {
            throw new NotFoundHttpException(Yii::t('menst.cms', 'The requested category does not exist..'));
        }

        return $model;
    }
}
