<?php
/**
 * @link https://github.com/gromver/yii2-cmf.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/gromver/yii2-cmf/blob/master/LICENSE
 * @package yii2-cmf
 * @version 1.0.0
 */

namespace gromver\cmf\frontend\behaviors;

use gromver\cmf\frontend\components\MenuManager;
use yii\base\Behavior;

/**
 * Class MenuUrlRuleBehavior
 * @package yii2-cmf
 * @author Gayazov Roman <gromver5@gmail.com>
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
     * @param $event \gromver\cmf\frontend\components\MenuUrlRuleEvent
     */
    public function parseRequest($event)
    {
        return;
    }

    /**
     * @param $event \gromver\cmf\frontend\components\MenuUrlRuleEvent
     */
    public function createUrl($event)
    {
        return;
    }
}