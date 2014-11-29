<?php
/**
 * @link https://github.com/gromver/yii2-cmf.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/gromver/yii2-grom/blob/master/LICENSE
 * @package yii2-cmf
 * @version 1.0.0
 */

namespace gromver\platform\frontend\modules\tag\components;


use gromver\platform\frontend\components\MenuRouter;
use gromver\platform\common\models\MenuItem;
use gromver\platform\common\models\Tag;

/**
 * Class MenuRouterTag
 * @package yii2-cmf
 * @author Gayazov Roman <gromver5@gmail.com>
 */
class MenuRouterTag extends MenuRouter {
    public function parseUrlRules()
    {
        return [
            [
                'menuRoute' => 'grom/tag/default/index',
                'handler' => 'parseTagCloud'
            ],
        ];
    }

    /**
     * @return array
     */
    public function createUrlRules()
    {
        return [
            [
                'requestRoute' => 'grom/tag/default/view',
                'requestParams' => ['id'],
                'handler' => 'createTagItems'
            ],
        ];
    }

    public function parseTagCloud($requestInfo)
    {
        if (preg_match('/^\d+$/', $requestInfo->requestRoute)) {
            return ['grom/tag/default/view', ['id' => $requestInfo->requestRoute]];
        } else {
            return ['grom/tag/default/view', ['id' => Tag::find()->select('id')->where(['alias' => $requestInfo->requestRoute])->scalar()]];
        }
    }

    public function createTagItems($requestInfo)
    {
        if($path = $requestInfo->menuMap->getMenuPathByRoute('grom/tag/default/index')) {
            $path .= '/' . (isset($requestInfo->requestParams['alias']) ? $requestInfo->requestParams['alias'] : $requestInfo->requestParams['id']);
            unset($requestInfo->requestParams['id'], $requestInfo->requestParams['alias']);
            return MenuItem::toRoute($path, $requestInfo->requestParams);
        }
    }
}