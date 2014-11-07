<?php
/**
 * @link https://github.com/gromver/yii2-cms.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/gromver/yii2-cmf/blob/master/LICENSE
 * @package yii2-cms
 * @version 1.0.0
 */

namespace gromver\cmf\backend\modules\tag;

use gromver\cmf\backend\interfaces\DesktopInterface;
use gromver\cmf\backend\interfaces\MenuRouterInterface;
use Yii;

/**
 * Class Module
 * @package yii2-cms
 * @author Gayazov Roman <gromver5@gmail.com>
 */
class Module extends \yii\base\Module implements MenuRouterInterface, DesktopInterface
{
    public $controllerNamespace = 'gromver\cmf\backend\modules\tag\controllers';
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
                ['label' => Yii::t('menst.cms', 'Tags'), 'url' => ['/cmf/tag/default/index']],
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
                ['label' => Yii::t('menst.cms', 'Tag Cloud'), 'route' => 'cmf/tag/default/index'],
            ]
        ];
    }
}
