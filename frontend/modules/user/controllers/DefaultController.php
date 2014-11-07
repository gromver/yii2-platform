<?php
/**
 * @link https://github.com/gromver/yii2-cms.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/gromver/yii2-cmf/blob/master/LICENSE
 * @package yii2-cms
 * @version 1.0.0
 */

namespace gromver\cmf\frontend\modules\user\controllers;

use kartik\widgets\Alert;
use gromver\models\ObjectModel;
use Yii;
use gromver\cmf\common\models\User;
use gromver\models\Model;
use yii\base\InvalidParamException;
use yii\filters\AccessControl;
use yii\web\Controller;

/**
 * Class DefaultController
 * @package yii2-cms
 * @author Gayazov Roman <gromver5@gmail.com>
 */
class DefaultController extends Controller
{
    public function behaviors()
    {
        return [
            [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['update', 'index'],
                        'roles' => ['@'],
                    ],
                ]
            ]
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionUpdate()
    {
        /** @var \gromver\cmf\common\models\User $user */
        $user = Yii::$app->user->getIdentity();

        $model = $this->extractParamsModel($user);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $user->setParamsArray($model->toArray());

            if ($user->save()) {
                Yii::$app->session->setFlash(Alert::TYPE_SUCCESS, Yii::t('menst.cms', "Profile saved."));
                return $this->redirect('');
            } else {
                Yii::$app->session->setFlash(Alert::TYPE_DANGER, Yii::t('menst.cms', "It wasn't succeeded to keep the user's parameters. Error:\n{error}", ['error' => implode("\n", $user->getFirstErrors())]));
            }
        }

        return $this->render('update', [
            'user' => $user,
            'model' => $model
        ]);
    }

    /**
     * @param $user User
     * @return ObjectModel
     */
    protected function extractParamsModel($user)
    {
        if ($this->module->userParamsClass) {
            try {
                $attributes = $user->getParamsArray();
            } catch(InvalidParamException $e) {
                $attributes = [];
            }

            $model = new ObjectModel($this->module->userParamsClass);
            $model->setAttributes($attributes);

            return $model;
        }
    }
}
