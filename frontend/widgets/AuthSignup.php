<?php
/**
 * @link https://github.com/menst/yii2-cms.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/menst/yii2-cms/blob/master/LICENSE
 * @package yii2-cms
 * @version 1.0.0
 */

namespace menst\cms\frontend\widgets;

use menst\cms\common\widgets\Widget;
use menst\cms\common\models\User;

/**
 * Class AuthSignup
 * @package yii2-cms
 * @author Gayazov Roman <m.e.n.s.t@yandex.ru>
 */
class AuthSignup extends Widget {
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
        echo $this->render('auth/signup', [
            'model' => $this->model
        ]);
    }
}