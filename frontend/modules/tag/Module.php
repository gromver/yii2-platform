<?php
/**
 * @link https://github.com/gromver/yii2-cms.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/gromver/yii2-cmf/blob/master/LICENSE
 * @package yii2-cms
 * @version 1.0.0
 */

namespace gromver\cmf\frontend\modules\tag;

use gromver\cmf\frontend\interfaces\MenuUrlRuleInterface;
use gromver\cmf\frontend\modules\tag\components\TagMenuUrlRuleBehavior;

/**
 * Class Module
 * @package yii2-cms
 * @author Gayazov Roman <gromver5@gmail.com>
 */
class Module extends \yii\base\Module implements MenuUrlRuleInterface
{
    public $controllerNamespace = 'gromver\cmf\frontend\modules\tag\controllers';

    /*public function init()
    {
        parent::init();

        // custom initialization code goes here
    }*/
    /**
     * @inheritdoc
     */
    public function getMenuUrlRuleBehavior()
    {
        return TagMenuUrlRuleBehavior::className();
    }
}
