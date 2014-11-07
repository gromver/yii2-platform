<?php
/**
 * @link https://github.com/gromver/yii2-cms.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/gromver/yii2-cmf/blob/master/LICENSE
 * @package yii2-cms
 * @version 1.0.0
 */

namespace gromver\cmf\backend\modules\auth;

use gromver\cmf\backend\interfaces\DesktopInterface;
use gromver\cmf\backend\interfaces\MenuRouterInterface;
use Yii;

/**
 * Class Module
 * Этот модуль используется админкой для авторизации пользователя, можно настроить период запоминания пользователя в куках,
 * количесвто безуспешных попыток авторизации с последущим подключением капчи
 *
 * @package yii2-cms
 * @author Gayazov Roman <gromver5@gmail.com>
 */
class Module extends \yii\base\Module implements MenuRouterInterface, DesktopInterface
{
    public $controllerNamespace = 'gromver\cmf\backend\modules\auth\controllers';
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
                ['label' => Yii::t('menst.cms', 'Login'), 'route' => 'cmf/auth/default/login'],
                ['label' => Yii::t('menst.cms', 'Signup'), 'route' => 'cmf/auth/default/signup'],
                ['label' => Yii::t('menst.cms', 'Request password reset token'), 'route' => 'cmf/auth/default/request-password-reset-token'],
                ['label' => Yii::t('menst.cms', 'Reset password'), 'route' => 'cmf/auth/default/reset-password'],
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
                ['label' => Yii::t('menst.cms', 'Login'), 'url' => ['/cmf/auth/default/login']],
                ['label' => Yii::t('menst.cms', 'Password Reset'), 'url' => ['/cmf/auth/default/request-password-reset']],
            ]
        ];
    }
}
