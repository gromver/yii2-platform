<?php
/**
 * @link https://github.com/gromver/yii2-cmf.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/gromver/yii2-cmf/blob/master/LICENSE
 * @package yii2-cmf
 * @version 1.0.0
 */

namespace gromver\cmf\backend\interfaces;

/**
 * Interface MenuRouterInterface
 * @package yii2-cmf
 * @author Gayazov Roman <gromver5@gmail.com>
 */
interface MenuRouterInterface
{
    /**
     * @return array
     * @see \gromver\cmf\backend\widgets\Routers
     */
    public function getMenuRoutes();
}