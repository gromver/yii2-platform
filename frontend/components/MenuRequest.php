<?php
/**
 * @link https://github.com/gromver/yii2-cmf.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/gromver/yii2-cmf/blob/master/LICENSE
 * @package yii2-cmf
 * @version 1.0.0
 */

namespace gromver\cmf\frontend\components;


use yii\base\Object;

/**
 * Class MenuRequest
 * @package yii2-cmf
 * @author Gayazov Roman <gromver5@gmail.com>
 */
class MenuRequest extends Object {
    public $menuMap;
    public $menuRoute;
    public $menuParams;
    public $requestRoute;
    public $requestParams;
}