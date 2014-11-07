<?php
/**
 * @link https://github.com/gromver/yii2-cms.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/gromver/yii2-cmf/blob/master/LICENSE
 * @package yii2-cms
 * @version 1.0.0
 */

namespace gromver\cmf\frontend\widgets;

use gromver\cmf\common\widgets\Widget;
use gromver\cmf\common\models\Tag;
use Yii;
use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;

/**
 * Class TagItems
 * @package yii2-cms
 * @author Gayazov Roman <gromver5@gmail.com>
 */
class TagItems extends Widget {
    /**
     * Tag model or TagId
     * @var Tag|string
     * @type modal
     * @url /cmf/default/select-tag
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

            if ($alias) {
                $this->language or $this->language = Yii::$app->language;

                $this->source = Tag::find()->andWhere(['alias' => $alias, 'language' => $this->language])->one();
            }

            if (empty($this->source)) {
                $this->source = Tag::findOne($id);
            }
        }

        if (empty($this->source)) {
            throw new InvalidConfigException(Yii::t('menst.cms', 'Tag not found.'));
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
            'tag/itemsDefault' => Yii::t('menst.cms', 'Default'),
            'tag/itemsList' => Yii::t('menst.cms', 'List'),
        ];
    }

    public static function itemLayouts()
    {
        return [
            '_itemItem' => Yii::t('menst.cms', 'Default'),
        ];
    }

    public static function languages()
    {
        return ['' => Yii::t('menst.cms', 'Autodetect')] + Yii::$app->getLanguagesList();
    }

} 