<?php
/**
 * @link https://github.com/gromver/yii2-cmf.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/gromver/yii2-grom/blob/master/LICENSE
 * @package yii2-cmf
 * @version 1.0.0
 */

namespace gromver\platform\backend\assets;


use yii\web\AssetBundle;

/**
 * Class CkeditorHighlightAsset
 * @package yii2-cmf
 * @author Gayazov Roman <gromver5@gmail.com>
 */
class CkeditorHighlightAsset extends AssetBundle {
    public $sourcePath = '@gromver/platform/backend/assets/ckeditor';
    public $js = ['plugins/codesnippet/lib/highlight/highlight.pack.js'];
    public $css = ['plugins/codesnippet/lib/highlight/styles/default.css'];

    public function init() {
        parent::init();
        \Yii::$app->view->registerJs('hljs.initHighlightingOnLoad();');
    }
} 