<?php
/**
 * @link https://github.com/menst/yii2-cms.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/menst/yii2-cms/blob/master/LICENSE
 * @package yii2-cms
 * @version 1.0.0
 */

namespace menst\cms\backend\modules\search\controllers;

use yii\elasticsearch\Exception;
use yii\filters\AccessControl;
use yii\helpers\Json;
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
                        'actions' => ['index'],
                        'roles' => ['read'],
                    ]
                ]
            ]
        ];
    }

    public function actionIndex($q = null)
    {
        return $this->render('index', [
            'query' => $q
        ]);
    }

    public function actionReindex()
    {
        $documents = [
            'menst\cms\common\models\search\Page',
            'menst\cms\common\models\search\Post',
            'menst\cms\common\models\search\Category',
        ];

        foreach ($documents as $documentClass) {
            echo "Uploading {$documentClass} models.";
            $completed = $this->upload($documentClass);
            echo "{$completed} items uploaded.";
        }
    }

    /**
     * @param $documentClass \menst\cms\common\models\search\ActiveDocument
     * @return int
     * @throws \yii\elasticsearch\Exception
     */
    public function upload($documentClass)
    {
        $bulk = '';
        /** @var \yii\db\ActiveRecord|string $modelClass */
        $modelClass = $documentClass::model();
        /** @var \menst\cms\common\models\search\ActiveDocument $document */
        $document = new $documentClass;
        $uploaded = 0;
        foreach ($modelClass::find()->each() as $model) {
            /** @var \yii\db\ActiveRecord $model */
            $action = Json::encode([
                "index" => [
                    "_id" => $model->getPrimaryKey(),
                    "_type" => $documentClass::type(),
                    "_index" => $documentClass::index(),
                ],
            ]);

            $document->loadModel($model);
            $data = Json::encode($document->toArray());
            $bulk .= $action . "\n" . $data . "\n";
            $uploaded++;
        }

        $url = [$documentClass::index(), $documentClass::type(), '_bulk'];
        $response = \yii\elasticsearch\ActiveRecord::getDb()->post($url, [], $bulk);
        $n = 0;
        $errors = [];
        foreach ($response['items'] as $item) {
            if (isset($item['index']['status']) && ($item['index']['status'] == 201 || $item['index']['status'] == 200)) {
                $n++;
            } else {
                $errors[] = $item['index'];
            }
        }
        if (!empty($errors) || isset($response['errors']) && $response['errors']) {
            throw new Exception(__METHOD__ . ' failed inserting '. $modelClass .' model records.', $errors);
        }

        return $n;
    }

}
