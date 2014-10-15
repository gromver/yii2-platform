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
 * Class CategoryQuery
 * @package yii2-cms
 * @author Gayazov Roman <m.e.n.s.t@yandex.ru>
 */
class CategoryQuery extends ActiveQuery
{
    public function behaviors() {
        return [
            [
                'class' => NestedSetQuery::className(),
            ],
        ];
    }
    /**
     * @return CategoryQuery
     */
    public function published()
    {
        $badcatsQuery = new Query([
            'select' => ['badcats.id'],
            'from' => ['{{%cms_category}} AS unpublished'],
            'join' => [
                ['LEFT JOIN', '{{%cms_category}} AS badcats', 'unpublished.lft <= badcats.lft AND unpublished.rgt >= badcats.rgt']
            ],
            'where' => 'unpublished.status != '.Category::STATUS_PUBLISHED,
            'groupBy' => ['badcats.id']
        ]);

        return $this->andWhere(['NOT IN', '{{%cms_category}}.id', $badcatsQuery]);
    }

    /**
     * @return CategoryQuery
     */
    public function unpublished()
    {
        return $this->innerJoin('{{%cms_category}} AS ancestors', '{{%cms_category}}.lft >= ancestors.lft AND {{%cms_category}}.rgt <= ancestors.rgt')->andWhere('ancestors.status != '.Category::STATUS_PUBLISHED)->addGroupBy(['{{%cms_category}}.id']);
    }

    /**
     * Фильтр по категории
     * @param $id
     * @return $this
     */
    public function parent($id)
    {
        return $this->andWhere(['{{%cms_category}}.parent_id' => $id]);
    }

    /**
     * @return static
     */
    public function language($language)
    {
        return $this->andFilterWhere(['{{%cms_category}}.language' => $language]);
    }

    /**
     * @return static
     */
    public function noRoots()
    {
        return $this->andWhere('{{%cms_category}}.lft!=1');
    }

    /**
     * @return static
     */
    /*public function roots()
    {
        return $this->andWhere(['{{%cms_category}}.lft' => 1]);
    }

    /*public function tree($depth = null)
    {
        $this->addOrderBy(['{{%cms_category}}.root' => SORT_ASC, '{{%cms_category}}.lft' => SORT_ASC]);

        if ($depth !== null) {
            $this->andWhere("{{%cms_category}}.[[level]] <= :depth", [':depth' => $depth]);
        }

        return $this;
    }*/
} 