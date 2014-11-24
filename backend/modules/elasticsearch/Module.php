<?php
/**
 * @link https://github.com/gromver/yii2-cmf.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/gromver/yii2-cmf/blob/master/LICENSE
 * @package yii2-cmf
 * @version 1.0.0
 */

namespace gromver\cmf\backend\modules\elasticsearch;

use gromver\cmf\backend\interfaces\DesktopInterface;
use gromver\cmf\backend\interfaces\MenuRouterInterface;
use gromver\cmf\common\interfaces\BootstrapInterface;
use Yii;

/**
 * Class Module
 * В этом модуле можно кастомизировать дополнительные кдассы ActiveDocument поддерживаемые цмской
 * @package yii2-cmf
 * @author Gayazov Roman <gromver5@gmail.com>
 */
class Module extends \gromver\cmf\common\modules\elasticsearch\Module implements BootstrapInterface, DesktopInterface, MenuRouterInterface
{
    public $controllerNamespace = 'gromver\cmf\backend\modules\elasticsearch\controllers';
    public $desktopOrder = 6;

    /**
     * @inheritdoc
     */
    public function getDesktopItem()
    {
        return [
            'label' => Yii::t('gromver.cmf', 'Search'),
            'links' => [
                ['label' => Yii::t('gromver.cmf', 'Searching'), 'url' => ['/' . $this->getUniqueId() . '/default/index']],
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function getMenuRoutes()
    {
        return [
            'label' => Yii::t('gromver.cmf', 'Search'),
            'routers' => [
                ['label' => Yii::t('gromver.cmf', 'Searching'), 'route' =>  $this->getUniqueId() . '/default/index'],
            ]
        ];
    }
}
