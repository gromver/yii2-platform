<?php
/**
 * @link https://github.com/gromver/yii2-cmf.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/gromver/yii2-cmf/blob/master/LICENSE
 * @package yii2-cmf
 * @version 1.0.0
 */

namespace gromver\cmf\common\models;

use dosamigos\transliterator\TransliteratorHelper;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\helpers\Inflector;

/**
 * This is the model class for table "cms_menu_type".
 * @package yii2-cmf
 * @author Gayazov Roman <gromver5@gmail.com>
 *
 * @property integer $id
 * @property string $title
 * @property string $alias
 * @property string $note
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property string $lock
 *
 * @property MenuItem[] $items
 */
class MenuType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%cms_menu_type}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'created_at', 'updated_at', 'created_by', 'updated_by', 'lock'], 'integer'],
            [['title'], 'string', 'max' => 1024],
            [['alias'], 'filter', 'filter' => 'trim'],
            [['alias'], 'filter', 'filter' => function($value){
                    if(empty($value)) {
                        return Inflector::slug(TransliteratorHelper::process($this->title));
                    } else {
                        return Inflector::slug($value);
                    }
                }],
            [['alias'], 'unique'],
            [['alias'], 'required', 'enableClientValidation' => false],
            [['alias', 'note'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('gromver.cmf', 'ID'),
            'status' => Yii::t('gromver.cmf', 'Status'),
            'title' => Yii::t('gromver.cmf', 'Title'),
            'alias' => Yii::t('gromver.cmf', 'Alias'),
            'note' => Yii::t('gromver.cmf', 'Note'),
            'created_at' => Yii::t('gromver.cmf', 'Created At'),
            'updated_at' => Yii::t('gromver.cmf', 'Updated At'),
            'created_by' => Yii::t('gromver.cmf', 'Created By'),
            'updated_by' => Yii::t('gromver.cmf', 'Updated By'),
            'lock' => Yii::t('gromver.cmf', 'Lock'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            BlameableBehavior::className(),
        ];
    }

    public function optimisticLock()
    {
        return 'lock';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItems()
    {
        return $this->hasMany(MenuItem::className(), ['menu_type_id'=>'id'])->orderBy('lft');
    }
}
