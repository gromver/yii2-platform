<?php
/**
 * @link https://github.com/gromver/yii2-cmf.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/gromver/yii2-cmf/blob/master/LICENSE
 * @package yii2-cmf
 * @version 1.0.0
 */

namespace gromver\cmf\backend\widgets;

use yii\bootstrap\Widget;

/**
 * Class CmfPanel
 * @package yii2-cmf
 * @author Gayazov Roman <gromver5@gmail.com>
 */
class CmfPanel extends Widget {
    public $layout = 'cmf/panelDefault';

    public function run()
    {
        return $this->render($this->layout, [
            'widget' => $this
        ]);
    }
}