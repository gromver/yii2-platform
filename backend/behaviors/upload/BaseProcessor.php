<?php
/**
 * @link https://github.com/gromver/yii2-cms.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/gromver/yii2-cmf/blob/master/LICENSE
 * @package yii2-cms
 * @version 1.0.0
 */

namespace gromver\cmf\backend\behaviors\upload;

use yii\base\Object;

/**
 * Class BaseProcessor
 * @package yii2-cms
 * @author Gayazov Roman <gromver5@gmail.com>
 */
abstract class BaseProcessor extends Object {
    public function process($filePath) {}
}