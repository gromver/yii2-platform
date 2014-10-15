<?php
/**
 * @link https://github.com/menst/yii2-cms.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/menst/yii2-cms/blob/master/LICENSE
 * @package yii2-cms
 * @version 1.0.0
 */

namespace menst\cms\frontend\widgets;

use menst\cms\common\models\LoginForm;
use menst\cms\common\widgets\Widget;

/**
 * Class AuthLogin
 * @package yii2-cms
 * @author Gayazov Roman <m.e.n.s.t@yandex.ru>
 */
class AuthLogin extends Widget {
    /**
     * @ignore
     * @var LoginForm
     */
    public $model;

    public function init()
    {
        parent::init();

        $this->setShowPanel(false);

        if (!isset($this->model)) {
            $this->model = new LoginForm();
        }
    }

    protected function launch()
    {
        echo $this->render('auth/login', [
            'model' => $this->model
        ]);
    }
}