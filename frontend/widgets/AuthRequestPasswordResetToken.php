<?php
/**
 * @link https://github.com/gromver/yii2-cmf.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/gromver/yii2-grom/blob/master/LICENSE
 * @package yii2-cmf
 * @version 1.0.0
 */

namespace gromver\platform\frontend\widgets;

use gromver\platform\common\widgets\Widget;
use gromver\platform\common\models\User;

/**
 * Class AuthRequestPasswordResetToken
 * @package yii2-cmf
 * @author Gayazov Roman <gromver5@gmail.com>
 */
class AuthRequestPasswordResetToken extends Widget {
    /**
     * @ignore
     * @var User
     */
    public $model;

    public function init()
    {
        parent::init();

        $this->setShowPanel(false);

        if (!isset($this->model)) {
            $this->model = new User();
        }
    }

    protected function launch()
    {
        echo $this->render('auth/requestPasswordResetToken', [
            'model' => $this->model
        ]);
    }
}