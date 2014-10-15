<?php
/**
 * @link https://github.com/menst/yii2-cms.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/menst/yii2-cms/blob/master/LICENSE
 * @package yii2-cms
 * @version 1.0.0
 */

namespace menst\cms\frontend\behaviors;

use menst\cms\frontend\components\MenuManager;
use yii\base\Behavior;

/**
 * Class MenuUrlRuleBehavior
 * @package yii2-cms
 * @author Gayazov Roman <m.e.n.s.t@yandex.ru>
 */
class MenuUrlRuleBehavior extends Behavior
{
    public function events()
    {
        return [
            MenuManager::EVENT_CREATE_URL => 'createUrl',
            MenuManager::EVENT_PARSE_REQUEST => 'parseRequest',
        ];
    }

    /**
     * @param $event \menst\cms\frontend\components\MenuUrlRuleEvent
     */
    public function parseRequest($event)
    {
        return;
    }

    /**
     * @param $event \menst\cms\frontend\components\MenuUrlRuleEvent
     */
    public function createUrl($event)
    {
        return;
    }
}