<?php
/**
 * @link https://github.com/menst/yii2-cms.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/menst/yii2-cms/blob/master/LICENSE
 * @package yii2-cms
 * @version 1.0.0
 */

namespace menst\cms\frontend\modules\tag;

use menst\cms\frontend\interfaces\MenuUrlRuleInterface;
use menst\cms\frontend\modules\tag\components\TagMenuUrlRuleBehavior;

/**
 * Class Module
 * @package yii2-cms
 * @author Gayazov Roman <m.e.n.s.t@yandex.ru>
 */
class Module extends \yii\base\Module implements MenuUrlRuleInterface
{
    public $controllerNamespace = 'menst\cms\frontend\modules\tag\controllers';

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
