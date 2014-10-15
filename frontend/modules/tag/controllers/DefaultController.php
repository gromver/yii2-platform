<?php
/**
 * @link https://github.com/menst/yii2-cms.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/menst/yii2-cms/blob/master/LICENSE
 * @package yii2-cms
 * @version 1.0.0
 */

namespace menst\cms\frontend\modules\tag\controllers;

use menst\cms\common\models\Tag;
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
        return $this->render('index');
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->loadModel($id)
        ]);
    }

    public function actionPosts($id)
    {
        return $this->render('posts', [
            'model' => $this->loadModel($id)
        ]);
    }

    public function loadModel($id)
    {
        if(!($model = Tag::findOne($id))) {
            throw new NotFoundHttpException(Yii::t('menst.cms', 'The requested tag does not exist..'));
        }

        return $model;
    }
}
