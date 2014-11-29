<?php
/**
 * @link https://github.com/gromver/yii2-cmf.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/gromver/yii2-grom/blob/master/LICENSE
 * @package yii2-cmf
 * @version 1.0.0
 */

namespace gromver\platform\backend\modules\menu;

use gromver\platform\backend\interfaces\DesktopInterface;
use Yii;

/**
 * Class Module
 * @package yii2-cmf
 * @author Gayazov Roman <gromver5@gmail.com>
 */
class Module extends \yii\base\Module implements DesktopInterface
{
    public $controllerNamespace = 'gromver\platform\backend\modules\menu\controllers';
    public $defaultRoute = 'item';
    public $desktopOrder = 4;

    /**
     * @inheritdoc
     */
    public function getDesktopItem()
    {
        return [
            'label' => Yii::t('gromver.platform', 'Menu'),
            'links' => [
                ['label' => Yii::t('gromver.platform', 'Menu Types'), 'url' => ['/grom/menu/type']],
                ['label' => Yii::t('gromver.platform', 'Menu Items'), 'url' => ['/grom/menu/item']],
            ]
        ];
    }
}
