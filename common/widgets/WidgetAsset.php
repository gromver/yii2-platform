<?php
/**
 * @link https://github.com/gromver/yii2-cmf.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/gromver/yii2-grom/blob/master/LICENSE
 * @package yii2-cmf
 * @version 1.0.0
 */

namespace gromver\platform\common\widgets;

use yii\web\AssetBundle;

/**
 * Class WidgetAsset
 * @package yii2-cmf
 * @author Gayazov Roman <gromver5@gmail.com>
 */
class WidgetAsset extends AssetBundle {
    public $sourcePath = '@gromver/platform/common/widgets/assets';
    public $css = [
        'css/style.css'
    ];
}