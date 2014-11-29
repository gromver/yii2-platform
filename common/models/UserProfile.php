<?php
/**
 * @link https://github.com/gromver/yii2-cmf.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/gromver/yii2-grom/blob/master/LICENSE
 * @package yii2-cmf
 * @version 1.0.0
 */

namespace gromver\platform\common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "grom_user_profile".
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
        return '{{%grom_user_profile}}';
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
            'id' => Yii::t('gromver.platform', 'ID'),
            'user_id' => Yii::t('gromver.platform', 'User ID'),
            'name' => Yii::t('gromver.platform', 'Name'),
            'surname' => Yii::t('gromver.platform', 'Surname'),
            'patronymic' => Yii::t('gromver.platform', 'Patronymic'),
            'phone' => Yii::t('gromver.platform', 'Phone'),
            'work_phone' => Yii::t('gromver.platform', 'Work Phone'),
            'email' => Yii::t('gromver.platform', 'Email'),
            'work_email' => Yii::t('gromver.platform', 'Work Email'),
            'address' => Yii::t('gromver.platform', 'Address'),
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
