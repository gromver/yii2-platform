<?php
/**
 * @link https://github.com/gromver/yii2-cmf.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/gromver/yii2-cmf/blob/master/LICENSE
 * @package yii2-cmf
 * @version 1.0.0
 */

namespace gromver\cmf\frontend\modules\news\controllers;

use gromver\cmf\common\models\Category;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use Yii;

/**
 * Class CategoryController
 * @package yii2-cmf
 * @author Gayazov Roman <gromver5@gmail.com>
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
            throw new NotFoundHttpException(Yii::t('gromver.cmf', 'The requested category does not exist..'));
        }

        return $model;
    }
}
