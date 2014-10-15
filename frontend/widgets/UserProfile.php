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
use menst\models\ObjectModel;
use yii\base\InvalidParamException;

/**
 * Class UserProfile
 * @package yii2-cms
 * @author Gayazov Roman <m.e.n.s.t@yandex.ru>
 */
class UserProfile extends Widget {
    /**
     * @ignore
     */
    public $model;
    /**
     * @type list
     * @multiple
     * @items params
     * @empty Все
     */
    public $params;

    public function init()
    {
        parent::init();

        if (!isset($this->model)) {
            try {
                $attributes = \Yii::$app->user->getParamsArray();
            } catch(InvalidParamException $e) {
                $attributes = [];
            }

            $this->model = self::model();
            $this->model->setAttributes($attributes);
        }

        $visibleParams = $this->getVisibleParams();
        foreach ($this->model->attributes() as $attribute) {
            if (!in_array($attribute, $visibleParams)) {
                $this->model->undefineAttribute($attribute);
            }
        }
    }

    protected function launch()
    {
        echo $this->render('user/profile', [
            'model' => $this->model,
        ]);
    }

    private static function model()
    {
        return new ObjectModel(\Yii::$app->getModule('cms/user')->userParamsClass);
    }

    public static function params()
    {
        return self::model()->attributeLabels();
    }

    public function getVisibleParams()
    {
        return is_array($this->params) && count($this->params) ? $this->params : $this->model->attributes();
    }
}