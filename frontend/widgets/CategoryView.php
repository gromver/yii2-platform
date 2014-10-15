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

            if ($this->language && $path) {
                $language = $this->language == 'auto' ? \Yii::$app->language : $this->language;

                $this->source = Category::find()->andWhere(['path' => $path, 'language' => $language])->one();
            }

            if (empty($this->source))  {
                $this->source = Category::findOne($id);
            }
        }

        if (empty($this->source)) {
            throw new InvalidConfigException('Категория не найдена.');
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
            'category/viewDefault' => 'По умолчанию',
            'category/viewVerbose' => 'Подробный',
            'category/viewOnlyCategories' => 'Только подкатегории',
            'category/viewOnlyPosts' => 'Только статьи',
        ];
    }

    public static function languages()
    {
        return ['' => 'Не задано', 'auto' => \Yii::t('menst.cms', 'Автоопределение')] + \Yii::$app->getLanguagesList();
    }
} 