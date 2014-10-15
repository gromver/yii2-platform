<?php
/**
 * @link https://github.com/menst/yii2-cms.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/menst/yii2-cms/blob/master/LICENSE
 * @package yii2-cms
 * @version 1.0.0
 */

namespace menst\cms\backend\modules\media;

use menst\cms\backend\interfaces\DesktopInterface;
use Yii;

/**
 * Class Module
 * @package yii2-cms
 * @author Gayazov Roman <m.e.n.s.t@yandex.ru>
 */
class Module extends \yii\base\Module implements DesktopInterface
{
    public $controllerNamespace = 'menst\cms\backend\modules\media\controllers';
    /**
     * @var array elFinder manager controller config
     * @see \mihaildev\elfinder\Controller
     */
    public $elFinderConfig;
    public $desktopOrder = 3;

    public function init()
    {
        parent::init();

        if (!isset($this->elFinderConfig)) {
            $this->elFinderConfig = [
                'access' => ['read'],
                'roots' => [
                    [
                        'path' => 'files/global',
                        'baseUrl' => '',
                        'basePath' => '@frontend/web',
                        'name' => Yii::t('menst.cms', 'Global'),
                        'access' => ['write' => 'update']
                    ],
                    [
                        'class' => 'mihaildev\elfinder\UserPath',
                        'path' => 'files/user_{id}',
                        'name' => Yii::t('menst.cms', 'My Documents'),
                        'access' => ['read' => 'read', 'write' => 'update']
                    ]
                ]
            ];
        }
        // custom initialization code goes here

        $this->controllerMap = [
            'manager' => array_merge(['class' => 'mihaildev\elfinder\Controller'], $this->elFinderConfig)
        ];
    }

    /**
     * @inheritdoc
     */
    public function getDesktopItem()
    {
        return [
            'label' => Yii::t('menst.cms', 'Media'),
            'links' => [
                ['label' => Yii::t('menst.cms', 'Media Manager'), 'url' => ['/cms/media/default/index']]
            ]
        ];
    }
}
