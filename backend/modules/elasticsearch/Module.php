<?php
/**
 * @link https://github.com/gromver/yii2-cmf.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/gromver/yii2-grom/blob/master/LICENSE
 * @package yii2-cmf
 * @version 1.0.0
 */

namespace gromver\platform\backend\modules\elasticsearch;

use gromver\platform\backend\interfaces\DesktopInterface;
use gromver\platform\backend\interfaces\MenuRouterInterface;
use gromver\platform\common\interfaces\BootstrapInterface;
use Yii;

/**
 * Class Module
 * В этом модуле можно кастомизировать дополнительные кдассы ActiveDocument поддерживаемые цмской
 * @package yii2-cmf
 * @author Gayazov Roman <gromver5@gmail.com>
 */
class Module extends \gromver\platform\common\modules\elasticsearch\Module implements BootstrapInterface, DesktopInterface, MenuRouterInterface
{
    public $controllerNamespace = 'gromver\platform\backend\modules\elasticsearch\controllers';
    public $desktopOrder = 6;

    /**
     * @inheritdoc
     */
    public function getDesktopItem()
    {
        return [
            'label' => Yii::t('gromver.platform', 'Search'),
            'links' => [
                ['label' => Yii::t('gromver.platform', 'Search'), 'url' => ['/' . $this->getUniqueId() . '/default/index']],
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function getMenuRoutes()
    {
        return [
            'label' => Yii::t('gromver.platform', 'Search'),
            'routers' => [
                ['label' => Yii::t('gromver.platform', 'Search'), 'route' =>  $this->getUniqueId() . '/default/index'],
            ]
        ];
    }
}
