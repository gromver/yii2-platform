<?php
/**
 * @link https://github.com/gromver/yii2-cmf.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/gromver/yii2-cmf/blob/master/LICENSE
 * @package yii2-cmf
 * @version 1.0.0
 */

namespace gromver\cmf\common\interfaces;

/**
 * Interface ViewableInterface
 * @package yii2-cmf
 * @author Gayazov Roman <gromver5@gmail.com>
 */
interface ViewableInterface {
    /**
     * @return array | string route
     */
    public function getViewLink();

    /**
     * @param $model
     * @return array | string route
     */
    public static function viewLink($model);
} 