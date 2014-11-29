<?php
/**
 * @link https://github.com/gromver/yii2-cmf.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/gromver/yii2-grom/blob/master/LICENSE
 * @package yii2-cmf
 * @version 1.0.0
 */

namespace gromver\platform\frontend\widgets;

use yii\bootstrap\Widget;

/**
 * Class PlatformPanel
 * @package yii2-cmf
 * @author Gayazov Roman <gromver5@gmail.com>
 */
class PlatformPanel extends Widget {
    public $layout = 'platform/panelDefault';

    public function run()
    {
        return $this->render($this->layout, [
            'widget' => $this
        ]);
    }
}