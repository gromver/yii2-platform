<?php
/**
 * @link https://github.com/gromver/yii2-cms.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/gromver/yii2-cmf/blob/master/LICENSE
 * @package yii2-cms
 * @version 1.0.0
 */

namespace gromver\cmf\common\models;

use dosamigos\transliterator\TransliteratorHelper;
use gromver\cmf\common\interfaces\ViewableInterface;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\Query;
use yii\helpers\Inflector;

/**
 * This is the model class for table "cms_tag".
 * @package yii2-cms
 * @author Gayazov Roman <gromver5@gmail.com>
 *
 * @property integer $id
 * @property string $language
 * @property string $title
 * @property string $alias
 * @property integer $status
 * @property string $group
 * @property string $metakey
 * @property string $metadesc
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $hits
 * @property integer $lock
 *
 * @property \gromver\cmf\common\models\TagToItem[] $tagToItems
 * @property Tag[] $translations
 */
class Tag extends \yii\db\ActiveRecord implements ViewableInterface
{
    const STATUS_PUBLISHED = 1;
    const STATUS_UNPUBLISHED = 2;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%cms_tag}}';
    }

    /**
     * @return string
     */
    public static function pivotTableName()
    {
        return '{{%cms_tag_to_item}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['language', 'title', 'status'], 'required'],
            [['status', 'created_at', 'updated_at', 'created_by', 'updated_by', 'hits', 'lock'], 'integer'],
            [['language'], 'string', 'max' => 7],
            [['title'], 'string', 'max' => 100],
            [['alias', 'group', 'metakey'], 'string', 'max' => 255],
            [['metadesc'], 'string', 'max' => 2048],

            [['alias'], 'filter', 'filter' => 'trim'],
            [['alias'], 'filter', 'filter' => function($value){
                    if(empty($value))
                        return Inflector::slug(TransliteratorHelper::process($this->title));
                    else
                        return Inflector::slug($value);
                }],
            [['alias'], 'unique', 'filter' => function($query){
                    /** @var $query \yii\db\ActiveQuery */
                    $query->andWhere(['language' => $this->language]);
                }],
            [['alias'], 'required', 'enableClientValidation' => false],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('menst.cms', 'ID'),
            'language' => Yii::t('menst.cms', 'Language'),
            'title' => Yii::t('menst.cms', 'Title'),
            'alias' => Yii::t('menst.cms', 'Alias'),
            'status' => Yii::t('menst.cms', 'Status'),
            'group' => Yii::t('menst.cms', 'Group'),
            'metakey' => Yii::t('menst.cms', 'Metakey'),
            'metadesc' => Yii::t('menst.cms', 'Metadesc'),
            'created_at' => Yii::t('menst.cms', 'Created At'),
            'updated_at' => Yii::t('menst.cms', 'Updated At'),
            'created_by' => Yii::t('menst.cms', 'Created By'),
            'updated_by' => Yii::t('menst.cms', 'Updated By'),
            'hits' => Yii::t('menst.cms', 'Hits'),
            'lock' => Yii::t('menst.cms', 'Lock'),
        ];
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            BlameableBehavior::className()
        ];
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

    public function getStatusLabel($status = null)
    {
        return Yii::t('menst.cms', self::$_statuses[$status === null ? $this->status : $status]);
    }

    public function afterDelete()
    {
        parent::afterDelete();

        $this->getDb()->createCommand()->delete(self::pivotTableName(), ['tag_id' => $this->id])->execute();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTagToItems()
    {
        return $this->hasMany(TagToItem::className(), ['tag_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTranslations()
    {
        return self::hasMany(self::className(), ['alias' => 'alias'])->indexBy('language');
    }


    public function optimisticLock()
    {
        return 'lock';
    }

    public function hit()
    {
        return $this->updateAttributes(['hits' => $this->hits + 1]);
    }

    //http://www.slideshare.net/edbond/tagging-and-folksonomy-schema-design-for-scalability-and-performance?related=2

    /**
     * @param $tagText
     * @param null $coverage
     * @return Query
     */
    public static function relatedTagsToTagQuery($tagText, $coverage = null){
        $relatedItemsQuery = (new Query())
            ->select('item_id, item_class')
            ->from(self::tableName() . 't')->innerJoin(self::pivotTableName() . ' t2i', 't.id=t2i.tag_id')->where(['title'=>$tagText]);

        if($coverage) $relatedItemsQuery->limit($coverage);

        $resultQuery = (new ActiveQuery(self::className()))->select('t2.*')->from(['t2i1' => $relatedItemsQuery])
            ->innerJoin(self::pivotTableName() . ' t2i2', 't2i1.item_id=t2i2.item_id AND t2i1.item_class=t2i2.item_class')
            ->innerJoin(self::tableName() . ' t2', 't2i2.tag_id=t2.id')
            ->groupBy('t2i2.tag_id');

        return $resultQuery;
    }

    /**
     * @param $itemId
     * @param $itemClass
     * @return ActiveQuery
     */
    public static function relatedItemsToItemQuery($itemId, $itemClass)
    {
        return TagToItem::find()
            ->select('p2.*')
            ->from(self::pivotTableName() . ' p1')
            ->innerJoin(self::pivotTableName() . ' p2', 'p1.tag_id=p2.tag_id')
            ->where(['p1.item_id' => $itemId, 'p1.item_class' => $itemClass])
            ->groupBy('p2.item_id');
    }
    /**
     * @param $tags
     * @return ActiveQuery
     */
    public static function relatedItemsToTagsQuery($tags)
    {
        $tags = (array)$tags;

        $query = TagToItem::find()
            ->select('t2i0.*')
            ->from(self::tableName() . ' t0')
            ->innerJoin(self::pivotTableName() . ' t2i0', 't0.id=t2i0.tag_id');

        foreach($tags as $k => $tag) {
            if($k) {
                $query->join('CROSS JOIN', self::tableName() . ' t'.$k);
                $query->innerJoin(self::pivotTableName() . ' t2i'.$k, 't2i'.($k-1).'.item_id=t2i'.$k.'.item_id AND t2i'.($k-1).'.item_class=t2i'.$k.'.item_class AND t'.$k.'.id=t2i'.$k.'.tag_id');
            }

            $query->andWhere("t{$k}.title=:title{$k}", [":title{$k}"=>$tag]);
        }

        return $query;
    }

    /**
     * @param $tags
     * @return ActiveQuery
     */
    public static function relatedItemsToTagsOrQuery($tags)
    {
        return TagToItem::find()
            ->select('item_id, item_class')
            ->from(self::tableName(). ' t')
            ->innerJoin(self::pivotTableName() . ' t2i', 't.id=t2i.tag_id')
            ->where(['t.title'=>$tags])
            ->groupBy('item_id');
    }

    //ViewableInterface
    /**
     * @inheritdoc
     */
    public function getViewLink()
    {
        return ['/cmf/tag/default/view', 'id' => $this->id, 'alias' => $this->alias];
    }
    /**
     * @inheritdoc
     */
    public static function viewLink($model)
    {
        return ['/cmf/tag/default/view', 'id' => $model['id'], 'alias' => $model['alias']];
    }
}
