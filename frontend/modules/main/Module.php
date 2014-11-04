<?php
/**
 * @link https://github.com/menst/yii2-cms.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/menst/yii2-cms/blob/master/LICENSE
 * @package yii2-cms
 * @version 1.0.0
 */

namespace menst\cms\frontend\modules\main;

use menst\cms\common\interfaces\SearchableInterface;
use menst\cms\common\models\MenuItem;
use menst\cms\common\models\Table;
use Yii;
use menst\cms\frontend\components\MenuManager;
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
class Module extends \yii\base\Module implements BootstrapInterface, SearchableInterface
{
    const SESSION_KEY_MODE = '__cms_mode';

    const MODE_EDIT = 'edit';
    const MODE_VIEW = 'view';

    public $controllerNamespace = '\menst\cms\frontend\modules\main\controllers';
    public $paramsPath = '@common/config/cms';
    public $blockModules = ['news', 'page', 'tag', 'search', 'user'];   //список модулей к которым нельзя попасть на прямую(cms/post/..., cms/page/...)

    private $_mode;

    /*public function init()
    {
        parent::init();

        // custom initialization code goes here
    }*/

    /**
     * Bootstrap method to be called during application bootstrap stage.
     * @param \yii\web\Application $app the application currently running
     */
    public function bootstrap($app)
    {
        Yii::$container->set('menst\models\fields\EditorField', [
            'controller' => $app->urlManagerBackend->createUrl(['cms/media/manager'])
        ]);
        Yii::$container->set('menst\models\fields\MediaField', [
            'controller' => $app->urlManagerBackend->createUrl(['cms/media/manager'])
        ]);
        Yii::$container->set('menst\cms\common\helpers\ModuleQuery', [
            'cache' => $app->cache,
            'cacheDependency' => new ExpressionDependency(['expression' => '\Yii::$app->getModulesHash()'])
        ]);
        Yii::$container->set('menst\cms\frontend\components\MenuMap', [
            'cache' => $app->cache,
            'cacheDependency' => Table::dependency(MenuItem::tableName())
        ]);

        /** @var MenuManager $manager */
        $manager = \Yii::createObject(MenuManager::className());
        $rules = [$manager];
        if (is_array($this->blockModules) && count($this->blockModules)) {
            $rules['cms/<module:(' . implode('|', $this->blockModules). ')><path:(/.*)?>'] = 'cms/default/page-not-found'; //блокируем доступ к контент модулям напрямую
        }

        $app->urlManager->addRules($rules, false); //вставляем в начало списка

        $app->set('menuManager', $manager);
        $app->set($this->id, $this);
    }

    public function init()
    {
        parent::init();

        $params = @include Yii::getAlias($this->paramsPath . '/params.php');

        if(is_array($params))
            $this->params = ArrayHelper::merge($params, $this->params);

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

    public function setMode($mode, $saveInSession = true)
    {
        $this->_mode = in_array($mode, self::modes()) ? $mode : self::MODE_VIEW;

        if($saveInSession)
            Yii::$app->session->set(self::SESSION_KEY_MODE, $mode);
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
