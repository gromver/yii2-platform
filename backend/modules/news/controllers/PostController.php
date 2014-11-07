<?php
/**
 * @link https://github.com/gromver/yii2-cms.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/gromver/yii2-cmf/blob/master/LICENSE
 * @package yii2-cms
 * @version 1.0.0
 */

namespace gromver\cmf\backend\modules\news\controllers;

use gromver\cmf\common\models\Category;
use gromver\cmf\common\models\Post;
use gromver\cmf\backend\modules\news\models\PostSearch;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * Class PostController implements the CRUD actions for Post model.
 * @package yii2-cms
 * @author Gayazov Roman <gromver5@gmail.com>
 */
class PostController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post', 'delete'],
                    'bulk-delete' => ['post'],
                    'delete-file' => ['post'],
                    'publish' => ['post'],
                    'unpublish' => ['post'],
                    'ordering' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['create', 'update', 'ordering', 'delete-file', 'publish', 'unpublish'],
                        'roles' => ['update'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['delete', 'bulk-delete'],
                        'roles' => ['delete'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'select'],
                        'roles' => ['read'],
                    ],
                ]
            ]
        ];
    }

    /**
     * Lists all Post models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PostSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all Post models.
     * @return mixed
     */
    public function actionSelect($route = 'cmf/news/post/view')
    {
        $searchModel = new PostSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        Yii::$app->getModule('cms')->layout = 'modal';

        return $this->render('select', [
                'dataProvider' => $dataProvider,
                'searchModel' => $searchModel,
                'route' => $route
            ]);
    }

    /**
     * Displays a single Post model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Post model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($language=null, $sourceId=null, $category_id = null)
    {
        $model = new Post();
        $model->loadDefaultValues();
        $model->status = Category::STATUS_PUBLISHED;
        $model->category_id = $category_id;

        if($sourceId && $language) {
            $sourceModel = $this->findModel($sourceId);
            if(!$targetCategory = Category::findOne(['path' => $sourceModel->category->path, 'language' => $language]))
                throw new NotFoundHttpException(Yii::t('menst.cms', "The category for the specified localization isn't found."));
            $model->category_id = $targetCategory->id;
            $model->alias = $sourceModel->alias;
            $model->published_at = $sourceModel->published_at;
            $model->status = $sourceModel->status;
            $model->preview_text = $sourceModel->preview_text;
            $model->detail_text = $sourceModel->detail_text;
            $model->metakey = $sourceModel->metakey;
            $model->metadesc = $sourceModel->metadesc;
        } else
            $sourceModel = null;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'sourceModel' => $sourceModel
            ]);
        }
    }

    /**
     * Updates an existing Post model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Post model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionBulkDelete()
    {
        $data = Yii::$app->request->getBodyParam('data', []);

        $models = Post::find()->where(['id'=>$data])->all();

        foreach($models as $model)
            $model->delete();

        return $this->redirect(ArrayHelper::getValue(Yii::$app->request, 'referrer', ['index']));
    }

    public function actionPublish($id)
    {
        $model = $this->findModel($id);

        $model->status = Post::STATUS_PUBLISHED;
        $model->save();

        return $this->redirect(ArrayHelper::getValue(Yii::$app->request, 'referrer', ['index']));
    }

    public function actionUnpublish($id)
    {
        $model = $this->findModel($id);

        $model->status = Post::STATUS_UNPUBLISHED;
        $model->save();

        return $this->redirect(ArrayHelper::getValue(Yii::$app->request, 'referrer', ['index']));
    }

    public function actionOrdering()
    {
        $data = Yii::$app->request->getBodyParam('data', []);

        foreach($data as $id => $order){
            if($target = Post::findOne($id))
                $target->updateAttributes(['ordering'=>$order]);
        }

        return $this->redirect(ArrayHelper::getValue(Yii::$app->request, 'referrer', ['index']));
    }

    public function actionDeleteFile($pk, $attribute)
    {
        $model = $this->findModel($pk);

        $model->deleteFile($attribute);

        $this->redirect(['update', 'id'=>$pk]);
    }

    /**
     * Finds the Post model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Post the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Post::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('menst.cms', 'The requested page does not exist.'));
        }
    }
}
