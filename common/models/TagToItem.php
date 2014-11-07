<?php
/**
 * @link https://github.com/gromver/yii2-cmf.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/gromver/yii2-cmf/blob/master/LICENSE
 * @package yii2-cmf
 * @version 1.0.0
 */

namespace gromver\cmf\common\models;


use yii\db\ActiveRecord;

/**
 * Class TagToItem
 * @package yii2-cmf
 * @author Gayazov Roman <gromver5@gmail.com>
 *
 * @property integer $tag_id
 * @property integer $item_id
 * @property string $item_class
 * @property ActiveRecord $item
 */
class TagToItem extends ActiveRecord {
    public static function tableName()
    {
        return '{{%cms_tag_to_item}}';
    }

    public static function primaryKey()
    {
        return ['tag_id', 'item_id', 'item_class'];
    }

    public function getItem()
    {
        /** @var ActiveRecord $itemClass */
        $itemClass = $this->item_class;
        return $itemClass::findOne($this->item_id);
    }
} 