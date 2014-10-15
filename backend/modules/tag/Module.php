<?php
/**
 * @link https://github.com/menst/yii2-cms.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/menst/yii2-cms/blob/master/LICENSE
 * @package yii2-cms
 * @version 1.0.0
 */

namespace menst\cms\backend\modules\tag;

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
    public $controllerNamespace = 'menst\cms\backend\modules\tag\controllers';
    public $desktopOrder = 7;

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
            'label' => Yii::t('menst.cms', 'Tag'),
            'links' => [
                ['label' => Yii::t('menst.cms', 'Tags'), 'url' => ['/cms/tag/default/index']],
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function getMenuRoutes()
    {
        return [
            'label' => Yii::t('menst.cms', 'Tag'),
            'routers' => [
                ['label' => Yii::t('menst.cms', 'Tag Cloud'), 'route' => 'cms/tag/default/index'],
            ]
        ];
    }
}
