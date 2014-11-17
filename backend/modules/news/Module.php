<?php
/**
 * @link https://github.com/gromver/yii2-cmf.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/gromver/yii2-cmf/blob/master/LICENSE
 * @package yii2-cmf
 * @version 1.0.0
 */

namespace gromver\cmf\backend\modules\news;

use gromver\cmf\backend\interfaces\DesktopInterface;
use gromver\cmf\backend\interfaces\MenuRouterInterface;
use Yii;

/**
 * Class Module
 * @package yii2-cmf
 * @author Gayazov Roman <gromver5@gmail.com>
 */
class Module extends \yii\base\Module implements MenuRouterInterface, DesktopInterface
{
    public $controllerNamespace = 'gromver\cmf\backend\modules\news\controllers';
    public $defaultRoute = 'category';
    public $desktopOrder = 4;

    /**
     * @inheritdoc
     */
    public function getDesktopItem()
    {
        return [
            'label' => Yii::t('gromver.cmf', 'News'),
            'links' => [
                ['label' => Yii::t('gromver.cmf', 'Categories'), 'url' => ['/cmf/news/category']],
                ['label' => Yii::t('gromver.cmf', 'Posts'), 'url' => ['/cmf/news/post']],
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function getMenuRoutes()
    {
        return [
            'label' => Yii::t('gromver.cmf', 'News'),
            'routers' => [
                ['label' => Yii::t('gromver.cmf', 'Post View'), 'url' => ['/cmf/news/post/select']],
                ['label' => Yii::t('gromver.cmf', 'Category View'), 'url' => ['/cmf/news/category/select']],
                ['label' => Yii::t('gromver.cmf', 'All Posts'), 'route' => 'cmf/news/post/index'],
            ]
        ];
    }
}
