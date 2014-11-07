<?php
/**
 * @link https://github.com/gromver/yii2-cms.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/gromver/yii2-cmf/blob/master/LICENSE
 * @package yii2-cms
 * @version 1.0.0
 */

namespace gromver\cmf\common\models;

use Yii;

/**
 * This is the model class for table "cms_user_profile".
 * @package yii2-cms
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
class UserProfile extends \yii\db\ActiveRecord
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
            'id' => Yii::t('menst.cms', 'ID'),
            'user_id' => Yii::t('menst.cms', 'User ID'),
            'name' => Yii::t('menst.cms', 'Name'),
            'surname' => Yii::t('menst.cms', 'Surname'),
            'patronymic' => Yii::t('menst.cms', 'Patronymic'),
            'phone' => Yii::t('menst.cms', 'Phone'),
            'work_phone' => Yii::t('menst.cms', 'Work Phone'),
            'email' => Yii::t('menst.cms', 'Email'),
            'work_email' => Yii::t('menst.cms', 'Work Email'),
            'address' => Yii::t('menst.cms', 'Address'),
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
