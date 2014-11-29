<?php
/**
 * @link https://github.com/gromver/yii2-cmf.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/gromver/yii2-grom/blob/master/LICENSE
 * @package yii2-cmf
 * @version 1.0.0
 */

namespace gromver\platform\frontend\modules\main;

use gromver\platform\common\models\MenuItem;
use gromver\platform\common\models\Table;
use gromver\modulequery\ModuleQuery;
use Yii;
use gromver\platform\frontend\components\MenuManager;
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
class Module extends \yii\base\Module implements BootstrapInterface
{
    const SESSION_KEY_MODE = '__grom_mode';

    const MODE_EDIT = 'edit';
    const MODE_VIEW = 'view';

    public $controllerNamespace = '\gromver\platform\frontend\modules\main\controllers';
    public $paramsPath = '@common/config/grom';
    public $blockModules = ['news', 'page', 'tag', 'user'];   //список модулей к которым нельзя попасть на прямую(grom/post/..., grom/page/...)

    private $_mode;

    /**
     * Bootstrap method to be called during application bootstrap stage.
     * @param \yii\web\Application $app the application currently running
     */
    public function bootstrap($app)
    {
        $app->set($this->id, $this);

        Yii::$container->set('gromver\models\fields\EditorField', [
            'controller' => 'grom/media/manager'
        ]);
        Yii::$container->set('gromver\models\fields\MediaField', [
            'controller' => 'grom/media/manager'
        ]);
        Yii::$container->set('gromver\modulequery\ModuleQuery', [
            'cache' => $app->cache,
            'cacheDependency' => new ExpressionDependency(['expression' => '\Yii::$app->getModulesHash()'])
        ]);
        Yii::$container->set('gromver\platform\frontend\components\MenuMap', [
            'cache' => $app->cache,
            'cacheDependency' => Table::dependency(MenuItem::tableName())
        ]);

        /** @var MenuManager $manager */
        $manager = \Yii::createObject(MenuManager::className());
        $rules = [$manager];
        if (is_array($this->blockModules) && count($this->blockModules)) {
            $rules['grom/<module:(' . implode('|', $this->blockModules). ')><path:(/.*)?>'] = 'grom/default/page-not-found'; //блокируем доступ к контент модулям напрямую
        }

        $app->urlManager->addRules($rules, false); //вставляем в начало списка

        $app->set('menuManager', $manager);

        ModuleQuery::instance()->implement('\gromver\platform\common\interfaces\BootstrapInterface')->invoke('bootstrap', [$app]);
    }

    public function init()
    {
        parent::init();
        $this->initI18N();

        $params = @include Yii::getAlias($this->paramsPath . '/params.php');

        if (is_array($params)) {
            $this->params = ArrayHelper::merge($params, $this->params);
        }

        $view = Yii::$app->getView();
        $view->title = @$this->params['title'];
        if (!empty($this->params['keywords'])) {
            $view->registerMetaTag(['name' => 'keywords', 'content' => $this->params['keywords']], 'keywords');
        }
        if (!empty($this->params['description'])) {
            $view->registerMetaTag(['name' => 'description', 'content' => $this->params['description']], 'description');
        }
        if (!empty($this->params['robots'])) {
            $view->registerMetaTag(['name' => 'robots', 'content' => $this->params['robots']], 'robots');
        }
    }


    public function initI18N()
    {
        Yii::$app->i18n->translations['gromver.*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@gromver/platform/frontend/messages',
        ];
    }

    public function setMode($mode, $saveInSession = true)
    {
        $this->_mode = in_array($mode, self::modes()) ? $mode : self::MODE_VIEW;

        if ($saveInSession) {
            Yii::$app->session->set(self::SESSION_KEY_MODE, $mode);
        }
    }

    public function getMode()
    {
        if(!isset($this->_mode)) {
            $this->setMode(Yii::$app->session->get(self::SESSION_KEY_MODE, self::MODE_VIEW));
        }

        return $this->_mode;
    }

    public function getIsEditMode()
    {
        return $this->getMode() === self::MODE_EDIT;
    }

    public static function modes()
    {
        return [self::MODE_VIEW, self::MODE_EDIT];
    }

    public function getSiteName()
    {
        return !empty($this->params['siteName']) ? $this->params['siteName'] : Yii::$app->name;
    }
}
