<?php
/**
 * @link https://github.com/menst/yii2-cms.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/menst/yii2-cms/blob/master/LICENSE
 * @package yii2-cms
 * @version 1.0.0
 */

namespace menst\cms\frontend\widgets;

use menst\cms\common\models\Tag;
use menst\cms\common\widgets\Widget;
use yii\db\Query;

/**
 * Class TagCloud
 * @package yii2-cms
 * @author Gayazov Roman <m.e.n.s.t@yandex.ru>
 */
class TagCloud extends Widget {
    public $fontBase = 14;
    public $fontSpace = 6;
    /**
     * @type list
     * @items languages
     */
    public $language;

    private $_tags;
    private $_maxWeight;

    public function init()
    {
        parent::init();

        $language = $this->language == 'auto' ? \Yii::$app->language : $this->language;

        $this->_tags = (new Query())
            ->select('t.id, t.title, t.alias, count(t2m.item_id) AS weight')
            ->from(Tag::tableName() . ' t')
            ->innerJoin(Tag::pivotTableName() . ' t2m', 't.id=t2m.tag_id')
            ->where(['t.language' => $language])
            ->groupBy('t.id')->all();

        shuffle($this->_tags);
    }

    public function getTags()
    {
        return $this->_tags;
    }

    public function getMaxWeight()
    {
        if(!isset($this->_maxWeight)) {
            $max = 0;
            array_walk($this->_tags, function($v)use(&$max){
                $max = max($v['weight'], $max);
            });
            $this->_maxWeight = $max;
        }

        return $this->_maxWeight;
    }

    protected function launch()
    {
        echo $this->render('tag/tagCloud', [
            'tags' => $this->_tags
        ]);
    }

    public static function languages()
    {
        return ['' => 'Не задано', 'auto' => \Yii::t('menst.cms', 'Автоопределение')] + \Yii::$app->getLanguagesList();
    }
} 