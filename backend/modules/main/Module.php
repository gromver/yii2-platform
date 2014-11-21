<?php
/**
 * @link https://github.com/gromver/yii2-cmf.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/gromver/yii2-cmf/blob/master/LICENSE
 * @package yii2-cmf
 * @version 1.0.0
 */

namespace gromver\cmf\backend\modules\main;

use gromver\modulequery\ModuleQuery;
use gromver\cmf\common\interfaces\SearchableInterface;
use gromver\cmf\common\models\Table;
use gromver\cmf\backend\interfaces\DesktopInterface;
use gromver\cmf\backend\interfaces\MenuRouterInterface;
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
class Module extends \yii\base\Module implements DesktopInterface, MenuRouterInterface, BootstrapInterface, SearchableInterface
{
    public $controllerNamespace = 'gromver\cmf\backend\modules\main\controllers';

    public $paramsPath = '@common/config/cmf';

    public $desktopOrder = 1;

    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        Yii::$container->set('gromver\models\fields\EditorField', ['controller' => 'cmf/media/manager']);
        Yii::$container->set('gromver\models\fields\MediaField', ['controller' => 'cmf/media/manager']);
        Yii::$container->set('gromver\modulequery\ModuleQuery', [
            'cache' => $app->cache,
            'cacheDependency' => new ExpressionDependency(['expression' => '\Yii::$app->getModulesHash()'])
        ]);

        ModuleQuery::instance()->implement('\gromver\cmf\common\interfaces\BootstrapInterface')->invoke('bootstrap', [$app]);

        Table::bootstrap();

        $app->set($this->id, $this);

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
            'basePath' => '@gromver/cmf/backend/messages',
            //'forceTranslation' => true,
        ];
    }

    /**
     * @inheritdoc
     */
    public function getDesktopItem()
    {
        return [
            'label' => Yii::t('gromver.cmf', 'System'),
            'links' => [
                ['label' => Yii::t('gromver.cmf', 'Site Map'), 'url' => ['/cmf/default/index']],
                ['label' => Yii::t('gromver.cmf', 'Settings'), 'url' => ['/cmf/default/params']],
                ['label' => Yii::t('gromver.cmf', 'Flush Cache'), 'url' => ['/cmf/default/flush-cache']],
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function getMenuRoutes()
    {
        return [
            'label' => Yii::t('gromver.cmf', 'System'),
            'routers' => [
                //['label' => Yii::t('gromver.cmf', 'Sitemap'), 'route' => 'cmf/default/sitemap'/*, 'icon' => '<i class="glyphicon glyphicon-cog"></i>'*/],
                ['label' => Yii::t('gromver.cmf', 'Contact Form'), 'route' => 'cmf/default/contact'/*, 'icon' => '<i class="glyphicon glyphicon-cog"></i>'*/]
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
            'gromver\cmf\common\models\search\Page',
            'gromver\cmf\common\models\search\Post',
            'gromver\cmf\common\models\search\Category',
        ];
    }

    public function getSiteName()
    {
        return !empty($this->params['siteName']) ? $this->params['siteName'] : Yii::$app->name;
    }
}
