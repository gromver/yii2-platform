<?php
/**
 * @link https://github.com/menst/yii2-cms.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/menst/yii2-cms/blob/master/LICENSE
 * @package yii2-cms
 * @version 1.0.0
 */

namespace menst\cms\frontend\modules\news;

use menst\cms\frontend\interfaces\MenuUrlRuleInterface;
use menst\cms\frontend\modules\news\components\NewsMenuUrlBehavior;

/**
 * Class Module
 * @package yii2-cms
 * @author Gayazov Roman <m.e.n.s.t@yandex.ru>
 */
class Module extends \yii\base\Module implements MenuUrlRuleInterface
{
    public $controllerNamespace = 'menst\cms\frontend\modules\news\controllers';
    public $defaultRoute = 'post';

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
        return NewsMenuUrlBehavior::className();
    }
}
