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
use menst\cms\backend\behaviors\NestedSetBehavior;
use menst\cms\backend\behaviors\TaggableBehavior;
use menst\cms\backend\behaviors\upload\ThumbnailProcessor;
use menst\cms\backend\behaviors\UploadBehavior;
use menst\cms\backend\behaviors\VersioningBehavior;
use menst\cms\common\interfaces\TranslatableInterface;
use menst\cms\common\interfaces\ViewableInterface;
use Yii;
use yii\helpers\Html;
use yii\helpers\Inflector;

/**
 * This is the model class for table "cms_category".
 * @package yii2-cms
 * @author Gayazov Roman <m.e.n.s.t@yandex.ru>
 *
 * @property integer $id
 * @property integer $parent_id
 * @property string $language
 * @property string $title
 * @property string $alias
 * @property string $path
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
 * @property integer $lft
 * @property integer $rgt
 * @property integer $level
 * @property integer $ordering
 * @property integer $hits
 * @property integer $lock
 *
 * @property Post[] $posts
 */
class Category extends \yii\db\ActiveRecord implements TranslatableInterface, ViewableInterface
{
    const STATUS_PUBLISHED = 1;
    const STATUS_UNPUBLISHED = 2;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%cms_category}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id', 'created_at', 'updated_at', 'status', 'created_by', 'updated_by', 'lft', 'rgt', 'level', 'ordering', 'hits', 'lock'], 'integer'],
            [['preview_text', 'detail_text'], 'string'],
            [['title', 'preview_image', 'detail_image'], 'string', 'max' => 1024],
            [['alias', 'metakey'], 'string', 'max' => 255],
            [['path', 'metadesc'], 'string', 'max' => 2048],

            [['published_at'], 'date', 'format' => 'dd.MM.yyyy HH:mm', 'timestampAttribute' => 'published_at', 'when' => function($model) {
                    return is_string($this->published_at);
                }],
            [['published_at'], 'integer', 'enableClientValidation'=>false],
            /*[['language'], 'required', 'when' => function($model){
                    return !$this->parent_id;
                }, 'whenClient' => 'function(attribute, value) {
                    return $("#' . Html::getInputId($this, 'parent_id') . '").val() == "";
                }'],*/
            [['language'], 'required'],
            [['language'], 'string', 'max' => 7],
            [['language'], function($attribute, $params) {
                if (($parent = self::findOne($this->parent_id)) && !$parent->isRoot() && $parent->language != $this->language) {
                    $this->addError($attribute, Yii::t('menst.cms', 'Язык должен совпадать с родительским.'));
                }
            }],

            /*[['language'], 'filter', 'filter'=>function($value) {
                    return ($parent = self::findOne($this->parent_id)) ? $parent->language : null;
                }, 'when' => function(){
                    return $this->parent_id;
                }],*/

            [['parent_id'], 'exist', 'targetAttribute'=>'id'],
            [['parent_id'], 'compare', 'compareAttribute'=>'id', 'operator'=>'!='],

            [['alias'], 'filter', 'filter'=>'trim'],
            [['alias'], 'filter', 'filter'=>function($value){
                    if (empty($value)) {
                        return Inflector::slug(TransliteratorHelper::process($this->title));                        
                    } else {
                        return Inflector::slug($value);                        
                    }
                }],
            [['alias'], 'unique', 'filter'=>function($query){
                    /** @var $query \yii\db\ActiveQuery */
                    if($parent = self::findOne($this->parent_id)){
                        $query->andWhere('lft>=:lft AND rgt<=:rgt AND level=:level AND language=:language', [
                                'lft' => $parent->lft,
                                'rgt' => $parent->rgt,
                                'level' => $parent->level + 1,
                                'language' => $this->language
                            ]);
                        //$path = $parent->path . '/' . $this->alias;
                        //$query->andWhere(['path'=>$path]);
                    }// else $query->andWhere(['language'=>$this->language])->andWhere('root=id');
                }],
            [['alias'], 'string', 'max' => 250],
            [['alias'], 'required', 'enableClientValidation' => false],

            [['title', 'detail_text', 'status'], 'required'],
            [['tags', 'versionNote'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('menst.cms', 'ID'),
            'parent_id' => Yii::t('menst.cms', 'Parent ID'),
            'language' => Yii::t('menst.cms', 'Language'),
            'title' => Yii::t('menst.cms', 'Title'),
            'alias' => Yii::t('menst.cms', 'Alias'),
            'path' => Yii::t('menst.cms', 'Path'),
            'preview_text' => Yii::t('menst.cms', 'Preview Text'),
            'preview_image' => Yii::t('menst.cms', 'Preview Image'),
            'detail_text' => Yii::t('menst.cms', 'Detail Text'),
            'detail_image' => Yii::t('menst.cms', 'Detail Image'),
            'metakey' => Yii::t('menst.cms', 'Metakey'),
            'metadesc' => Yii::t('menst.cms', 'Metadesc'),
            'created_at' => Yii::t('menst.cms', 'Created At'),
            'updated_at' => Yii::t('menst.cms', 'Updated At'),
            'published_at' => Yii::t('menst.cms', 'Published At'),
            'status' => Yii::t('menst.cms', 'Status'),
            'created_by' => Yii::t('menst.cms', 'Created By'),
            'updated_by' => Yii::t('menst.cms', 'Updated By'),
            'lft' => Yii::t('menst.cms', 'Lft'),
            'rgt' => Yii::t('menst.cms', 'Rgt'),
            'level' => Yii::t('menst.cms', 'Level'),
            'ordering' => Yii::t('menst.cms', 'Ordering'),
            'hits' => Yii::t('menst.cms', 'Hits'),
            'lock' => Yii::t('menst.cms', 'Lock'),
            'tags' => Yii::t('menst.cms', 'Tags'),
            'versionNote' => Yii::t('menst.cms', 'Version Note')
        ];
    }

    public function behaviors()
    {
        return [
            \yii\behaviors\TimestampBehavior::className(),
            \yii\behaviors\BlameableBehavior::className(),
            TaggableBehavior::className(),
            [
                'class' => VersioningBehavior::className(),
                'attributes' => ['title', 'alias', 'preview_text', 'detail_text', 'metakey', 'metadesc']
            ],
            [
                'class' => NestedSetBehavior::className(),
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
                    'savePath' => 'upload/categories'
                ]
            ]
        ];
    }

    /**
     * @inheritdoc
     * @return CategoryQuery
     */
    public static function find()
    {
        return new CategoryQuery(get_called_class());
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPosts()
    {
        return $this->hasMany(Post::className(), ['category_id'=>'id'])->inverseOf('category');
    }

    private static $_statuses = [
        self::STATUS_PUBLISHED => 'Published',
        self::STATUS_UNPUBLISHED => 'Unpublished',
    ];

    public static function statusLabels()
    {
        return array_map(function($label) {
                return Yii::t('menst.cms', $label);
            }, self::$_statuses);
    }

    public function getStatusLabel($status=null)
    {
        if ($status === null) {
            return Yii::t('menst.cms', self::$_statuses[$this->status]);
        }
        return Yii::t('menst.cms', self::$_statuses[$status]);
    }

    public function optimisticLock()
    {
        return 'lock';
    }

    public function hit()
    {
        return $this->updateAttributes(['hits'=>$this->hits + 1]);
    }

    public function save($runValidation=true,$attributes=null)
    {
        if($this->getIsNewRecord() && $parent = self::findOne($this->parent_id))
            return $this->appendTo($parent,$runValidation,$attributes);

        return $this->saveNode($runValidation,$attributes);
    }

    public function afterSave($insert, $changedAttributes)
    {
        $newParent = self::findOne($this->parent_id);
        $moved = false;
        if (isset($newParent)) {
            if (!($parent = $this->parent()->one()) or !$parent->equals($newParent)) {
                $this->moveAsLast($newParent);
                $newParent->refresh();
                $newParent->reorderNode('lft');
                $moved = true;
            } else {
                if(array_key_exists('ordering', $changedAttributes)) $newParent->reorderNode('ordering');
            }
        }/* else
            if(!$this->isRoot()) {
                $this->moveAsRoot();
                $moved = true;
            }*/

        if ($moved) {
            $this->refresh();
            $this->normalizePath();
        } elseif(array_key_exists('alias', $changedAttributes)) {
            $this->normalizePath();
        }

        //Если изменен язык, смена языка возможна только для корневых пунктов меню
        if (array_key_exists('language', $changedAttributes)) {
            $this->normalizeDescendants();
        }

        return parent::afterSave($insert, $changedAttributes);
    }

    private function calculatePath()
    {
        $aliases = $this->ancestors()->noRoots()->select('alias')->column();
        return empty($aliases) ? $this->alias : implode('/', $aliases) . '/' . $this->alias;
    }

    public function normalizePath($parentPath = null)
    {
        if($parentPath === null) {
            $path = $this->calculatePath();
        } else {
            $path = $parentPath . '/' . $this->alias;
        }

        $this->updateAttributes(['path' => $path]);

        $children = $this->children()->all();
        foreach ($children as $child) {
            $child->normalizePath($path);
        }
    }

    public function normalizeDescendants()
    {
        $ids = $this->descendants()->select('id')->column();
        self::updateAll(['language' => $this->language], ['id' => $ids]);
    }

    /**
     * @return static | null
     */
    public function getParent()
    {
        return $this->parent()->one();
    }

    //LinkableItem Interface
    /**
     * @inheritdoc
     */
    public function getViewLink()
    {
        return ['/cms/news/category/view', 'id' => $this->id/*, 'alias'=>$this->alias*/];
    }
    /**
     * @inheritdoc
     */
    public static function viewLink($model)
    {
        return ['/cms/news/category/view', 'id' => $model['id']/*, 'alias'=>$model['alias']*/];
    }

    //translatable interface
    public function getTranslations()
    {
        return self::hasMany(self::className(), ['path'=>'path'])->indexBy('language');
    }

    public function getLanguage()
    {
        return $this->language;
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

    public function getBreadcrumbs($includeSelf = false)
    {
        if ($this->isRoot()) {
            return [];
        } else {
            $path = $this->ancestors()->noRoots()->all();
            if ($includeSelf) {
                $path[] = $this;
            }
            return array_map(function ($item) {
                /** @var self $item */
                return [
                    'label' => $item->title,
                    'url' => $item->getViewLink()
                ];
            }, $path);
        }
    }
}
