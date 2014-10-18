<?php
/**
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/menst/yii2-cms/blob/master/LICENSE
 * @link https://github.com/menst/yii2-cms.git#readme
 * @package yii2-cms
 * @version 1.0.0
 */

namespace menst\cms\common\models\search;


use menst\cms\common\models\Category;

/**
 * Class Post
 * @package yii2-cms
 * @author Gayazov Roman <m.e.n.s.t@yandex.ru>
 */
class Post extends ActiveDocument {
    public function attributes()
    {
        return ['id', 'alias', 'category_id', 'title', 'metakey', 'metadesc', 'published', 'language', 'tags', 'text', 'date'];
    }

    public static function model()
    {
        return \menst\cms\common\models\Post::className();
    }

    /**
     * @param \menst\cms\common\models\Page $model
     */
    public function loadModel($model)
    {
        $this->attributes = $model->toArray([], ['published', 'language', 'tags', 'text', 'date']);
    }

    public static function filter()
    {
        $filters = [
            [
                'not' => [
                    'and' => [
                        [
                            'type' => ['value' => 'post']
                        ],
                        [
                            'term' => ['published' => false]
                        ]
                    ]
                ]
            ],
        ];

        if ($unpublishedCategories = Category::find()->unpublished()->select('{{%cms_category}}.id')->column()) {
            $filters[] =             [
                'not' => [
                    'and' => [
                        [
                            'type' => ['value' => 'post']
                        ],
                        [
                            'term' => ['category_id' => $unpublishedCategories]
                        ]
                    ]
                ]
            ];
        }

        return $filters;
    }
} 