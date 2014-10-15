<?php
/**
 * @link https://github.com/menst/yii2-cms.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/menst/yii2-cms/blob/master/LICENSE
 * @package yii2-cms
 * @version 1.0.0
 */

namespace menst\cms\frontend\modules\user;

use menst\cms\common\interfaces\BootstrapInterface;
use menst\cms\common\models\User;

/**
 * Class Module
 * @package yii2-cms
 * @author Gayazov Roman <m.e.n.s.t@yandex.ru>
 */
class Module extends \yii\base\Module
{
    public $controllerNamespace = 'menst\cms\frontend\modules\user\controllers';
    public $userParamsClass = 'menst\cms\common\models\UserParams';

    /*public function init()
    {
        parent::init();

        // custom initialization code goes here
    }*/
}
