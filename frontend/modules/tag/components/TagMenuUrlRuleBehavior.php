<?php
/**
 * @link https://github.com/gromver/yii2-cms.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/gromver/yii2-cmf/blob/master/LICENSE
 * @package yii2-cms
 * @version 1.0.0
 */

namespace gromver\cmf\frontend\modules\tag\components;

use gromver\cmf\common\models\MenuItem;
use gromver\cmf\common\models\Tag;
use gromver\cmf\frontend\behaviors\MenuUrlRuleBehavior;

/**
 * Class TagMenuUrlRuleBehavior
 * @package yii2-cms
 * @author Gayazov Roman <gromver5@gmail.com>
 */
class TagMenuUrlRuleBehavior extends MenuUrlRuleBehavior
{
    /**
     * @inheritdoc
     */
    public function parseRequest($event)
    {
        if($event->menuRoute=='cmf/tag/default/index') {
            if(preg_match('/^\d+$/', $event->requestRoute))
                $event->resolve(['cmf/tag/default/view', ['id'=>$event->requestRoute]]);
            else
                $event->resolve(['cmf/tag/default/view', ['id'=>Tag::find()->select('id')->where(['alias' => $event->requestRoute, 'language' => \Yii::$app->language])->scalar()]]);
        }
    }

    /**
     * @inheritdoc
     */
    public function createUrl($event)
    {
        if($event->requestRoute=='cmf/tag/default/view' && isset($event->requestParams['id'])) {
            if($path = $event->menuMap->getMenuPathByRoute('cmf/tag/default/index')) {
                $path .= '/' . (isset($event->requestParams['alias']) ? $event->requestParams['alias'] : $event->requestParams['id']);
                unset($event->requestParams['id'], $event->requestParams['alias']);
                $event->resolve(MenuItem::toRoute($path, $event->requestParams));
            }
        }
    }
}