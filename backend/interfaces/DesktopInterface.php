<?php
/**
 * @link https://github.com/gromver/yii2-cms.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/gromver/yii2-cmf/blob/master/LICENSE
 * @package yii2-cms
 * @version 1.0.0
 */

namespace gromver\cmf\backend\interfaces;

/**
 * Interface DesktopInterface
 * @package yii2-cms
 * @author Gayazov Roman <gromver5@gmail.com>
 */
interface DesktopInterface
{
    /**
     * @return array
     * @see \gromver\cmf\backend\widgets\Desktop
     */
    public function getDesktopItem();
}