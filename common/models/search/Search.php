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
 * Class Search
 * @package yii2-cms
 * @author Gayazov Roman <m.e.n.s.t@yandex.ru>
 */
class Search extends ActiveDocument {
    /**
     * @inheritdoc
     */
    public static function instantiate($row)
    {
        return ($documentClass = ActiveDocument::findDocumentByType($row['_type'])) ? new $documentClass : new static;
    }

    public static function index()
    {
        return 'cms';
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