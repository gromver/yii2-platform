<?php
/**
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/gromver/yii2-cmf/blob/master/LICENSE
 * @link https://github.com/gromver/yii2-cmf.git#readme
 * @package yii2-cmf
 * @version 1.0.0
 */

namespace gromver\cmf\backend\modules\elasticsearch\models;

/**
 * Class Category
 * @package yii2-cmf
 * @author Gayazov Roman <gromver5@gmail.com>
 */
class Category extends ActiveDocument {
    public function attributes()
    {
        return ['id', 'parent_id', 'title', 'metakey', 'metadesc', 'language', 'published', 'tags', 'text', 'date'];
    }

    public static function model()
    {
        return \gromver\cmf\common\models\Category::className();
    }

    /**
     * @param \gromver\cmf\common\models\Page $model
     */
    public function loadModel($model)
    {
        $this->attributes = $model->toArray([], ['published', 'tags', 'text', 'date']);
    }

    public static function filter()
    {
        $filters = [
            [
                'not' => [
                    'and' => [
                        [
                            'type' => ['value' => 'category']
                        ],
                        [
                            'term' => ['published' => false]
                        ]
                    ]
                ]
            ]
        ];

        if ($unpublishedCategories = \gromver\cmf\common\models\Category::find()->unpublished()->select('{{%cms_category}}.id')->column()) {
            $filters[] = [
                'not' => [
                    'and' => [
                        [
                            'type' => ['value' => 'category']
                        ],
                        [
                            'term' => ['parent_id' => $unpublishedCategories]
                        ]
                    ]
                ]
            ];
        }

        return $filters;
    }
} 