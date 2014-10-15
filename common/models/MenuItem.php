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
use menst\cms\common\interfaces\ViewableInterface;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;
use yii\helpers\Json;

/**
 * This is the model class for table "cms_menu_item".
 * @package yii2-cms
 * @author Gayazov Roman <m.e.n.s.t@yandex.ru>
 *
 * @property integer $id
 * @property integer $menu_type_id
 * @property integer $parent_id
 * @property integer $status
 * @property string $language
 * @property string $title
 * @property string $alias
 * @property string $path
 * @property string $note
 * @property string $link
 * @property integer $link_type
 * @property string $link_params
 * @property string $layout_path
 * @property string $access_rule
 * @property string $metakey
 * @property string $metadesc
 * @property string $robots
 * @property integer $secure
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $lft
 * @property integer $rgt
 * @property integer $level
 * @property string $ordering
 * @property string $hits
 * @property string $lock
 *
 * @property string $linkTitle
 * @property MenuType $menuType
 * @property MenuItem $parent
 */
class MenuItem extends \yii\db\ActiveRecord implements ViewableInterface
{
    const STATUS_PUBLISHED = 1;
    const STATUS_UNPUBLISHED = 2;

    const LINK_ROUTE = 1;   //MenuItem::link используется в качестве роута, MenuItem::path в качестве ссылки
    const LINK_HREF = 2;    //MenuItem::link используется в качестве ссылки, MenuItem::path не используется

    const CONTEXT_PROPER = 1;
    const CONTEXT_APPLICABLE = 2;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%cms_menu_item}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['menu_type_id', 'parent_id', 'status', 'link_type', 'secure', 'created_at', 'updated_at', 'created_by', 'updated_by', 'lft', 'rgt', 'level', 'ordering', 'hits', 'lock'], 'integer'],
            [['menu_type_id'], 'required'],
            [['menu_type_id'], 'exist', 'targetAttribute' => 'id', 'targetClass' => MenuType::className()],
            [['language'], 'required'],
            [['language'], 'string', 'max' => 7],
            [['language'], function($attribute, $params) {
                if (($parent = self::findOne($this->parent_id)) && !$parent->isRoot() && $parent->language != $this->language) {
                    $this->addError($attribute, Yii::t('menst.cms', 'Язык должен совпадать с родительским.'));
                }
            }],
            [['title', 'link', 'layout_path'], 'string', 'max' => 1024],
            [['alias', 'note', 'metakey'], 'string', 'max' => 255],
            [['metadesc'], 'string', 'max' => 2048],
            [['access_rule', 'robots'], 'string', 'max' => 50],

            [['parent_id'], 'exist', 'targetAttribute' => 'id'],
            [['parent_id'], 'compare', 'compareAttribute' => 'id', 'operator' => '!='],
            [['parent_id'], function($attribute, $params) {
                if (($parent = self::findOne($this->parent_id)) && !$parent->isRoot() && $parent->menu_type_id != $this->menu_type_id) {
                    $this->addError($attribute, Yii::t('menst.cms', 'Родительский пункт меню не соотвествует выбранному типу меню.'));
                }
            }],
            [['alias'], 'filter', 'filter' => 'trim'],
            [['alias'], 'filter', 'filter' => function($value){
                    if (empty($value)) {
                        return Inflector::slug(TransliteratorHelper::process($this->title));
                    } else {
                        return Inflector::slug($value);
                    }
                }],
            [['alias'], 'unique', 'filter' => function($query) {
                    /** @var $query \yii\db\ActiveQuery */
                    if ($parent = self::findOne($this->parent_id)){
                        $query->andWhere('lft>=:lft AND rgt<=:rgt AND level=:level AND language=:language', [
                                'lft' => $parent->lft,
                                'rgt' => $parent->rgt,
                                'level' => $parent->level + 1,
                                'language' => $this->language
                            ]);
                    }
                }],
            [['alias'], 'string', 'max' => 255],
            [['alias'], 'required', 'enableClientValidation' => false],
            [['title',  'link', 'status'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('menst.cms', 'ID'),
            'menu_type_id' => Yii::t('menst.cms', 'Menu Type ID'),
            'parent_id' => Yii::t('menst.cms', 'Parent ID'),
            'status' => Yii::t('menst.cms', 'Status'),
            'language' => Yii::t('menst.cms', 'Language'),
            'title' => Yii::t('menst.cms', 'Title'),
            'alias' => Yii::t('menst.cms', 'Alias'),
            'path' => Yii::t('menst.cms', 'Path'),
            'note' => Yii::t('menst.cms', 'Note'),
            'link' => Yii::t('menst.cms', 'Link'),
            'link_type' => Yii::t('menst.cms', 'Link Type'),
            'link_params' => Yii::t('menst.cms', 'Link Params'),
            'layout_path' => Yii::t('menst.cms', 'Layout Path'),
            'access_rule' => Yii::t('menst.cms', 'Access Rule'),
            'metakey' => Yii::t('menst.cms', 'Metakey'),
            'metadesc' => Yii::t('menst.cms', 'Metadesc'),
            'robots' => Yii::t('menst.cms', 'Robots'),
            'secure' => Yii::t('menst.cms', 'Secure'),
            'created_at' => Yii::t('menst.cms', 'Created At'),
            'updated_at' => Yii::t('menst.cms', 'Updated At'),
            'created_by' => Yii::t('menst.cms', 'Created By'),
            'updated_by' => Yii::t('menst.cms', 'Updated By'),
            'lft' => Yii::t('menst.cms', 'Lft'),
            'rgt' => Yii::t('menst.cms', 'Rgt'),
            'level' => Yii::t('menst.cms', 'Level'),
            'ordering' => Yii::t('menst.cms', 'Ordering'),
            'hits' => Yii::t('menst.cms', 'Hits'),
            'lock' => Yii::t('menst.cms', 'Lock'),
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
            [
                'class' => NestedSetBehavior::className(),
            ]

        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenuType()
    {
        return $this->hasOne(MenuType::className(), ['id' => 'menu_type_id']);
    }

    /**
     * @return static | null
     */
    public function getParent() {
        return $this->parent()->one();
    }

    /**
     * @inheritdoc
     * @return MenuItemQuery
     */
    public static function find()
    {
        return new MenuItemQuery(get_called_class());
    }

    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_UPDATE,
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

    public function getStatusLabel($status=null)
    {
        if ($status === null) {
            return Yii::t('menst.cms', self::$_statuses[$this->status]);
        }
        return Yii::t('menst.cms', self::$_statuses[$status]);
    }

    public function getTranslations()
    {
        return self::hasMany(self::className(), ['path' => 'path'])->noRoots()->indexBy('language');
    }

    public function optimisticLock()
    {
        return 'lock';
    }

    public function save($runValidation=true, $attributes=null) {
        if($this->getIsNewRecord() && $this->parent_id) {
            return $this->appendTo(self::findOne($this->parent_id), $runValidation, $attributes);
        }

        return $this->saveNode($runValidation,$attributes);
    }

    public function afterSave($insert, $changedAttributes)
    {
        $newParent = self::findOne($this->parent_id);
        $moved = false;
        if (isset($newParent)) {
            if (!($parent = $this->parent()->one()) or !$parent->equals($newParent)) {
                //предок изменился
                $this->moveAsLast($newParent);
                $newParent->refresh();
                $newParent->reorderNode('lft');
                $moved = true;
            } else {
                if(array_key_exists('ordering', $changedAttributes)) $newParent->reorderNode('ordering');
            }
        }/* else {
            if (!$this->isRoot()) {
                $this->moveAsRoot();
                $moved = true;
            }
        }*/

        if ($moved) {
            $this->refresh();
            $this->normalizePath();
        } elseif (array_key_exists('alias', $changedAttributes)) {
            $this->normalizePath();
        }

        //Если изменен тип меню или язык, смена языка возможна только для корневых пунктов меню
        if (array_key_exists('menu_type_id', $changedAttributes) || array_key_exists('language', $changedAttributes)) {
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
        if ($parentPath === null) {
            $path = $this->calculatePath();
        } else {
            $path = $parentPath . '/' . $this->alias;
        }

        $this->updateAttributes(['path' => $path]);

        $children = $this->children()->all();
        foreach ($children as $child) {
            /** @var self $child */
            $child->normalizePath($path);
        }
    }

    public function normalizeDescendants()
    {
        $ids = $this->descendants()->select('id')->column();
        self::updateAll(['menu_type_id' => $this->menu_type_id, 'language' => $this->language], ['id' => $ids]);
    }

    public function getLinkParams()
    {
        return Json::decode($this->link_params);
    }

    public function setLinkParams($value)
    {
        $this->link_params = Json::encode($value);
    }

    /**
     * @return MenuLinkParams
     */
    public function getLinkParamsModel()
    {
        $model = new MenuLinkParams();
        $model->setAttributes($this->getLinkParams());
        return $model;
    }


    static public function getLinkTypes()
    {
        return [
            self::LINK_ROUTE => 'Ссылка на компонент',
            self::LINK_HREF => 'Обычная ссылка',
        ];
    }


    private static $_linkTypes = [
        self::LINK_ROUTE => 'Ссылка на компонент',
        self::LINK_HREF => 'Обычная ссылка',
    ];

    public static function linkTypeLabels()
    {
        return array_map(function($label) {
                return Yii::t('menst.news', $label);
            }, self::$_statuses);
    }

    public function getLinkTypeLabel($type=null)
    {
        if ($type === null) {
            return Yii::t('menst.cms', self::$_linkTypes[$this->link_type]);
        }
        return Yii::t('menst.cms', self::$_linkTypes[$type]);
    }


    public function parseUrl()
    {
        $arUrl = parse_url($this->link);
        parse_str(@$arUrl['query'], $params);
        if(!empty($arUrl['fragment']))
            $params['#'] = $arUrl['fragment'];
        return [trim($arUrl['path'], '/'), $params];
    }

    public static function toRoute($route, $params=null)
    {
        if (is_array($route)) {
            $_route = $route;
            $route = ArrayHelper::remove($_route, 0);
            $params = array_merge($_route, (array)$params);
        }

        return !empty($params) ? $route . '?' . http_build_query($params):$route;
    }

    private $_context;

    public function setContext($value)
    {
        $this->_context = $value;
    }

    public function getContext()
    {
        return $this->_context;
    }

    public function isProperContext()
    {
        return $this->_context === self::CONTEXT_PROPER;
    }

    public function isApplicableContext()
    {
        return $this->_context === self::CONTEXT_APPLICABLE;
    }

    //ViewableInterface
    /**
     * @inheritdoc
     */
    public function getViewLink()
    {
        if($this->link_type==self::LINK_ROUTE)
            return '/' . $this->path;
        else
            return $this->link;
    }
    /**
     * @inheritdoc
     */
    public static function viewLink($model) {
        if($model['link_type']==self::LINK_ROUTE)
            return '/' . $model['path'];
        else
            return $model['link'];
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

    /**
     * Тайтл для ссылок в меню
     * @return string
     */
    public function getLinkTitle()
    {
        $linkParams = $this->getLinkParams();
        return empty($linkParams['title']) ? $this->title : $linkParams['title'];
    }
}
