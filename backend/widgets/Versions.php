<?php
/**
 * @link https://github.com/gromver/yii2-cmf.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/gromver/yii2-cmf/blob/master/LICENSE
 * @package yii2-cmf
 * @version 1.0.0
 */

namespace gromver\cmf\backend\widgets;


use gromver\widgets\ModalIFrame;
use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\bootstrap\Modal;
use Yii;
use yii\helpers\Html;

/**
 * Class Versions
 * @package yii2-cmf
 * @author Gayazov Roman <gromver5@gmail.com>
 */
class Versions extends Widget
{
    /**
     * @var \yii\db\ActiveRecord
     */
    public $model;

    public function init()
    {
        if (!is_object($this->model)) {
            throw new InvalidConfigException(__CLASS__ . '::model must be set.');
        }
    }

    public function run()
    {
        return ModalIFrame::widget([
            'modalOptions' => [
                'header' => Yii::t('gromver.cmf', 'Item Versions Manager - "{title}" (ID:{id})', ['title' => $this->model->title, 'id' => $this->model->getPrimaryKey()]),  //todo возможно ввести какойнибуть интерфейс для тайтла
                'size' => Modal::SIZE_LARGE,
            ],
            'buttonContent' => Html::a('<i class="glyphicon glyphicon-hdd"></i> ' . Yii::t('gromver.cmf', 'Versions'),
                ['/cmf/version/default/item', 'item_id' => $this->model->getPrimaryKey(), 'item_class' => $this->model->className()], [
                    'class'=>'btn btn-default btn-sm',
                ]),
        ]);
    }
} 