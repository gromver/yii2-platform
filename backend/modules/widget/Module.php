<?php
/**
 * @link https://github.com/menst/yii2-cms.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/menst/yii2-cms/blob/master/LICENSE
 * @package yii2-cms
 * @version 1.0.0
 */

namespace menst\cms\backend\modules\widget;

use menst\cms\backend\interfaces\DesktopInterface;
use Yii;

/**
 * Class Module
 * @package yii2-cms
 * @author Gayazov Roman <m.e.n.s.t@yandex.ru>
 */
class Module extends \yii\base\Module implements DesktopInterface
{
    public $controllerNamespace = 'menst\cms\backend\modules\widget\controllers';
    public $desktopOrder = 10;

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
            'label' => Yii::t('menst.cms', 'Widget'),
            'links' => [
                ['label' => Yii::t('menst.cms', 'Configurations'), 'url' => ['/cms/widget/default/index']]
            ]
        ];
    }
}
