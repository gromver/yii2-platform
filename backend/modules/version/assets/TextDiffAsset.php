<?php
/**
 * @link https://github.com/menst/yii2-cms.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/menst/yii2-cms/blob/master/LICENSE
 * @package yii2-cms
 * @version 1.0.0
 */

namespace menst\cms\backend\modules\version\assets;

/**
 * Class TextDiffAsset
 * @package yii2-cms
 * @author Gayazov Roman <m.e.n.s.t@yandex.ru>
 */
class TextDiffAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@menst/cms/backend/modules/version/assets/PrettyTextDiff';
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