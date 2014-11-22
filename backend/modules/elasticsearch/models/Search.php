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
 * Class Search
 * @package yii2-cmf
 * @author Gayazov Roman <gromver5@gmail.com>
 */
class Search extends ActiveDocument {
    /**
     * @inheritdoc
     */
    public static function instantiate($row)
    {
        return ($documentClass = ActiveDocument::findDocumentByType($row['_type'])) ? new $documentClass : new static;
    }

    public static function type()
    {
        return '';
    }

    public function attributes()
    {
        return ['title', 'text', 'date'];
    }
    //fallback
    public function getViewLink()
    {
        return [''];
    }

    public static function viewLink($model)
    {
        return [''];
    }
}