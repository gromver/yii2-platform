<?php
/**
 * @link https://github.com/gromver/yii2-cmf.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/gromver/yii2-cmf/blob/master/LICENSE
 * @package yii2-cmf
 * @version 1.0.0
 */

namespace gromver\cmf\backend\modules\menu;

use gromver\cmf\backend\interfaces\DesktopInterface;
use Yii;

/**
 * Class Module
 * @package yii2-cmf
 * @author Gayazov Roman <gromver5@gmail.com>
 */
class Module extends \yii\base\Module implements DesktopInterface
{
    public $controllerNamespace = 'gromver\cmf\backend\modules\menu\controllers';
    public $defaultRoute = 'item';
    public $desktopOrder = 4;

    /**
     * @inheritdoc
     */
    public function getDesktopItem()
    {
        return [
            'label' => Yii::t('gromver.cmf', 'Menu'),
            'links' => [
                ['label' => Yii::t('gromver.cmf', 'Menu Types'), 'url' => ['/cmf/menu/type']],
                ['label' => Yii::t('gromver.cmf', 'Menu Items'), 'url' => ['/cmf/menu/item']],
            ]
        ];
    }
}
