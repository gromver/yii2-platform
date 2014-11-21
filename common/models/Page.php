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
use gromver\cmf\common\components\UrlManager;
use gromver\cmf\common\interfaces\TranslatableInterface;
use gromver\cmf\common\interfaces\ViewableInterface;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\Inflector;

/**
 * This is the model class for table "cms_page".
 * @package yii2-cmf
 * @author Gayazov Roman <gromver5@gmail.com>
 *
 * @property integer $id
 * @property integer $translation_id
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
 *
 * @property Page[] $translations
 */
class Page extends ActiveRecord implements TranslatableInterface, ViewableInterface
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

            [['alias'], 'filter', 'filter' => 'trim'],
            [['alias'], 'filter', 'filter' => function($value){
                    if (empty($value)) {
                        return Inflector::slug(TransliteratorHelper::process($this->title));
                    } else {
                        return Inflector::slug($value);
                    }
                }],
            [['alias'], 'unique', 'filter' => function($query){
                /** @var $query \yii\db\ActiveQuery */
                $query->andWhere(['language' => $this->language]);
            }],
            [['alias'], 'string', 'max' => 250],
            [['alias'], 'required', 'enableClientValidation' => false],
            [['translation_id'], 'unique', 'filter' => function($query) {
                /** @var $query \yii\db\ActiveQuery */
                $query->andWhere(['language' => $this->language]);
            }, 'message' => Yii::t('gromver.cmf', 'Локализация ({language}) для записи (ID:{id}) уже существует.', ['language' => $this->language, 'id' => $this->translation_id])],
            [['tags', 'versionNote', 'lock'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('gromver.cmf', 'ID'),
            'translation_id' => Yii::t('gromver.cmf', 'Translation ID'),
            'language' => Yii::t('gromver.cmf', 'Language'),
            'title' => Yii::t('gromver.cmf', 'Title'),
            'alias' => Yii::t('gromver.cmf', 'Alias'),
            'preview_text' => Yii::t('gromver.cmf', 'Preview Text'),
            'detail_text' => Yii::t('gromver.cmf', 'Detail Text'),
            'metakey' => Yii::t('gromver.cmf', 'Meta keywords'),
            'metadesc' => Yii::t('gromver.cmf', 'Meta description'),
            'created_at' => Yii::t('gromver.cmf', 'Created At'),
            'updated_at' => Yii::t('gromver.cmf', 'Updated At'),
            'status' => Yii::t('gromver.cmf', 'Status'),
            'tags' => Yii::t('gromver.cmf', 'Tags'),
            'created_by' => Yii::t('gromver.cmf', 'Created By'),
            'updated_by' => Yii::t('gromver.cmf', 'Updated By'),
            'hits' => Yii::t('gromver.cmf', 'Hits'),
            'lock' => Yii::t('gromver.cmf', 'Lock'),
            'versionNote' => Yii::t('gromver.cmf', 'Version Note')
        ];
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            BlameableBehavior::className(),
            TaggableBehavior::className(),
            [
                'class' => VersioningBehavior::className(),//todo затестить чекаут в версию с уже занятым алиасом
                'attributes' => ['title', 'alias', 'preview_text', 'detail_text', 'metakey', 'metadesc']
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        if ($insert && $this->translation_id === null) {
            $this->updateAttributes([
                'translation_id' => $this->id
            ]);
        }

        parent::afterSave($insert, $changedAttributes);
    }


    private static $_statuses = [
        self::STATUS_PUBLISHED => 'Published',
        self::STATUS_UNPUBLISHED => 'Unpublished',
    ];

    public static function statusLabels()
    {
        return array_map(function($label) {
                return Yii::t('gromver.cmf', $label);
            }, self::$_statuses);
    }

    public function getStatusLabel($status=null)
    {
        if ($status === null) {
            return Yii::t('gromver.cmf', self::$_statuses[$this->status]);
        }
        return Yii::t('gromver.cmf', self::$_statuses[$status]);
    }

    public function optimisticLock()
    {
        return 'lock';
    }

    //translatable interface
    public function getTranslations()
    {
        return self::hasMany(self::className(), ['translation_id' => 'translation_id'])->indexBy('language');
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
        return ['/cmf/page/default/view', 'id' => $this->id, UrlManager::LANGUAGE_PARAM => $this->language];
    }
    /**
     * @inheritdoc
     */
    public static function viewLink($model)
    {
        return ['/cmf/page/default/view', 'id' => $model['id'], UrlManager::LANGUAGE_PARAM => $model['language']];
    }

    public function extraFields()
    {
        return [
            'published',
            'tags' => function($model) {
                    return array_values(array_map(function($tag) {
                        return $tag->title;
                    }, $model->tags));
                },
            'text' => function($model) {
                    /** @var self $model */
                    return strip_tags($model->preview_text . ' ' . $model->detail_text);
                },
            'date' => function($model) {
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
