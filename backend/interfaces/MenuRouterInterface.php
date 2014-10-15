<?php
/**
 * @link https://github.com/menst/yii2-cms.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/menst/yii2-cms/blob/master/LICENSE
 * @package yii2-cms
 * @version 1.0.0
 */

namespace menst\cms\backend\interfaces;

/**
 * Interface MenuRouterInterface
 * @package yii2-cms
 * @author Gayazov Roman <m.e.n.s.t@yandex.ru>
 */
interface MenuRouterInterface
{
    /**
     * @return array
     * @see \menst\cms\backend\widgets\Routers
     */
    public function getMenuRoutes();
}