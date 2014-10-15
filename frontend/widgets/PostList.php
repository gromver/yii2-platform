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
use menst\cms\common\models\Post;
use yii\data\ActiveDataProvider;

/**
 * Class PostList
 * @package yii2-cms
 * @author Gayazov Roman <m.e.n.s.t@yandex.ru>
 */
class PostList extends Widget {
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
    public $layout = 'post/listBlog';
    /**
     * @type list
     * @items itemLayouts
     * @editable
     */
    public $itemLayout = '_itemArticle';
    public $pageSize = 20;
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
                    'query' => $this->getQuery(),
                    'pagination' => [
                        'pageSize' => $this->pageSize
                    ],
                    'sort' => [
                        'defaultOrder' => [$this->sort => (int)$this->dir]
                    ]
                ]),
            'itemLayout' => $this->itemLayout,
            'category' => $this->category,
            'listViewOptions' => $this->listViewOptions
        ]);
    }

    protected function getQuery()
    {
        return Post::find()->published()->category($this->category ? $this->category->id : null);
    }

    public static function layouts()
    {
        return [
            'post/listDefault' => 'Простой список',
            'post/listBlog' => 'Список и календарь с тегами',
        ];
    }

    public static function itemLayouts()
    {
        return [
            '_itemArticle' => 'Статья',
            '_itemNews' => 'Новость',
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