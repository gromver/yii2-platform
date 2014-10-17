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
use menst\cms\common\models\Category;
use yii\base\InvalidConfigException;
use Yii;

/**
 * Class CategoryView
 * @package yii2-cms
 * @author Gayazov Roman <m.e.n.s.t@yandex.ru>
 */
class CategoryView extends Widget {
    /**
     * Category or CategoryId or CategoryId:CategoryPath
     * @var Category|string
     * @type modal
     * @url /cms/default/select-category
     */
    public $source;
    /**
     * @type list
     * @items languages
     */
    public $language;
    /**
     * @type list
     * @items layouts
     * @editable
     */
    public $layout = 'category/viewDefault';


    protected function normalizeSource()
    {
        if ($this->source && !$this->source instanceof Category) {
            @list($id, $path) = explode(':', $this->source);
            $this->source = null;

            if ($path) {
                $this->language or $this->language = Yii::$app->language;

                $this->source = Category::find()->andWhere(['path' => $path, 'language' => $this->language])->one();
            }

            if (empty($this->source))  {
                $this->source = Category::findOne($id);
            }
        }

        if (empty($this->source)) {
            throw new InvalidConfigException(Yii::t('menst.cms', 'Category not found.'));
        }
    }

    protected function launch()
    {
        $this->normalizeSource();

        echo $this->render($this->layout, [
            'model' => $this->source,
        ]);
    }

    public static function layouts()
    {
        return [
            'category/viewDefault' => Yii::t('menst.cms', 'Default'),
            'category/viewVerbose' => Yii::t('menst.cms', 'Verbose'),
            'category/viewOnlyCategories' => Yii::t('menst.cms', 'Only categories list'),
            'category/viewOnlyPosts' => Yii::t('menst.cms', 'Only posts list'),
        ];
    }

    public static function languages()
    {
        return ['' => Yii::t('menst.cms', 'Autodetect')] + Yii::$app->getLanguagesList();
    }
} 