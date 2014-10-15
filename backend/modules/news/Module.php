<?php
/**
 * @link https://github.com/menst/yii2-cms.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/menst/yii2-cms/blob/master/LICENSE
 * @package yii2-cms
 * @version 1.0.0
 */

namespace menst\cms\backend\modules\news;

use menst\cms\backend\interfaces\DesktopInterface;
use menst\cms\backend\interfaces\MenuRouterInterface;
use Yii;

/**
 * Class Module
 * @package yii2-cms
 * @author Gayazov Roman <m.e.n.s.t@yandex.ru>
 */
class Module extends \yii\base\Module implements MenuRouterInterface, DesktopInterface
{
    public $controllerNamespace = 'menst\cms\backend\modules\news\controllers';
    public $defaultRoute = 'category';
    public $desktopOrder = 4;

    /*public function init()
    {
        parent::init();

        // custom initialization code goes here
    }*/

    /**
     * @inheritdoc
     */
    public function getDesktopItem()
    {
        return [
            'label' => Yii::t('menst.cms', 'News'),
            'links' => [
                ['label' => Yii::t('menst.cms', 'Categories'), 'url' => ['/cms/news/category']],
                ['label' => Yii::t('menst.cms', 'Posts'), 'url' => ['/cms/news/post']],
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function getMenuRoutes()
    {
        return [
            'label' => Yii::t('menst.cms', 'News'),
            'routers' => [
                ['label' => Yii::t('menst.cms', 'Post View'), 'url' => ['/cms/news/post/select']],
                ['label' => Yii::t('menst.cms', 'Category View'), 'url' => ['/cms/news/category/select']],
                ['label' => Yii::t('menst.cms', 'All Posts'), 'route' => 'cms/news/post/index'],
            ]
        ];
    }
}
