<?
/**
 * @link https://github.com/gromver/yii2-cmf.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/gromver/yii2-cmf/blob/master/LICENSE
 * @package yii2-cmf
 * @version 1.0.0
 */

namespace gromver\cmf\frontend\modules\search\components;
use gromver\cmf\common\models\MenuItem;

/**
 * Class SearchMenuUrlBehavior
 * @package yii2-cmf
 * @author Gayazov Roman <gromver5@gmail.com>
 */
class SearchMenuUrlBehavior extends \gromver\cmf\frontend\behaviors\MenuUrlRuleBehavior {
    /**
     * @inheritdoc
     */
    public function createUrl($event)
    {
        if ($event->requestRoute == 'cmf/search/default/index' && $path = $event->menuMap->getMenuPathByRoute('cmf/search/default/index')) {
            $event->resolve(MenuItem::toRoute($path, $event->requestParams));
        }
    }
} 