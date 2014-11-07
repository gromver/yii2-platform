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
use gromver\cmf\backend\behaviors\TaggableBehavior;
use gromver\cmf\backend\behaviors\VersioningBehavior;
use gromver\cmf\common\interfaces\TranslatableInterface;
use gromver\cmf\common\interfaces\ViewableInterface;
use Yii;
use yii\helpers\Inflector;

/**
 * This is the model class for table "cms_page".
 * @package yii2-cmf
 * @author Gayazov Roman <gromver5@gmail.com>
 *
 * @property integer $id
 * @property string $language
 * @property string $title
 * @property string $alias
 * @property string $preview_text
 * @property string $detail_text
 * @property string $metakey
 * @property string $metadesc
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $status
 * @property integer $created_by
 * @property integer $updated_by
 * @property string $hits
 * @property string $lock
 */
class Page extends \yii\db\ActiveRecord implements TranslatableInterface, ViewableInterface
{
    const STATUS_PUBLISHED = 1;
    const STATUS_UNPUBLISHED = 2;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%cms_page}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['language', 'title', 'detail_text', 'status'], 'required'],
            [['preview_text', 'detail_text'], 'string'],
            [['created_at', 'updated_at', 'status', 'created_by', 'updated_by', 'hits', 'lock'], 'integer'],
            [['language'], 'string', 'max' => 7],
            [['title'], 'string', 'max' => 1024],
            [['alias', 'metakey'], 'string', 'max' => 255],
            [['metadesc'], 'string', 'max' => 2048],

            [['alias'], 'filter', 'filter'=>'trim'],
            [['alias'], 'filter', 'filter'=>function($value){
                    if(empty($value))
                        return Inflector::slug(TransliteratorHelper::process($this->title));
                    else
                        return Inflector::slug($value);
                }],
            [['alias'], 'unique', 'filter'=>function($query){
                    /** @var $query \yii\db\ActiveQuery */
                    $query->andWhere(['language'=>$this->language]);
                }],
            [['alias'], 'string', 'max' => 250],
            [['alias'], 'required', 'enableClientValidation'=>false],
            [['tags', 'versionNote', 'lock'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('menst.page', 'ID'),
            'language' => Yii::t('menst.page', 'Language'),
            'title' => Yii::t('menst.page', 'Title'),
            'alias' => Yii::t('menst.page', 'Alias'),
            'preview_text' => Yii::t('menst.page', 'Preview Text'),
            'detail_text' => Yii::t('menst.page', 'Detail Text'),
            'metakey' => Yii::t('menst.page', 'Metakey'),
            'metadesc' => Yii::t('menst.page', 'Metadesc'),
            'created_at' => Yii::t('menst.page', 'Created At'),
            'updated_at' => Yii::t('menst.page', 'Updated At'),
            'status' => Yii::t('menst.page', 'Status'),
            'created_by' => Yii::t('menst.page', 'Created By'),
            'updated_by' => Yii::t('menst.page', 'Updated By'),
            'hits' => Yii::t('menst.page', 'Hits'),
            'lock' => Yii::t('menst.page', 'Lock'),
        ];
    }

    public function behaviors()
    {
        return [
            \yii\behaviors\TimestampBehavior::className(),
            \yii\behaviors\BlameableBehavior::className(),
            TaggableBehavior::className(),
            [
                'class' => VersioningBehavior::className(),//todo затестить чекаут в версию с уже занятым алиасом
                'attributes' => ['title', 'alias', 'preview_text', 'detail_text', 'metakey', 'metadesc']
            ],
        ];
    }

    private static $_statuses = [
        self::STATUS_PUBLISHED => 'Published',
        self::STATUS_UNPUBLISHED => 'Unpublished',
    ];

    public static function statusLabels()
    {
        return array_map(function($label) {
                return Yii::t('menst.page', $label);
            }, self::$_statuses);
    }

    public function getStatusLabel($status=null)
    {
        if ($status === null) {
            return Yii::t('menst.page', self::$_statuses[$this->status]);
        }
        return Yii::t('menst.page', self::$_statuses[$status]);
    }

    public function optimisticLock()
    {
        return 'lock';
    }

    //translatable interface
    public function getTranslations()
    {
        return self::hasMany(self::className(), ['alias'=>'alias'])->indexBy('language');
    }

    public function getLanguage()
    {
        return $this->language;
    }

    //LinkableItem Interface
    /**
     * @inheritdoc
     */
    public function getViewLink()
    {
        return ['/cmf/page/default/view', 'id'=>$this->id/*, 'alias'=>$this->alias*/];
    }
    /**
     * @inheritdoc
     */
    public static function viewLink($model)
    {
        return ['/cmf/page/default/view', 'id'=>$model['id']/*, 'alias'=>$model['alias']*/];
    }

    public function extraFields()
    {
        return [
            'published',
            'tags' => function($model, $field) {
                    return array_values(array_map(function($tag) {
                        return $tag->title;
                    }, $model->tags));
                },
            'text' => function($model, $field) {
                    /** @var self $model */
                    return strip_tags($model->preview_text . ' ' . $model->detail_text);
                },
            'date' => function($model, $field) {
                    /** @var self $model */
                    return date(DATE_ISO8601, $model->updated_at);
                },
        ];
    }

    public function getPublished()
    {
        return $this->status == self::STATUS_PUBLISHED;
    }
}
