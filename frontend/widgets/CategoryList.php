<?php
/**
 * @link https://github.com/menst/yii2-cms.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/menst/yii2-cms/blob/master/LICENSE
 * @package yii2-cms
 * @version 1.0.0
 */

namespace menst\cms\frontend\widgets;

use menst\cms\common\widgets\Widget;
use menst\cms\common\models\Category;
use yii\data\ActiveDataProvider;

/**
 * Class CategoryList
 * @package yii2-cms
 * @author Gayazov Roman <m.e.n.s.t@yandex.ru>
 */
class CategoryList extends Widget {
    /**
     * Category or CategoryId or CategoryId:CategoryPath
     * @var Category|string
     * @type modal
     * @url /cms/default/select-category
     */
    public $category;
    /**
     * @type list
     * @items languages
     */
    public $language;
    /**
     * @type list
     * @items layouts
     * @editable
     */
    public $layout = 'category/list';
    /**
     * @type list
     * @items itemLayouts
     * @editable
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

    protected function normalizeCategory()
    {
        if ($this->category && !$this->category instanceof Category) {
            @list($id, $path) = explode(':', $this->category);
            $this->category = null;

            if ($this->language && $path) {
                $language = $this->language == 'auto' ? \Yii::$app->language : $this->language;

                $this->category = Category::find()->andWhere(['path' => $path, 'language' => $language])->one();
            }

            if (empty($this->category)) {
                $this->category = Category::findOne($id);
            }
        }
    }

    protected function launch()
    {
        $this->normalizeCategory();

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

    public static function layouts()
    {
        return [
            'category/list' => 'По умолчанию',
        ];
    }


    public static function itemLayouts()
    {
        return [
            '_itemArticle' => 'Статья',
            //'_itemNews' => 'Новость',
        ];
    }

    public static function sortColumns()
    {
        return [
            'published_at' => 'По дате публикации',
            'created_at' => 'По дате создания',
            'title' => 'По названию',
            'ordering' => 'По порядку',
        ];
    }

    public static function sortDirections()
    {
        return [
            SORT_ASC => 'По возрастанию',
            SORT_DESC => 'По убыванию',
        ];
    }

    public static function languages()
    {
        return ['' => 'Не задано', 'auto' => \Yii::t('menst.cms', 'Автоопределение')] + \Yii::$app->getLanguagesList();
    }
} 