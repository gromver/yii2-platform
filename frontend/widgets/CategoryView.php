<?php
/**
 * @link https://github.com/gromver/yii2-cmf.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/gromver/yii2-cmf/blob/master/LICENSE
 * @package yii2-cmf
 * @version 1.0.0
 */

namespace gromver\cmf\frontend\widgets;

use gromver\cmf\common\widgets\Widget;
use gromver\cmf\common\models\Category;
use yii\base\InvalidConfigException;
use Yii;

/**
 * Class CategoryView
 * @package yii2-cmf
 * @author Gayazov Roman <gromver5@gmail.com>
 */
class CategoryView extends Widget {
    /**
     * Category or CategoryId or CategoryId:CategoryPath
     * @var Category|string
     * @type modal
     * @url /cmf/default/select-category
     */
    public $category;
    /**
     * @type list
     * @items layouts
     * @editable
     */
    public $layout = 'category/viewDefault';

    protected function launch()
    {
        if ($this->category && !$this->category instanceof Category) {
            $this->category = Category::findOne(intval($this->category));
        }

        if (empty($this->category)) {
            throw new InvalidConfigException(Yii::t('gromver.cmf', 'Category not found.'));
        }

        echo $this->render($this->layout, [
            'model' => $this->category,
        ]);
    }

    public function customControls()
    {
        return [
            [
                'url' => Yii::$app->urlManagerBackend->createUrl(['cmf/news/category/update', 'id' => $this->category->id, 'backUrl' => $this->getBackUrl()]),
                'label' => '<i class="glyphicon glyphicon-pencil"></i>',
                'options' => ['title' => Yii::t('gromver.cmf', 'Update Category')]
            ],
        ];
    }

    public static function layouts()
    {
        return [
            'category/viewDefault' => Yii::t('gromver.cmf', 'Default'),
            'category/viewVerbose' => Yii::t('gromver.cmf', 'Verbose'),
            'category/viewOnlyCategories' => Yii::t('gromver.cmf', 'Only categories list'),
            'category/viewOnlyPosts' => Yii::t('gromver.cmf', 'Only posts list'),
        ];
    }
}