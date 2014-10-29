<?php
/**
 * @link https://github.com/menst/yii2-cms.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/menst/yii2-cms/blob/master/LICENSE
 * @package yii2-cms
 * @version 1.0.0
 */

namespace menst\cms\common\models;

use Yii;
use yii\helpers\Json;

/**
 * This is the model class for table "cms_widget_config".
 * @package yii2-cms
 * @author Gayazov Roman <m.e.n.s.t@yandex.ru>
 *
 * @property integer $id
 * @property string $widget_id
 * @property string $widget_class
 * @property string $language
 * @property string $context
 * @property string $url
 * @property string $params
 * @property integer $valid
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property string $lock
 */
class WidgetConfig extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%cms_widget_config}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['widget_id', 'widget_class'], 'required'],
            [['params'], 'string'],
            [['valid', 'created_at', 'updated_at', 'created_by', 'updated_by', 'lock'], 'integer'],
            [['widget_id'], 'string', 'max' => 50],
            [['widget_class'], 'string', 'max' => 255],
            [['context', 'url'], 'string', 'max' => 1024],
            [['language'], 'default', 'value' => function ($model, $attribute) {
                return Yii::$app->language;
            }],
            [['language'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('menst.cms', 'ID'),
            'widget_id' => Yii::t('menst.cms', 'Widget ID'),
            'widget_class' => Yii::t('menst.cms', 'Widget Class'),
            'language' => Yii::t('menst.cms', 'Language'),
            'context' => Yii::t('menst.cms', 'Context'),
            'url' => Yii::t('menst.cms', 'Url'),
            'params' => Yii::t('menst.cms', 'Params'),
            'valid' => Yii::t('menst.cms', 'Valid'),
            'created_at' => Yii::t('menst.cms', 'Created At'),
            'updated_at' => Yii::t('menst.cms', 'Updated At'),
            'created_by' => Yii::t('menst.cms', 'Created By'),
            'updated_by' => Yii::t('menst.cms', 'Updated By'),
            'lock' => Yii::t('menst.cms', 'Lock'),
        ];
    }

    public function behaviors()
    {
        return [
            \yii\behaviors\TimestampBehavior::className(),
            \yii\behaviors\BlameableBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function getParamsArray()
    {
        return Json::decode($this->params);
    }

    public function setParamsArray($value)
    {
        $this->params = Json::encode($value);
    }

}
