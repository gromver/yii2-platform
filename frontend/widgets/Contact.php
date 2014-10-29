<?php
/**
 * Created by PhpStorm.
 * User: roman
 * Date: 23.10.14
 * Time: 13:33
 */

namespace menst\cms\frontend\widgets;


use kartik\widgets\Alert;
use menst\cms\common\models\ContactForm;
use menst\cms\common\widgets\Widget;
use Yii;

class Contact extends Widget {
    /**
     * @type yesno
     */
    public $withCaptcha;
    public $view = 'contact/form';
    public $viewSuccess = 'contact/success';

    protected function launch()
    {
        $model = new ContactForm();
        if ($this->withCaptcha) {
            $model->scenario = 'withCaptcha';
        }

        if (!Yii::$app->user->isGuest) {
            /** @var \menst\cms\common\models\User $user */
            $user = Yii::$app->user->identity;
            $userParams = $user->getParamsArray();
            $model->name = $userParams['name'] ? $userParams['name'] : $user->username;
            $model->email = $user->email;
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->cms->params['adminEmail'])) {
                Yii::$app->session->setFlash(Alert::TYPE_SUCCESS, Yii::t('menst.cms', 'Email is sent.'));
                return $this->render($this->viewSuccess);
            } else {
                Yii::$app->session->setFlash(Alert::TYPE_SUCCESS, Yii::t('menst.cms', 'Error.'));
                //throw new \HttpRuntimeException(Yii::t('menst.cms', 'Email sending is failed.'));
            }
        }

        echo $this->render($this->view, [
            'model' => $model
        ]);
    }
}