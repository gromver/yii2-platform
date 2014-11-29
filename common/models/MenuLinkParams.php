<?php
/**
 * @link https://github.com/gromver/yii2-cmf.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/gromver/yii2-grom/blob/master/LICENSE
 * @package yii2-cmf
 * @version 1.0.0
 */

namespace gromver\platform\common\models;

use yii\base\Model;

/**
 * Class MenuLinkParams
 * @package yii2-cmf
 * @author Gayazov Roman <gromver5@gmail.com>
 */
class MenuLinkParams extends Model
{
    public $title;
    public $class;
    public $style;
    public $target;
    public $onclick;
    public $rel;

    public function rules()
    {
        return [
            [['title', 'class', 'style', 'target', 'onclick', 'rel'], 'string']
        ];
    }
}