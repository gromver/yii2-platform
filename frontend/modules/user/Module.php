<?php
/**
 * @link https://github.com/gromver/yii2-cmf.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/gromver/yii2-cmf/blob/master/LICENSE
 * @package yii2-cmf
 * @version 1.0.0
 */

namespace gromver\cmf\frontend\modules\user;

use gromver\cmf\common\interfaces\BootstrapInterface;
use gromver\cmf\common\models\User;

/**
 * Class Module
 * @package yii2-cmf
 * @author Gayazov Roman <gromver5@gmail.com>
 */
class Module extends \yii\base\Module
{
    public $controllerNamespace = 'gromver\cmf\frontend\modules\user\controllers';
    public $userParamsClass = 'gromver\cmf\common\models\UserParams';

    /*public function init()
    {
        parent::init();

        // custom initialization code goes here
    }*/
}
