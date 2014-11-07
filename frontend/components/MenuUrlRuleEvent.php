<?php
/**
 * @link https://github.com/gromver/yii2-cms.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/gromver/yii2-cmf/blob/master/LICENSE
 * @package yii2-cms
 * @version 1.0.0
 */

namespace gromver\cmf\frontend\components;

use yii\base\Event;

/**
 * Class MenuUrlRuleEvent
 * @package yii2-cms
 * @author Gayazov Roman <gromver5@gmail.com>
 *
 * @property \gromver\cmf\frontend\components\MenuManager $sender
 */
class MenuUrlRuleEvent extends Event
{
    /**
     * @var \gromver\cmf\frontend\components\MenuMap
     */
    public $menuMap;

    public $menuRoute;
    public $menuParams;

    public $requestRoute;
    public $requestParams;

    public $result = false;

    public function resolve($result)
    {
        $this->handled = true;
        $this->result = $result;
    }
}
