<?php
/**
 * @link https://github.com/menst/yii2-cms.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/menst/yii2-cms/blob/master/LICENSE
 * @package yii2-cms
 * @version 1.0.0
 */

namespace menst\cms\backend\modules\news\models;

use menst\cms\common\models\Category;
use Yii;
use yii\data\ActiveDataProvider;

/**
 * Class CategorySearch represents the model behind the search form about `menst\cms\common\models\Category`.
 * @package yii2-cms
 * @author Gayazov Roman <m.e.n.s.t@yandex.ru>
 */
class CategorySearch extends Category
{
    public $tags;

    public function rules()
    {
        return [
            [['id', 'parent_id', 'created_at', 'updated_at', 'status', 'created_by', 'updated_by', 'lft', 'rgt', 'level', 'ordering', 'hits', 'lock'], 'integer'],
            [['language', 'title', 'alias', 'path', 'preview_text', 'preview_image', 'detail_text', 'detail_image', 'metakey', 'metadesc', 'tags', 'versionNote'], 'safe'],
            [['published_at'], 'date', 'format' => 'dd.MM.yyyy', 'timestampAttribute' => 'published_at', 'when' => function() {
                    return is_string($this->published_at);
                }],
        ];
    }

    /**
     * @param $params
     * @return ActiveDataProvider
     */
    public function search($params, $withRoots = false)
    {
        $query = $withRoots ? Category::find() : Category::find()->noRoots();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'lft' => SORT_ASC
                ]
            ]
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            '{{%cms_category}}.id' => $this->id,
            '{{%cms_category}}.parent_id' => $this->parent_id,
            '{{%cms_category}}.created_at' => $this->created_at,
            '{{%cms_category}}.updated_at' => $this->updated_at,
            '{{%cms_category}}.status' => $this->status,
            '{{%cms_category}}.created_by' => $this->created_by,
            '{{%cms_category}}.updated_by' => $this->updated_by,
            '{{%cms_category}}.lft' => $this->lft,
            '{{%cms_category}}.rgt' => $this->rgt,
            '{{%cms_category}}.level' => $this->level,
            '{{%cms_category}}.ordering' => $this->ordering,
            '{{%cms_category}}.hits' => $this->hits,
            '{{%cms_category}}.lock' => $this->lock,
        ]);

        if ($this->published_at) {
            $query->andWhere('{{%cms_category}}.published_at >= :timestamp', ['timestamp' => $this->published_at]);
        }

        $query->andFilterWhere(['like', '{{%cms_category}}.language', $this->language])
            ->andFilterWhere(['like', '{{%cms_category}}.title', $this->title])
            ->andFilterWhere(['like', '{{%cms_category}}.alias', $this->alias])
            ->andFilterWhere(['like', '{{%cms_category}}.path', $this->path])
            ->andFilterWhere(['like', '{{%cms_category}}.preview_text', $this->preview_text])
            ->andFilterWhere(['like', '{{%cms_category}}.preview_image', $this->preview_image])
            ->andFilterWhere(['like', '{{%cms_category}}.detail_text', $this->detail_text])
            ->andFilterWhere(['like', '{{%cms_category}}.detail_image', $this->detail_image])
            ->andFilterWhere(['like', '{{%cms_category}}.metakey', $this->metakey])
            ->andFilterWhere(['like', '{{%cms_category}}.metadesc', $this->metadesc]);

        if($this->tags)
            $query->innerJoinWith('tags')->andFilterWhere(['{{%cms_tag}}.id' => $this->tags]);

        return $dataProvider;
    }
}
