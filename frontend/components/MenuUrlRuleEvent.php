<?php
/**
 * @link https://github.com/menst/yii2-cms.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/menst/yii2-cms/blob/master/LICENSE
 * @package yii2-cms
 * @version 1.0.0
 */

namespace menst\cms\frontend\components;

use yii\base\Event;

/**
 * Class MenuUrlRuleEvent
 * @package yii2-cms
 * @author Gayazov Roman <m.e.n.s.t@yandex.ru>
 *
 * @property \menst\cms\frontend\components\MenuManager $sender
 */
class MenuUrlRuleEvent extends Event
{
    /**
     * @var \menst\cms\frontend\components\MenuMap
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
