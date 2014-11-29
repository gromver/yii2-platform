<?php
/**
 * @link https://github.com/gromver/yii2-cmf.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/gromver/yii2-grom/blob/master/LICENSE
 * @package yii2-cmf
 * @version 1.0.0
 */

namespace gromver\platform\backend\modules\tag;

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
    public $controllerNamespace = 'gromver\platform\backend\modules\tag\controllers';
    public $desktopOrder = 7;

    /**
     * @inheritdoc
     */
    public function getDesktopItem()
    {
        return [
            'label' => Yii::t('gromver.platform', 'Tag'),
            'links' => [
                ['label' => Yii::t('gromver.platform', 'Tags'), 'url' => ['/grom/tag/default/index']],
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function getMenuRoutes()
    {
        return [
            'label' => Yii::t('gromver.platform', 'Tag'),
            'routers' => [
                ['label' => Yii::t('gromver.platform', 'Tag Cloud'), 'route' => 'grom/tag/default/index'],
            ]
        ];
    }
}
