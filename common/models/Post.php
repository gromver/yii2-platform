<?php
/**
 * @link https://github.com/menst/yii2-cms.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/menst/yii2-cms/blob/master/LICENSE
 * @package yii2-cms
 * @version 1.0.0
 */

namespace menst\cms\common\models;

use dosamigos\transliterator\TransliteratorHelper;
use menst\cms\backend\behaviors\TaggableBehavior;
use menst\cms\backend\behaviors\upload\ThumbnailProcessor;
use menst\cms\backend\behaviors\UploadBehavior;
use menst\cms\backend\behaviors\VersioningBehavior;
use menst\cms\common\interfaces\TranslatableInterface;
use menst\cms\common\interfaces\ViewableInterface;
use Yii;
use yii\helpers\Inflector;

/**
 * This is the model class for table "cms_post".
 * @package yii2-cms
 * @author Gayazov Roman <m.e.n.s.t@yandex.ru>
 *
 * @property integer $id
 * @property integer $category_id
 * @property string $title
 * @property string $alias
 * @property string $preview_text
 * @property string $preview_image
 * @property string $detail_text
 * @property string $detail_image
 * @property string $metakey
 * @property string $metadesc
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $published_at
 * @property integer $status
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $ordering
 * @property integer $hits
 * @property integer $lock
 *
 * @property Category $category
 * @property User[] $viewers
 */
class Post extends \yii\db\ActiveRecord implements TranslatableInterface, ViewableInterface
{
    const STATUS_PUBLISHED = 1;
    const STATUS_UNPUBLISHED = 2;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%cms_post}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'category_id', 'title', 'detail_text'], 'required'],
            [['category_id', 'created_at', 'updated_at', 'status', 'created_by', 'updated_by', 'ordering', 'hits', 'lock'], 'integer'],
            [['preview_text', 'detail_text'], 'string'],
            [['title', 'preview_image', 'detail_image'], 'string', 'max' => 1024],
            [['alias', 'metakey'], 'string', 'max' => 255],
            [['metadesc'], 'string', 'max' => 2048],

            [['published_at'], 'date', 'format' => 'dd.MM.yyyy HH:mm', 'timestampAttribute' => 'published_at', 'when' => function($model) {
                    return is_string($this->published_at);
                }],
            [['published_at'], 'integer', 'enableClientValidation'=>false],
            [['alias'], 'filter', 'filter'=>'trim'],
            [['alias'], 'filter', 'filter'=>function($value){
                    if(empty($value))
                        return Inflector::slug(TransliteratorHelper::process($this->title));
                    else
                        return Inflector::slug($value);
                }],
            [['alias'], 'unique', 'filter'=>function($query){
                    /** @var $query \yii\db\ActiveQuery */
                    $query->andWhere(['category_id'=>$this->category_id]);
                }, 'message' => '{attribute} - Another article from this category has the same alias'],
            [['tags', 'versionNote'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('menst.news', 'ID'),
            'category_id' => Yii::t('menst.news', 'Category ID'),
            'title' => Yii::t('menst.news', 'Title'),
            'alias' => Yii::t('menst.news', 'Alias'),
            'preview_text' => Yii::t('menst.news', 'Preview Text'),
            'preview_image' => Yii::t('menst.news', 'Preview Image'),
            'detail_text' => Yii::t('menst.news', 'Detail Text'),
            'detail_image' => Yii::t('menst.news', 'Detail Image'),
            'metakey' => Yii::t('menst.news', 'Metakey'),
            'metadesc' => Yii::t('menst.news', 'Metadesc'),
            'created_at' => Yii::t('menst.news', 'Created At'),
            'updated_at' => Yii::t('menst.news', 'Updated At'),
            'published_at' => Yii::t('menst.news', 'Published At'),
            'status' => Yii::t('menst.news', 'Status'),
            'created_by' => Yii::t('menst.news', 'Created By'),
            'updated_by' => Yii::t('menst.news', 'Updated By'),
            'ordering' => Yii::t('menst.news', 'Ordering'),
            'hits' => Yii::t('menst.news', 'Hits'),
            'lock' => Yii::t('menst.news', 'Lock'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            \yii\behaviors\TimestampBehavior::className(),
            \yii\behaviors\BlameableBehavior::className(),
            TaggableBehavior::className(),
            [
                'class' => VersioningBehavior::className(),
                'attributes'=>['title', 'alias', 'preview_text', 'detail_text', 'metakey', 'metadesc']
            ],
            [
                'class' => UploadBehavior::className(),
                'attributes' => [
                    'detail_image'=>[
                        'fileName' => '{id}-full.#extension#'
                    ],
                    'preview_image'=>[
                        'fileName' => '{id}-thumb.#extension#',
                        'process' => ThumbnailProcessor::className()
                    ]
                ],
                'options' => [
                    'basePath' => '@frontend/web',
                    'baseUrl' => '',
                    'savePath'=>'upload/posts'
                ]
            ]
        ];
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getViewers()
    {
        return $this->hasMany(User::className(), ['id' => 'user_id'])->viaTable('{{%cms_post_viewed}}', ['post_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    private static $_statuses = [
        self::STATUS_PUBLISHED => 'Published',
        self::STATUS_UNPUBLISHED => 'Unpublished',
    ];

    public static function statusLabels()
    {
        return array_map(function($label) {
                return Yii::t('menst.news', $label);
            }, self::$_statuses);
    }

    public function getStatusLabel($status=null)
    {
        if ($status === null) {
            return Yii::t('menst.news', self::$_statuses[$this->status]);
        }
        return Yii::t('menst.news', self::$_statuses[$status]);
    }


    /**
     * @inheritdoc
     * @return PostQuery
     */
    public static function find()
    {
        return new PostQuery(get_called_class());
    }


    public function optimisticLock()
    {
        return 'lock';
    }

    public function hit()
    {
        return $this->updateAttributes(['hits'=>$this->hits + 1]);
    }

    public function getDayLink()
    {
        return ['/cms/news/post/day', 'category_id' => $this->category_id, 'year' => date('Y', $this->published_at), 'month' => date('m', $this->published_at), 'day' => date('j', $this->published_at)];
    }
    //ViewableInterface
    /**
     * @inheritdoc
     */
    public function getViewLink()
    {
        return ['/cms/news/post/view', 'id' => $this->id, 'alias' => $this->alias, 'category_id' => $this->category_id];
    }
    /**
     * @inheritdoc
     */
    public static function viewLink($model)
    {
        return ['/cms/news/post/view', 'id' => $model['id'], 'alias' => $model['alias'], 'category_id' => $model['category_id']];
    }

    //translatable interface
    public function getTranslations()
    {
        return self::hasMany(self::className(), ['alias'=>'alias'])->innerJoinWith('category', false)->andWhere(['path'=>$this->category->path])->indexBy('language');
    }

    public function getLanguage()
    {
        return $this->category->language;
    }

    public function extraFields()
    {
        return [
            'language',
            'published',
            'tags' => function($model, $field) {
                    return array_values(array_map(function($tag) {
                        return $tag->title;
                    }, $model->tags));
                },
            'text' => function($model, $field) {
                    /** @var self $model */
                    return strip_tags($model->preview_text . "\n" . $model->detail_text);
                },
            'date' => function($model, $field) {
                    /** @var self $model */
                    return date(DATE_ISO8601, $model->published_at);
                },
        ];
    }

    public function getPublished()
    {
        return $this->status == self::STATUS_PUBLISHED;
    }
}
