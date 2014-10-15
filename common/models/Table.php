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
use yii\base\Event;
use yii\caching\ExpressionDependency;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "cms_table".
 * @package yii2-cms
 * @author Gayazov Roman <m.e.n.s.t@yandex.ru>
 *
 * @property string $id
 * @property integer $timestamp
 */
class Table extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%cms_table}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'timestamp'], 'required'],
            [['timestamp'], 'integer'],
            [['id'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('menst.cms', 'ID'),
            'timestamp' => Yii::t('menst.cms', 'Timestamp'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    self::EVENT_BEFORE_INSERT => 'timestamp',
                    self::EVENT_BEFORE_UPDATE => 'timestamp',
                ]
            ]
        ];
    }

    /**
     * @param $table string table name
     * @return mixed
     */
    public static function timestamp($table)
    {
        return ArrayHelper::getValue(self::getData(), self::getDb()->getSchema()->getRawTableName($table), ['timestamp' => 0])['timestamp'];
    }

    /**
     * @param $table string table name
     * @return ExpressionDependency
     */
    public static function dependency($table)
    {
        return new ExpressionDependency(['expression' => self::className().'::timestamp("'.$table.'")']);
    }

    private static $data;

    private static function getData()
    {
        if(!isset(self::$data)) {
            self::$data = self::getDb()->noCache(function($db){
                    return self::find()->indexBy('id')->asArray()->all();
                });
        }

        return self::$data;
    }

    private static function clearData()
    {
        self::$data = null;
    }

    public static function bootstrap()
    {
        static $initialized;
        if(!isset($initialized)) {
            $initialized = true;
            Event::on(ActiveRecord::className(), ActiveRecord::EVENT_AFTER_DELETE, [self::className(), 'updateState']);
            Event::on(ActiveRecord::className(), ActiveRecord::EVENT_AFTER_INSERT, [self::className(), 'updateState']);
            Event::on(ActiveRecord::className(), ActiveRecord::EVENT_AFTER_UPDATE, [self::className(), 'updateState']);
        }
    }

    /**
     * @param $event Event
     */
    public static function updateState($event)
    {
        self::clearData();

        self::getDb()->createCommand("INSERT INTO ".self::tableName()." (id, timestamp) VALUES (:id, :timestamp) ON DUPLICATE KEY UPDATE [[timestamp]]=:timestamp",
            [
                ':id' => self::getDb()->getSchema()->getRawTableName($event->sender->tableName()),
                ':timestamp' => time()
            ]
        )->execute();
    }
}
