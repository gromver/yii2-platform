<?php
/**
 * @link https://github.com/gromver/yii2-cmf.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/gromver/yii2-cmf/blob/master/LICENSE
 * @package yii2-cmf
 * @version 1.0.0
 */

namespace gromver\cmf\backend\modules\user;

use gromver\cmf\backend\interfaces\DesktopInterface;
use gromver\cmf\backend\interfaces\MenuRouterInterface;
use Yii;

/**
 * Class Module
 * @package yii2-cmf
 * @author Gayazov Roman <gromver5@gmail.com>
 */
class Module extends \yii\base\Module implements DesktopInterface, MenuRouterInterface
{
    public $controllerNamespace = 'gromver\cmf\backend\modules\user\controllers';
    public $allowDelete = true;   //позволяет удалять пользователей из БД, при условии что они уже имеют статус User::STATUS_DELETED
    public $userParamsClass = 'gromver\cmf\common\models\UserParams';
    public $desktopOrder = 8;

    /**
     * @inheritdoc
     */
    public function getDesktopItem()
    {
        return [
            'label' => Yii::t('gromver.cmf', 'User'),
            'links' => [
                ['label' => Yii::t('gromver.cmf', 'Users'), 'url' => ['/cmf/user/default/index']]
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function getMenuRoutes()
    {
        return [
            'label' => Yii::t('gromver.cmf', 'User'),
            'routers' => [
                ['label' => Yii::t('gromver.cmf', 'User Profile'), 'route' => 'cmf/user/default/update'],
            ]
        ];
    }
}
