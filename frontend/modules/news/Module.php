<?php
/**
 * @link https://github.com/gromver/yii2-cmf.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/gromver/yii2-grom/blob/master/LICENSE
 * @package yii2-cmf
 * @version 1.0.0
 */

namespace gromver\platform\frontend\modules\news;

use gromver\platform\frontend\interfaces\MenuRouterInterface;
use gromver\platform\frontend\modules\news\components\MenuRouterNews;

/**
 * Class Module
 * @package yii2-cmf
 * @author Gayazov Roman <gromver5@gmail.com>
 */
class Module extends \yii\base\Module implements MenuRouterInterface
{
    public $controllerNamespace = 'gromver\platform\frontend\modules\news\controllers';
    public $defaultRoute = 'post';
    public $rssPageSize = 50;

    public function getMenuRouter()
    {
        return MenuRouterNews::className();
    }
}
