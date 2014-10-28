<?php
/**
 * @link https://github.com/menst/yii2-cms.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/menst/yii2-cms/blob/master/LICENSE
 * @package yii2-cms
 * @version 1.0.0
 */

namespace menst\cms\backend\modules\main;

use menst\cms\common\helpers\ModuleQuery;
use menst\cms\common\interfaces\SearchableInterface;
use menst\cms\common\models\Table;
use menst\cms\backend\interfaces\DesktopInterface;
use menst\cms\backend\interfaces\MenuRouterInterface;
use Yii;
use yii\base\BootstrapInterface;
use yii\caching\ExpressionDependency;
use yii\helpers\ArrayHelper;


/**
 * Class Module
 * @package yii2-cms
 * @author Gayazov Roman <m.e.n.s.t@yandex.ru>
 *
 * @property string $siteName
 * @property bool $isEditMode
 */
class Module extends \yii\base\Module implements DesktopInterface, MenuRouterInterface, BootstrapInterface, SearchableInterface
{
    public $controllerNamespace = 'menst\cms\backend\modules\main\controllers';

    public $paramsPath = '@common/config/cms';

    public $desktopOrder = 1;

    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        Yii::$container->set('menst\models\fields\EditorField', ['controller' => 'cms/media/manager']);
        Yii::$container->set('menst\models\fields\MediaField', ['controller' => 'cms/media/manager']);
        Yii::$container->set('menst\cms\common\helpers\ModuleQuery', [
            'cache' => $app->cache,
            'cacheDependency' => new ExpressionDependency(['expression' => '\Yii::$app->getModulesHash()'])
        ]);

        ModuleQuery::instance()->implement('\menst\cms\common\interfaces\BootstrapInterface')/*->cache('cache')*/->invoke('bootstrap', [$app]);

        Table::bootstrap();

        $app->set($this->id, $this);

    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $params = @include Yii::getAlias($this->paramsPath . '/params.php');

        if(is_array($params))
            $this->params = ArrayHelper::merge($params, $this->params);
    }

    /**
     * @inheritdoc
     */
    public function getDesktopItem()
    {
        return [
            'label' => Yii::t('menst.cms', 'System'),
            'links' => [
                ['label' => Yii::t('menst.cms', 'Site Map'), 'url' => ['/cms/default/index']],
                ['label' => Yii::t('menst.cms', 'Settings'), 'url' => ['/cms/default/params']],
                ['label' => Yii::t('menst.cms', 'Flush Cache'), 'url' => ['/cms/default/flush-cache']],
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function getMenuRoutes()
    {
        return [
            'label' => Yii::t('menst.cms', 'System'),
            'routers' => [
                //['label' => Yii::t('menst.cms', 'Sitemap'), 'route' => 'cms/default/sitemap'/*, 'icon' => '<i class="glyphicon glyphicon-cog"></i>'*/],
                ['label' => Yii::t('menst.cms', 'Contact Form'), 'route' => 'cms/default/contact'/*, 'icon' => '<i class="glyphicon glyphicon-cog"></i>'*/]
            ]
        ];
    }

    public function getIsEditMode()
    {
        return true;
    }

    public function getDocumentClasses()
    {
        return [
            'menst\cms\common\models\search\Page',
            'menst\cms\common\models\search\Post',
            'menst\cms\common\models\search\Category',
        ];
    }

    public function getSiteName()
    {
        return !empty($this->params['siteName']) ? $this->params['siteName'] : Yii::$app->name;
    }
}
