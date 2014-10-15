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
use menst\cms\common\models\Tag;
use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;

/**
 * Class TagPosts
 * @package yii2-cms
 * @author Gayazov Roman <m.e.n.s.t@yandex.ru>
 */
class TagPosts extends Widget {
    /**
     * Tag or TagId or TagId:TagAlias
     * @var Tag|string
     * @type modal
     * @url /cms/default/select-tag
     */
    public $source;
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
    public $layout = 'tag/postsDefault';
    /**
     * @type list
     * @items itemLayouts
     * @editable
     */
    public $itemLayout = '_itemPost';
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


    protected function normalizeSource()
    {
        if ($this->source && !$this->source instanceof Tag) {
            @list($id, $alias) = explode(':', $this->source);
            $this->source = null;

            if ($this->language && $alias) {
                $language = $this->language == 'auto' ? \Yii::$app->language : $this->language;

                $this->source = Tag::find()->andWhere(['alias' => $alias, 'language' => $language])->one();
            }

            if (empty($this->source)) {
                $this->source = Tag::findOne($id);
            }
        }
    }

    protected function launch()
    {
        $this->normalizeSource();

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
            'model' => $this->source
        ]);
    }

    protected function getQuery()
    {
        return Post::find()->published()->innerJoinWith('tags', false)->andWhere(['{{%cms_tag}}.id' => $this->source->id]);
    }

    public static function layouts()
    {
        return [
            'tag/postsDefault' => 'По умолчанию',
            'tag/postsOnly' => 'Только список',
        ];
    }

    public static function itemLayouts()
    {
        return [
            '_itemPost' => 'По умолчанию',
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