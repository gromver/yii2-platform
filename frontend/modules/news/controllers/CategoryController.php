<?php
/**
 * @link https://github.com/menst/yii2-cms.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/menst/yii2-cms/blob/master/LICENSE
 * @package yii2-cms
 * @version 1.0.0
 */

namespace menst\cms\frontend\modules\news\controllers;

use menst\cms\common\models\Category;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use Yii;

/**
 * Class CategoryController
 * @package yii2-cms
 * @author Gayazov Roman <m.e.n.s.t@yandex.ru>
 */
class CategoryController extends Controller
{
    public $defaultAction = 'view';

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->loadModel($id),
        ]);
    }

    public function actionCategories($id)
    {
        return $this->render('categories', [
            'model' => $this->loadModel($id),
        ]);
    }

    public function actionPosts($id)
    {
        return $this->render('posts', [
            'model' => $this->loadModel($id),
        ]);
    }

    public function loadModel($id)
    {
        if(!($model = Category::findOne($id))) {
            throw new NotFoundHttpException(Yii::t('menst.cms', 'The requested category does not exist..'));
        }

        return $model;
    }
}
