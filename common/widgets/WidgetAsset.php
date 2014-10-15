<?php
/**
 * @link https://github.com/menst/yii2-cms.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/menst/yii2-cms/blob/master/LICENSE
 * @package yii2-cms
 * @version 1.0.0
 */

namespace menst\cms\common\widgets;

use yii\web\AssetBundle;

/**
 * Class WidgetAsset
 * @package yii2-cms
 * @author Gayazov Roman <m.e.n.s.t@yandex.ru>
 */
class WidgetAsset extends AssetBundle {
    public $sourcePath = '@menst/cms/common/widgets/assets';
    public $css = [
        'css/style.css'
    ];
}