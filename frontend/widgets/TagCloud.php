<?php
/**
 * @link https://github.com/gromver/yii2-cmf.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/gromver/yii2-cmf/blob/master/LICENSE
 * @package yii2-cmf
 * @version 1.0.0
 */

namespace gromver\cmf\frontend\widgets;

use gromver\cmf\common\models\Tag;
use gromver\cmf\common\widgets\Widget;
use yii\db\Query;
use Yii;

/**
 * Class TagCloud
 * @package yii2-cmf
 * @author Gayazov Roman <gromver5@gmail.com>
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

        $this->language or $this->language = Yii::$app->language;

        $this->_tags = (new Query())
            ->select('t.id, t.title, t.alias, count(t2m.item_id) AS weight')
            ->from(Tag::tableName() . ' t')
            ->innerJoin(Tag::pivotTableName() . ' t2m', 't.id=t2m.tag_id')
            ->where(['t.language' => $this->language])
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
        return ['' => Yii::t('gromver.cmf', 'Autodetect')] + Yii::$app->getLanguagesList();
    }
} 