<?php
/**
 * @link https://github.com/menst/yii2-cms.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/menst/yii2-cms/blob/master/LICENSE
 * @package yii2-cms
 * @version 1.0.0
 */

namespace menst\cms\common\models;

use creocoder\behaviors\NestedSetQuery;
use yii\db\ActiveQuery;
use yii\db\Query;

/**
 * Class MenuItemQuery
 * @package yii2-cms
 * @author Gayazov Roman <m.e.n.s.t@yandex.ru>
 */
class MenuItemQuery extends ActiveQuery
{
    public function behaviors() {
        return [
            [
                'class' => NestedSetQuery::className(),
            ],
        ];
    }

    /**
     * @param $typeId
     * @return static
     */
    public function type($typeId)
    {
        return $this->andWhere(['{{%cms_menu_item}}.menu_type_id' => $typeId]);
    }
    /**
     * @return static
     */
    public function published()
    {
        $badcatsQuery = new Query([
            'select' => ['badcats.id'],
            'from' => ['{{%cms_menu_item}} AS unpublished'],
            'join' => [
                ['LEFT JOIN', '{{%cms_menu_item}} AS badcats', 'unpublished.lft <= badcats.lft AND unpublished.rgt >= badcats.rgt']
            ],
            'where' => 'unpublished.status != ' . MenuItem::STATUS_PUBLISHED,
            'groupBy' => ['badcats.id']
        ]);

        return $this->andWhere(['NOT IN', '{{%cms_menu_item}}.id', $badcatsQuery]);
    }

    /**
     * @return static
     */
    public function language($language)
    {
        return $this->andFilterWhere(['{{%cms_menu_item}}.language' => $language]);
    }

    /**
     * @return static
     */
    public function noRoots()
    {
        return $this->andWhere('{{%cms_menu_item}}.lft!=1');
    }
}