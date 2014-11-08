<?php
/**
 * @link https://github.com/gromver/yii2-cmf.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/gromver/yii2-cmf/blob/master/LICENSE
 * @package yii2-cmf
 * @version 1.0.0
 */

namespace gromver\cmf\backend\modules\news\controllers;

use kartik\widgets\Alert;
use Yii;
use gromver\cmf\common\models\Category;
use gromver\cmf\backend\modules\news\models\CategorySearch;
use yii\db\ActiveRecord;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * Class CategoryController implements the CRUD actions for Category model.
 * @package yii2-cmf
 * @author Gayazov Roman <gromver5@gmail.com>
 */
class CategoryController extends Controller
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
                    'categories' => ['post'],
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
                        'actions' => ['index', 'view', 'select', 'categories'],
                        'roles' => ['read'],
                    ],
                ]
            ]
        ];
    }

    /**
     * Lists all Category models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CategorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @param string $route
     * @return string
     */
    public function actionSelect($route = 'cmf/news/category/view')
    {
        $searchModel = new CategorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, true);
        $dataProvider->query->noRoots();

        Yii::$app->cmf->layout = 'modal';

        return $this->render('select', [
                'dataProvider' => $dataProvider,
                'searchModel' => $searchModel,
                'route' => $route
            ]);
    }

    /**
     * Displays a single Category model.
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
     * Creates a new Category model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param string|null $language
     * @param string|null $sourceId
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionCreate($language = null, $sourceId = null)
    {
        $model = new Category();
        $model->loadDefaultValues();
        $model->status = Category::STATUS_PUBLISHED;
        $model->language = Yii::$app->language;

        if ($sourceId && $language) {
            $sourceModel = $this->findModel($sourceId);
            /*if (!$sourceModel->isRoot()) {
                if (!$targetCategory = $sourceModel->level > 2 ? Category::findOne(['path' => $sourceModel->parent->path, 'language' => $language]) : Category::find()->roots()->one()) {
                    throw new NotFoundHttpException(Yii::t('gromver.cmf', "The category for the specified localization isn't found."));
                }

                $model->parent_id = $targetCategory->id;
            }*/
            /** @var Category $parentItem */
            // если локализуемая категория имеет родителя, то пытаемся найти релевантную локализацию для родителя создаваемой категории
            if (!($sourceModel->level > 2 && $parentItem = @$sourceModel->parent->translations[$language])) {
                $parentItem = Category::find()->roots()->one();
            }

            $model->parent_id = $parentItem->id;
            $model->language = $language;
            $model->translation_id = $sourceModel->translation_id;
            $model->alias = $sourceModel->alias;
            $model->published_at = $sourceModel->published_at;
            $model->status = $sourceModel->status;
            $model->preview_text = $sourceModel->preview_text;
            $model->detail_text = $sourceModel->detail_text;
            $model->metakey = $sourceModel->metakey;
            $model->metadesc = $sourceModel->metadesc;
        } else {
            $sourceModel = null;
        }

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
     * Updates an existing Category model.
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
     * Deletes an existing Category model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if ($model->descendants()->count()) {
            Yii::$app->session->setFlash(Alert::TYPE_DANGER, Yii::t('gromver.cmf', "It's impossible to remove category ID:{id} to contain in it subcategories so far.", ['id' => $model->id]));
        } elseif ($model->getPosts()->count() > 0) {
            Yii::$app->session->setFlash(Alert::TYPE_DANGER, Yii::t('gromver.cmf', "It's impossible to remove category ID:{id} to contain in it posts so far.", ['id' => $model->id]));
        } else {
            $model->deleteNode();
        }

        if (Yii::$app->request->getIsDelete()) {
            return $this->redirect(ArrayHelper::getValue(Yii::$app->request, 'referrer', ['index']));
        }

        return $this->redirect(['index']);
    }

    public function actionBulkDelete()
    {
        $data = Yii::$app->request->getBodyParam('data', []);

        $models = Category::findAll(['id'=>$data]);

        foreach ($models as $model) {
            /** @var Category $model */
            if ($model->getPosts()->count() > 0 || $model->descendants()->count()) continue;

            $model->deleteNode();
        }

        return $this->redirect(ArrayHelper::getValue(Yii::$app->request, 'referrer', ['index']));
    }

    public function actionPublish($id)
    {
        $model = $this->findModel($id);

        $model->status = Category::STATUS_PUBLISHED;
        $model->save();

        return $this->redirect(ArrayHelper::getValue(Yii::$app->request, 'referrer', ['index']));
    }

    public function actionUnpublish($id)
    {
        $model = $this->findModel($id);

        $model->status = Category::STATUS_UNPUBLISHED;
        $model->save();

        return $this->redirect(ArrayHelper::getValue(Yii::$app->request, 'referrer', ['index']));
    }

    public function actionOrdering()
    {
        $data = Yii::$app->request->getBodyParam('data', []);

        foreach ($data as $id => $order) {
            if ($target = Category::findOne($id)) {
                $target->updateAttributes(['ordering' => intval($order)]);
            }
        }

        Category::find()->roots()->one()->reorderNode('ordering');
        (new Category())->trigger(ActiveRecord::EVENT_AFTER_UPDATE);    //фиксируем изменение таблицы в \gromver\cmf\common\models\Table

        return $this->redirect(ArrayHelper::getValue(Yii::$app->request, 'referrer', ['index']));
    }

    public function actionDeleteFile($pk, $attribute)
    {
        $model = $this->findModel($pk);

        $model->deleteFile($attribute);

        $this->redirect(['update', 'id'=>$pk]);
    }

    public function actionCategories($update_item_id = null, $selected = '')
    {
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $language = $parents[0];
                //исключаем редактируемый пункт и его подпункты из списка
                if (!empty($update_item_id) && $updateItem = Category::findOne($update_item_id)) {
                    $excludeIds = array_merge([$update_item_id], $updateItem->descendants()->select('id')->column());
                } else {
                    $excludeIds = [];
                }
                // the getSubCatList function will query the database based on the
                // cat_id and return an array like below:
                // [
                //    ['id'=>'<sub-cat-id-1>', 'name'=>'<sub-cat-name1>'],
                //    ['id'=>'<sub-cat_id_2>', 'name'=>'<sub-cat-name2>']
                // ]
                $out = array_map(function($value) {
                    return [
                        'id' => $value['id'],
                        'name' => str_repeat(" • ", $value['level'] - 1) . $value['title']
                    ];
                }, Category::find()->noRoots()->language($language)->orderBy('lft')->andWhere(['not in', 'id', $excludeIds])->asArray()->all());
                /** @var Category $root */
                $root = Category::find()->roots()->one();
                array_unshift($out, [
                    'id' => $root->id,
                    'name' => Yii::t('gromver.cmf', 'Root')
                ]);

                echo Json::encode(['output' => $out, 'selected' => $selected ? $selected : $root->id]);
                return;
            }
        }
        echo Json::encode(['output' => '', 'selected' => $selected]);
    }

    /**
     * Finds the Category model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Category the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Category::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('gromver.cmf', 'The requested page does not exist.'));
        }
    }
}
