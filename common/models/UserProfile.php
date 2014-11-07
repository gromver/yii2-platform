<?php
/**
 * @link https://github.com/gromver/yii2-cmf.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/gromver/yii2-cmf/blob/master/LICENSE
 * @package yii2-cmf
 * @version 1.0.0
 */

namespace gromver\cmf\common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "cms_user_profile".
 * @package yii2-cmf
 * @author Gayazov Roman <gromver5@gmail.com>
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $name
 * @property string $surname
 * @property string $patronymic
 * @property string $phone
 * @property string $work_phone
 * @property string $email
 * @property string $work_email
 * @property string $address
 *
 * @property User $user
 */
class UserProfile extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%cms_user_profile}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id'], 'integer'],
            [['name', 'surname', 'patronymic'], 'string', 'max' => 64],
            [['phone', 'work_phone'], 'string', 'max' => 20],
            [['email', 'work_email'], 'string', 'max' => 255],
            [['email', 'work_email'], 'email'],
            [['address'], 'string', 'max' => 1024]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('gromver.cmf', 'ID'),
            'user_id' => Yii::t('gromver.cmf', 'User ID'),
            'name' => Yii::t('gromver.cmf', 'Name'),
            'surname' => Yii::t('gromver.cmf', 'Surname'),
            'patronymic' => Yii::t('gromver.cmf', 'Patronymic'),
            'phone' => Yii::t('gromver.cmf', 'Phone'),
            'work_phone' => Yii::t('gromver.cmf', 'Work Phone'),
            'email' => Yii::t('gromver.cmf', 'Email'),
            'work_email' => Yii::t('gromver.cmf', 'Work Email'),
            'address' => Yii::t('gromver.cmf', 'Address'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id'])->inverseOf('profile');
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['update'] = ['!user_id', 'name', 'surname', 'patronymic', 'phone', 'work_phone', 'email', 'work_email', 'address'];

        return $scenarios;
    }
}
