<?php
/**
 * @link https://github.com/gromver/yii2-cmf.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/gromver/yii2-grom/blob/master/LICENSE
 * @package yii2-cmf
 * @version 1.0.0
 */

namespace gromver\platform\backend\modules\news;

use gromver\platform\backend\interfaces\DesktopInterface;
use gromver\platform\backend\interfaces\MenuRouterInterface;
use Yii;

/**
 * Class Module
 * @package yii2-cmf
 * @author Gayazov Roman <gromver5@gmail.com>
 */
class Module extends \yii\base\Module implements MenuRouterInterface, DesktopInterface
{
    public $controllerNamespace = 'gromver\platform\backend\modules\news\controllers';
    public $defaultRoute = 'category';
    public $desktopOrder = 4;

    /**
     * @inheritdoc
     */
    public function getDesktopItem()
    {
        return [
            'label' => Yii::t('gromver.platform', 'News'),
            'links' => [
                ['label' => Yii::t('gromver.platform', 'Categories'), 'url' => ['/grom/news/category']],
                ['label' => Yii::t('gromver.platform', 'Posts'), 'url' => ['/grom/news/post']],
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function getMenuRoutes()
    {
        return [
            'label' => Yii::t('gromver.platform', 'News'),
            'routers' => [
                ['label' => Yii::t('gromver.platform', 'Post View'), 'url' => ['/grom/news/post/select']],
                ['label' => Yii::t('gromver.platform', 'Category View'), 'url' => ['/grom/news/category/select']],
                ['label' => Yii::t('gromver.platform', 'All Posts'), 'route' => 'grom/news/post/index'],
            ]
        ];
    }
}
