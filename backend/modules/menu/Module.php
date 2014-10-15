<?php
/**
 * @link https://github.com/menst/yii2-cms.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/menst/yii2-cms/blob/master/LICENSE
 * @package yii2-cms
 * @version 1.0.0
 */

namespace menst\cms\backend\modules\menu;

use menst\cms\backend\interfaces\DesktopInterface;
use menst\cms\backend\interfaces\MenuRouterInterface;
use Yii;

/**
 * Class Module
 * @package yii2-cms
 * @author Gayazov Roman <m.e.n.s.t@yandex.ru>
 */
class Module extends \yii\base\Module implements DesktopInterface, MenuRouterInterface
{
    public $controllerNamespace = 'menst\cms\backend\modules\menu\controllers';
    public $defaultRoute = 'item';
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
            'label' => Yii::t('menst.cms', 'Menu'),
            'links' => [
                ['label' => Yii::t('menst.cms', 'Menu Types'), 'url' => ['/cms/menu/type']],
                ['label' => Yii::t('menst.cms', 'Menu Items'), 'url' => ['/cms/menu/item']],
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function getMenuRoutes()
    {
        return [
            'label' => Yii::t('menst.cms', 'Menu'),
            'routers' => [
                ['label' => Yii::t('menst.cms', 'Menu Alias'), 'url' => ['/cms/menu/item/select']],
            ]
        ];
    }
}
