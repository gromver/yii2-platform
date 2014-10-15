<?php
/**
 * @link https://github.com/menst/yii2-cms.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/menst/yii2-cms/blob/master/LICENSE
 * @package yii2-cms
 * @version 1.0.0
 */

namespace menst\cms\common\models;

use yii\base\Model;

/**
 * Class MenuLinkParams
 * @package yii2-cms
 * @author Gayazov Roman <m.e.n.s.t@yandex.ru>
 */
class MenuLinkParams extends Model
{
    public $title;
    public $class;
    public $style;
    public $target;
    public $onclick;
    public $rel;
    /*public $page_title;
    public $show_page_heading = 1;
    public $page_heading;
    public $page_class;*/

    public function rules()
    {
        return [
            //[['show_page_heading'], 'integer'],
            //[['title', 'class', 'style', 'target', 'onclick', 'rel', 'page_title', 'page_heading', 'page_class'], 'string']
            [['title', 'class', 'style', 'target', 'onclick', 'rel'], 'string']
        ];
    }
}