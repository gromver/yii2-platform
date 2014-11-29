<?php
/**
 * @link https://github.com/gromver/yii2-cmf.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/gromver/yii2-grom/blob/master/LICENSE
 * @package yii2-cmf
 * @version 1.0.0
 */

namespace gromver\platform\backend\modules\main;

use gromver\modulequery\ModuleQuery;
use gromver\platform\common\models\Table;
use gromver\platform\backend\interfaces\DesktopInterface;
use gromver\platform\backend\interfaces\MenuRouterInterface;
use Yii;
use yii\base\BootstrapInterface;
use yii\caching\ExpressionDependency;
use yii\helpers\ArrayHelper;


/**
 * Class Module
 * @package yii2-cmf
 * @author Gayazov Roman <gromver5@gmail.com>
 *
 * @property string $siteName
 * @property bool $isEditMode
 */
class Module extends \yii\base\Module implements DesktopInterface, MenuRouterInterface, BootstrapInterface
{
    public $controllerNamespace = 'gromver\platform\backend\modules\main\controllers';

    public $paramsPath = '@common/config/grom';

    public $desktopOrder = 1;

    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        $app->set($this->id, $this);

        Yii::$container->set('gromver\models\fields\EditorField', ['controller' => 'grom/media/manager']);
        Yii::$container->set('gromver\models\fields\MediaField', ['controller' => 'grom/media/manager']);
        Yii::$container->set('gromver\modulequery\ModuleQuery', [
            'cache' => $app->cache,
            'cacheDependency' => new ExpressionDependency(['expression' => '\Yii::$app->getModulesHash()'])
        ]);

        Table::bootstrap();


        ModuleQuery::instance()->implement('\gromver\platform\common\interfaces\BootstrapInterface')->invoke('bootstrap', [$app]);
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->initI18N();

        $params = @include Yii::getAlias($this->paramsPath . '/params.php');

        if (is_array($params)) {
            $this->params = ArrayHelper::merge($params, $this->params);
        }
    }

    public function initI18N()
    {
        Yii::$app->i18n->translations['gromver.*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@gromver/platform/backend/messages',
        ];
    }

    /**
     * @inheritdoc
     */
    public function getDesktopItem()
    {
        return [
            'label' => Yii::t('gromver.platform', 'System'),
            'links' => [
                ['label' => Yii::t('gromver.platform', 'Site Map'), 'url' => ['/grom/default/index']],
                ['label' => Yii::t('gromver.platform', 'Settings'), 'url' => ['/grom/default/params']],
                ['label' => Yii::t('gromver.platform', 'Flush Cache'), 'url' => ['/grom/default/flush-cache']],
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function getMenuRoutes()
    {
        return [
            'label' => Yii::t('gromver.platform', 'System'),
            'routers' => [
                //['label' => Yii::t('gromver.platform', 'Sitemap'), 'route' => 'grom/default/sitemap'/*, 'icon' => '<i class="glyphicon glyphicon-cog"></i>'*/],
                ['label' => Yii::t('gromver.platform', 'Contact Form'), 'route' => 'grom/default/contact'/*, 'icon' => '<i class="glyphicon glyphicon-cog"></i>'*/]
            ]
        ];
    }

    public function getIsEditMode()
    {
        return true;
    }

    public function getSiteName()
    {
        return !empty($this->params['siteName']) ? $this->params['siteName'] : Yii::$app->name;
    }
}
