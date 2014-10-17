<?php
/**
 * @link https://github.com/menst/yii2-cms.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/menst/yii2-cms/blob/master/LICENSE
 * @package yii2-cms
 * @version 1.0.0
 */

namespace menst\cms\frontend\modules\tag\components;

use menst\cms\common\models\MenuItem;
use menst\cms\common\models\Tag;
use menst\cms\frontend\behaviors\MenuUrlRuleBehavior;

/**
 * Class TagMenuUrlRuleBehavior
 * @package yii2-cms
 * @author Gayazov Roman <m.e.n.s.t@yandex.ru>
 */
class TagMenuUrlRuleBehavior extends MenuUrlRuleBehavior
{
    /**
     * @inheritdoc
     */
    public function parseRequest($event)
    {
        if($event->menuRoute=='cms/tag/default/index') {
            if(preg_match('/^\d+$/', $event->requestRoute))
                $event->resolve(['cms/tag/default/view', ['id'=>$event->requestRoute]]);
            else
                $event->resolve(['cms/tag/default/view', ['id'=>Tag::find()->select('id')->where(['alias' => $event->requestRoute, 'language' => \Yii::$app->language])->scalar()]]);
        }
    }

    /**
     * @inheritdoc
     */
    public function createUrl($event)
    {
        if($event->requestRoute=='cms/tag/default/view' && isset($event->requestParams['id'])) {
            if($path = $event->menuMap->getMenuPathByRoute('cms/tag/default/index')) {
                $path .= '/' . (isset($event->requestParams['alias']) ? $event->requestParams['alias'] : $event->requestParams['id']);
                unset($event->requestParams['id'], $event->requestParams['alias']);
                $event->resolve(MenuItem::toRoute($path, $event->requestParams));
            }
        }
    }
}