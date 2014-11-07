<?php
/**
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/gromver/yii2-cmf/blob/master/LICENSE
 * @link https://github.com/gromver/yii2-cmf.git#readme
 * @package yii2-cmf
 * @version 1.0.0
 */

namespace gromver\cmf\common\models\search;

/**
 * Class Page
 * @package yii2-cmf
 * @author Gayazov Roman <gromver5@gmail.com>
 */
class Page extends ActiveDocument {
    public function attributes()
    {
        return ['id', 'title', 'alias', 'metakey', 'metadesc', 'language', 'published', 'tags', 'text', 'date'];
    }

    public static function model()
    {
        return \gromver\cmf\common\models\Page::className();
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
        return [
            [
                'not' => [
                    'and' => [
                        [
                            'type' => ['value' => 'page']
                        ],
                        [
                            'term' => ['published' => false]
                        ]
                    ]
                ]
            ]
        ];
    }
} 