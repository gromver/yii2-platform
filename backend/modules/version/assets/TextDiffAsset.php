<?php
/**
 * @link https://github.com/gromver/yii2-cmf.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/gromver/yii2-grom/blob/master/LICENSE
 * @package yii2-cmf
 * @version 1.0.0
 */

namespace gromver\platform\backend\modules\version\assets;

/**
 * Class TextDiffAsset
 * @package yii2-cmf
 * @author Gayazov Roman <gromver5@gmail.com>
 */
class TextDiffAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@gromver/platform/backend/modules/version/assets/PrettyTextDiff';
    public $js = [
        'diff_match_patch.js',
        'jquery.pretty-text-diff.min.js',
    ];
    public $css = [
        'style.css'
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}