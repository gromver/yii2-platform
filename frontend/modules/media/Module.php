<?php
/**
 * @link https://github.com/gromver/yii2-cmf.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/gromver/yii2-grom/blob/master/LICENSE
 * @package yii2-cmf
 * @version 1.0.0
 */

namespace gromver\platform\frontend\modules\media;

use gromver\platform\backend\interfaces\DesktopInterface;
use Yii;

/**
 * Class Module
 * @package yii2-cmf
 * @author Gayazov Roman <gromver5@gmail.com>
 */
class Module extends \yii\base\Module implements DesktopInterface
{
    public $controllerNamespace = 'gromver\platform\backend\modules\media\controllers';
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
                        'name' => Yii::t('gromver.platform', 'Global'),
                        'access' => ['write' => 'update']
                    ],
                    [
                        'class' => 'mihaildev\elfinder\UserPath',
                        'baseUrl' => '',
                        'basePath' => '@frontend/web',
                        'path' => 'files/user_{id}',
                        'name' => Yii::t('gromver.platform', 'My Documents'),
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
            'label' => Yii::t('gromver.platform', 'Media'),
            'links' => [
                ['label' => Yii::t('gromver.platform', 'Media Manager'), 'url' => ['/grom/media/default/index']]
            ]
        ];
    }
}
