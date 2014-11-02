<?php
/**
 * @link https://github.com/menst/yii2-cms.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/menst/yii2-cms/blob/master/LICENSE
 * @package yii2-cms
 * @version 1.0.0
 */

namespace menst\cms\backend\assets;


use yii\web\AssetBundle;

/**
 * Class CkeditorHighlightAsset
 * @package yii2-cms
 * @author Gayazov Roman <m.e.n.s.t@yandex.ru>
 */
class CkeditorHighlightAsset extends AssetBundle {
    public $sourcePath = '@menst/cms/backend/assets/ckeditor';
    public $js = ['plugins/codesnippet/lib/highlight/highlight.pack.js'];
    public $css = ['plugins/codesnippet/lib/highlight/styles/default.css'];

    public function init() {
        parent::init();
        \Yii::$app->view->registerJs('hljs.initHighlightingOnLoad();');
    }
} 