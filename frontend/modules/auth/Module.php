<?php
/**
 * @link https://github.com/menst/yii2-cms.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/menst/yii2-cms/blob/master/LICENSE
 * @package yii2-cms
 * @version 1.0.0
 */

namespace menst\cms\frontend\modules\auth;

/**
 * Class Module
 * @package yii2-cms
 * @author Gayazov Roman <m.e.n.s.t@yandex.ru>
 */
class Module extends \yii\base\Module
{
    public $controllerNamespace = 'menst\cms\frontend\modules\auth\controllers';

    /**
     * @var int
     * @desc Remember Me Time (seconds), default = 2592000 (30 days)
     */
    public $rememberMeTime = 2592000; // 30 days

    public $attemptsBeforeCaptcha = 3; // Unsuccessful Login Attempts before Captcha

    public $loginLayout = 'login';

    /*public function init()
    {
        parent::init();

        // custom initialization code goes here
    }*/
}
