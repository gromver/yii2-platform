<?
/**
 * @link https://github.com/menst/yii2-cms.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/menst/yii2-cms/blob/master/LICENSE
 * @package yii2-cms
 * @version 1.0.0
 */

namespace menst\cms\frontend\modules\search\components;
use menst\cms\common\models\MenuItem;

/**
 * Class SearchMenuUrlBehavior
 * @package yii2-cms
 * @author Gayazov Roman <m.e.n.s.t@yandex.ru>
 */
class SearchMenuUrlBehavior extends \menst\cms\frontend\behaviors\MenuUrlRuleBehavior {
    /**
     * @inheritdoc
     */
    public function createUrl($event)
    {
        if ($event->requestRoute == 'cms/search/default/index' && $path = $event->menuMap->getMenuPathByRoute('cms/search/default/index')) {
            $event->resolve(MenuItem::toRoute($path, $event->requestParams));
        }
    }
} 