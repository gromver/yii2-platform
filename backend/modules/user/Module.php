<?php
/**
 * @link https://github.com/gromver/yii2-cmf.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/gromver/yii2-grom/blob/master/LICENSE
 * @package yii2-cmf
 * @version 1.0.0
 */

namespace gromver\platform\backend\modules\user;

use gromver\platform\backend\interfaces\DesktopInterface;
use gromver\platform\backend\interfaces\MenuRouterInterface;
use Yii;

/**
 * Class Module
 * @package yii2-cmf
 * @author Gayazov Roman <gromver5@gmail.com>
 */
class Module extends \yii\base\Module implements DesktopInterface, MenuRouterInterface
{
    public $controllerNamespace = 'gromver\platform\backend\modules\user\controllers';
    public $allowDelete = true;   //позволяет удалять пользователей из БД, при условии что они уже имеют статус User::STATUS_DELETED
    public $userParamsClass = 'gromver\platform\common\models\UserParams';
    public $desktopOrder = 8;

    /**
     * @inheritdoc
     */
    public function getDesktopItem()
    {
        return [
            'label' => Yii::t('gromver.platform', 'Users'),
            'links' => [
                ['label' => Yii::t('gromver.platform', 'Users'), 'url' => ['/grom/user/default/index']]
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function getMenuRoutes()
    {
        return [
            'label' => Yii::t('gromver.platform', 'Users'),
            'routers' => [
                ['label' => Yii::t('gromver.platform', 'User Profile'), 'route' => 'grom/user/default/update'],
            ]
        ];
    }
}
