<?php
/**
 * @link https://github.com/gromver/yii2-cmf.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/gromver/yii2-grom/blob/master/LICENSE
 * @package yii2-cmf
 * @version 1.0.0
 */

namespace gromver\platform\frontend\widgets;

use gromver\platform\common\widgets\Widget;
use gromver\platform\common\models\Category;
use yii\data\ActiveDataProvider;
use Yii;

/**
 * Class CategoryList
 * @package yii2-cmf
 * @author Gayazov Roman <gromver5@gmail.com>
 */
class CategoryList extends Widget {
    /**
     * Category or CategoryId or CategoryId:CategoryPath
     * @var Category|string
     * @type modal
     * @url /grom/default/select-category
     * @translation gromver.platform
     */
    public $category;
    /**
     * @type list
     * @items layouts
     * @editable
     * @translation gromver.platform
     */
    public $layout = 'category/list';
    /**
     * @type list
     * @items itemLayouts
     * @editable
     * @translation gromver.platform
     */
    public $itemLayout = '_itemArticle';
    /**
     * @type list
     * @editable
     * @items sortColumns
     * @var string
     */
    public $sort = 'published_at';
    /**
     * @type list
     * @editable
     * @items sortDirections
     * @translation gromver.platform
     */
    public $dir = SORT_DESC;
    /**
     * @ignore
     */
    public $listViewOptions = [];

    public function init()
    {
        parent::init();
    }

    protected function launch()
    {
        if ($this->category && !$this->category instanceof Category) {
            $this->category = Category::findOne(intval($this->category));
        }

        echo $this->render($this->layout, [
            'dataProvider' => new ActiveDataProvider([
                    'query' => $this->category ? $this->category->children()->published()->orderBy(null) : Category::find()->roots()->published(),
                    'pagination' => false,
                    'sort' => [
                        'defaultOrder' => [$this->sort => $this->dir]
                    ]
                ]),
            'itemLayout' => $this->itemLayout
        ]);
    }

    public function customControls()
    {
        return [
            [
                'url' => Yii::$app->urlManagerBackend->createUrl(['grom/news/category/create', 'parentId' => $this->category ? $this->category->id : null, 'backUrl' => $this->getBackUrl()]),
                'label' => '<i class="glyphicon glyphicon-plus"></i>',
                'options' => ['title' => Yii::t('gromver.platform', 'Create Category')]
            ],
            [
                'url' => Yii::$app->urlManagerBackend->createUrl(['grom/news/category/index', 'CategorySearch' => ['parent_id' => $this->category ? $this->category->id : null]]),
                'label' => '<i class="glyphicon glyphicon-th-list"></i>',
                'options' => ['title' => Yii::t('gromver.platform', 'Categories list'), 'target' => '_blank']
            ],
        ];
    }

    public static function layouts()
    {
        return [
            'category/list' => Yii::t('gromver.platform', 'Default'),
        ];
    }


    public static function itemLayouts()
    {
        return [
            '_itemArticle' => Yii::t('gromver.platform', 'Default'),
        ];
    }

    public static function sortColumns()
    {
        return [
            'published_at' => Yii::t('gromver.platform', 'By publish date'),
            'created_at' => Yii::t('gromver.platform', 'By create date'),
            'title' => Yii::t('gromver.platform', 'By name'),
            'ordering' => Yii::t('gromver.platform', 'By order'),
        ];
    }

    public static function sortDirections()
    {
        return [
            SORT_ASC => Yii::t('gromver.platform', 'Asc'),
            SORT_DESC => Yii::t('gromver.platform', 'Desc'),
        ];
    }
} 