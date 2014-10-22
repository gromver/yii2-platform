<?php
/**
 * @link https://github.com/menst/yii2-cms.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/menst/yii2-cms/blob/master/LICENSE
 * @package yii2-cms
 * @version 1.0.0
 */

namespace menst\cms\backend\modules\user;

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
    public $controllerNamespace = 'menst\cms\backend\modules\user\controllers';
    public $allowDelete = true;   //позволяет удалять пользователей из БД, при условии что они уже имеют статус User::STATUS_DELETED
    public $userParamsClass = 'menst\cms\common\models\UserParams';
    public $desktopOrder = 8;

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
            'label' => Yii::t('menst.cms', 'User'),
            'links' => [
                ['label' => Yii::t('menst.cms', 'Users'), 'url' => ['/cms/user/default/index']]
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function getMenuRoutes()
    {
        return [
            'label' => Yii::t('menst.cms', 'User'),
            'routers' => [
                ['label' => Yii::t('menst.cms', 'User Profile'), 'route' => 'cms/user/default/update'],
            ]
        ];
    }
}
