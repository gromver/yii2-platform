<?php
/**
 * @link https://github.com/menst/yii2-cms.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/menst/yii2-cms/blob/master/LICENSE
 * @package yii2-cms
 * @version 1.0.0
 */

namespace menst\cms\frontend\widgets;

use menst\cms\common\widgets\Widget;
use menst\cms\common\models\Tag;
use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;

/**
 * Class TagItems
 * @package yii2-cms
 * @author Gayazov Roman <m.e.n.s.t@yandex.ru>
 */
class TagItems extends Widget {
    /**
     * Tag model or TagId
     * @var Tag|string
     * @type modal
     * @url /cms/default/select-tag
     */
    public $source;
    /**
     * @type list
     * @items languages
     */
    public $language;
    public $pageSize = 20;
    /**
     * @type list
     * @items layouts
     * @editable
     */
    public $layout = 'tag/itemsDefault';
    /**
     * @type list
     * @items itemLayouts
     * @editable
     */
    public $itemLayout = '_itemItem';


    protected function normalizeSource()
    {
        if (!$this->source instanceof Tag) {
            @list($id, $alias) = explode(':', $this->source);
            $this->source = null;

            if ($this->language && $alias) {
                $language = $this->language == 'auto' ? \Yii::$app->language : $this->language;

                $this->source = Tag::find()->andWhere(['alias' => $alias, 'language' => $language])->one();
            }

            if (empty($this->source)) {
                $this->source = Tag::findOne($id);
            }
        }

        if (empty($this->source)) {
            throw new InvalidConfigException('Тег не найден.');
        }
    }

    protected function launch()
    {
        $this->normalizeSource();

        echo $this->render($this->layout, [
            'dataProvider' => new ActiveDataProvider([
                    'query' => $this->source->getTagToItems(),
                    'pagination' => [
                        'pageSize' => $this->pageSize
                    ]
                ]),
            'itemLayout' => $this->itemLayout,
            'model' => $this->source
        ]);

        $this->source->hit();
    }

    public static function layouts()
    {
        return [
            'tag/itemsDefault' => 'По умолчанию',
            'tag/itemsOnly' => 'Только список',
        ];
    }

    public static function itemLayouts()
    {
        return [
            '_itemItem' => 'По умолчанию',
        ];
    }

    public static function languages()
    {
        return ['' => 'Не задано', 'auto' => \Yii::t('menst.cms', 'Автоопределение')] + \Yii::$app->getLanguagesList();
    }

} 