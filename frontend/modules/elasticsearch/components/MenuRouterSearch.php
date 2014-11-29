<?php
/**
 * @link https://github.com/gromver/yii2-cmf.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/gromver/yii2-grom/blob/master/LICENSE
 * @package yii2-cmf
 * @version 1.0.0
 */

namespace gromver\platform\frontend\modules\elasticsearch\components;


use gromver\platform\frontend\components\MenuRouter;
use gromver\platform\common\models\MenuItem;

/**
 * Class MenuRouterSearch
 * @package yii2-cmf
 * @author Gayazov Roman <gromver5@gmail.com>
 */
class MenuRouterSearch extends MenuRouter {
    /**
     * @return array
     */
    public function createUrlRules()
    {
        return [
            [
                'requestRoute' => 'grom/search/default/index',
                'handler' => 'createSearch'
            ],
        ];
    }

    public function createSearch($requestInfo)
    {
        if($path = $requestInfo->menuMap->getMenuPathByRoute('grom/search/default/index')) {
            return MenuItem::toRoute($path, $requestInfo->requestParams);
        }
    }
}