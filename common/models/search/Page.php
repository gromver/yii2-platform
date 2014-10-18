<?php
/**
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/menst/yii2-cms/blob/master/LICENSE
 * @link https://github.com/menst/yii2-cms.git#readme
 * @package yii2-cms
 * @version 1.0.0
 */

namespace menst\cms\common\models\search;

/**
 * Class Page
 * @package yii2-cms
 * @author Gayazov Roman <m.e.n.s.t@yandex.ru>
 */
class Page extends ActiveDocument {
    public function attributes()
    {
        return ['id', 'title', 'alias', 'metakey', 'metadesc', 'language', 'published', 'tags', 'text', 'date'];
    }

    public static function model()
    {
        return \menst\cms\common\models\Page::className();
    }

    /**
     * @param \menst\cms\common\models\Page $model
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