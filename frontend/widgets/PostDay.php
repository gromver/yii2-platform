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
use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;

/**
 * Class PostDay
 * @package yii2-cms
 * @author Gayazov Roman <m.e.n.s.t@yandex.ru>
 */
class PostDay extends Widget {
    /**
     * @var Category|string
     * @type modal
     * @url /cms/default/select-category
     */
    public $category;
    public $year;
    public $month;
    public $day;
    /**
     * @type list
     * @items languages
     */
    public $language;
    /**
     * @type list
     * @items layouts
     */
    public $layout = 'post/day';
    /**
     * @type list
     * @items itemLayouts
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
    public $dir = SORT_ASC;

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
        $categoryId = $this->category ? $this->category->id : null;

        echo $this->render($this->layout, [
            'dataProvider' => new ActiveDataProvider([
                    'query' => Post::find()->published()->category($categoryId)->day($this->year, $this->month, $this->day),
                    'pagination' => false,
                    'sort' => [
                        'defaultOrder' => [$this->sort => (int)$this->dir]
                    ]
                ]),
            'itemLayout' => $this->itemLayout,
            'prevDayPost' => Post::find()->published()->category($categoryId)->beforeDay($this->year, $this->month, $this->day)->one(),
            'nextDayPost' => Post::find()->published()->category($categoryId)->afterDay($this->year, $this->month, $this->day)->one(),
            'category' => $this->category,
            'year' => $this->year,
            'month' => $this->month,
            'day' => $this->day,
            'listViewOptions' => $this->listViewOptions
        ]);
    }

    public static function layouts()
    {
        return [
            'post/day' => 'По умолчанию',
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