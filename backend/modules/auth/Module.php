<?php
/**
 * @link https://github.com/menst/yii2-cms.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/menst/yii2-cms/blob/master/LICENSE
 * @package yii2-cms
 * @version 1.0.0
 */

namespace menst\cms\backend\modules\auth;

use menst\cms\backend\interfaces\DesktopInterface;
use menst\cms\backend\interfaces\MenuRouterInterface;
use Yii;

/**
 * Class Module
 * Этот модуль используется админкой для авторизации пользователя, можно настроить период запоминания пользователя в куках,
 * количесвто безуспешных попыток авторизации с последущим подключением капчи
 *
 * @package yii2-cms
 * @author Gayazov Roman <m.e.n.s.t@yandex.ru>
 */
class Module extends \yii\base\Module implements MenuRouterInterface, DesktopInterface
{
    public $controllerNamespace = 'menst\cms\backend\modules\auth\controllers';
    /**
     * @var int
     * @desc Remember Me Time (seconds), default = 2592000 (30 days)
     */
    public $rememberMeTime = 2592000; // 30 days
    public $attemptsBeforeCaptcha = 3; // Unsuccessful Login Attempts before Captcha
    public $loginLayout = 'login';
    public $desktopOrder = 2;

    /*public function init()
    {
        parent::init();

        // custom initialization code goes here
    }*/

    /**
     * @inheritdoc
     */
    public function getMenuRoutes()
    {
        return [
            'label' => Yii::t('menst.cms', 'Auth'),
            'routers' => [
                ['label' => Yii::t('menst.cms', 'Login'), 'route' => 'cms/auth/default/login'],
                ['label' => Yii::t('menst.cms', 'Signup'), 'route' => 'cms/auth/default/signup'],
                ['label' => Yii::t('menst.cms', 'Request password reset token'), 'route' => 'cms/auth/default/request-password-reset-token'],
                ['label' => Yii::t('menst.cms', 'Reset password'), 'route' => 'cms/auth/default/reset-password'],
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function getDesktopItem()
    {
        return [
            'label' => Yii::t('menst.cms', 'Auth'),
            'links' => [
                ['label' => Yii::t('menst.cms', 'Login'), 'url' => ['/cms/auth/default/login']],
                ['label' => Yii::t('menst.cms', 'Password Reset'), 'url' => ['/cms/auth/default/request-password-reset']],
            ]
        ];
    }
}
