<?php
/**
 * @link https://github.com/gromver/yii2-cms.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/gromver/yii2-cmf/blob/master/LICENSE
 * @package yii2-cms
 * @version 1.0.0
 */

namespace gromver\cmf\frontend\components;

use gromver\cmf\common\components\UrlManager;
use gromver\modulequery\ModuleQuery;
use gromver\cmf\common\models\MenuItem;
use Yii;
use yii\base\Component;
use yii\web\ForbiddenHttpException;
use yii\web\Request;
use yii\web\UrlRuleInterface;
use yii\web\View;


/**
 * Class MenuManager
 * Теория - есть 2 случая применения меню
 * 1.1 когда путь к меню(MenuItem::path) совпадает с \yii\web\Request::getPathInfo(),
 * такой пункт меню считается активным и прерывает обработку запроса отдавая роут и параметры распарсенные из MenuItem::link
 * 1.2 здесь есть частный случай когда для наибольшей части \yii\web\Request::getPathInfo() найдено совпадение пункта меню(MenuItem::path),
 * такой пункт меню не может использоватся в качестве поставщика роута (MenuItem::route), но тем не менее считается активным,
 * данный запрос необходимо обработать с учетом роута и параметров найденного пункта меню и оставшейся части \yii\web\Request::getPathInfo() и \yii\web\Request::getQueryParams()
 * 2. Независимо от пунктов 1 и 1.2 для пунктов меню с типом MenuItem::LINK_HREF ищем все пункты у которых MenuItem::link совпадает c Request::getUrl()
 * @package yii2-cms
 * @author Gayazov Roman <gromver5@gmail.com>
 */
class MenuManager extends Component implements UrlRuleInterface
{
    const CACHE_KEY = __CLASS__;

    /**
     * @var MenuItem
     */
    private $_menu;
    private $_maps = [];
    private $_activeMenuIds = [];        //айдишники всех пунктов меню соответсвующих 1.1, 1.2 и 2.

    const EVENT_PARSE_REQUEST = 'parseRequest';
    const EVENT_CREATE_URL = 'createUrl';

    public function behaviors()
    {
        return ModuleQuery::instance()->implement('\gromver\cmf\frontend\interfaces\MenuUrlRuleInterface')->execute('getMenuUrlRuleBehavior');
    }


    /**
     * @param null $language
     * @return MenuMap
     */
    public function getMenuMap($language = null)
    {
        $language or $language = Yii::$app->language;
        if(!isset($this->_maps[$language]))
            $this->_maps[$language] = Yii::createObject([
                'class' => MenuMap::className(),
                'language' => $language,
            ]);

        return $this->_maps[$language];
    }

    public function getActiveMenu()
    {
        return $this->_menu;
    }

    public function getActiveMenuIds()
    {
        return $this->_activeMenuIds;
    }

    /**
     * Parses the given request and returns the corresponding route and parameters.
     * @param \yii\web\UrlManager $manager the URL manager
     * @param Request $request the request component
     * @return array|bool the parsing result. The route and the parameters are returned as an array.
     * @throws ForbiddenHttpException
     */
    public function parseRequest($manager, $request)
    {
        if (!($pathInfo = $request->getPathInfo() or $pathInfo = $this->getMenuMap()->getMainPagePath())) {
            return false;
        }
        //Пункт 2
        $this->_activeMenuIds = array_keys($this->getMenuMap()->getLinks(), $request->getUrl());

        //Пункт 1.1
        if ($this->_menu = $this->getMenuMap()->getMenuByPath($pathInfo)) {
            $this->_activeMenuIds[] = $this->_menu->id;
            $this->_menu->setContext(MenuItem::CONTEXT_PROPER);
            //при полном совпадении метаданные меню перекрывают метаднные контроллера
            Yii::$app->getView()->on(View::EVENT_BEGIN_PAGE, [$this, 'applyMetaData']);
        }
        //Пункт 1.2
        elseif($this->_menu = $this->getMenuMap()->getApplicableMenuByPath($pathInfo)) {
            $this->_activeMenuIds[] = $this->_menu->id;
            $this->_menu->setContext(MenuItem::CONTEXT_APPLICABLE);
            $this->applyMetaData();
        } else
            return false;

        //Проверка на доступ к пунтку меню
        if (!empty($this->_menu->access_rule) && !Yii::$app->user->can($this->_menu->access_rule)) {
            if (Yii::$app->user->getIsGuest()) {
                Yii::$app->user->loginRequired();
            } else {
                throw new ForbiddenHttpException(Yii::t('menst.cms', 'You have no rights for access to this section of the site.'));
            }
        }

        if ($this->_menu->getContext() === MenuItem::CONTEXT_PROPER) {
            return $this->_menu->parseUrl();
        } else {
            $requestRoute = substr($pathInfo, mb_strlen($this->_menu->path) + 1);
            list($menuRoute, $menuParams) = $this->_menu->parseUrl();
            $event = new MenuUrlRuleEvent([
                'menuMap' => $this->getMenuMap(),
                'menuRoute' => $menuRoute,
                'menuParams' => $menuParams,
                'requestRoute' => $requestRoute,
                'requestParams' => $request->getQueryParams()
            ]);
            $this->trigger(self::EVENT_PARSE_REQUEST, $event);
            return $event->result;
        }
    }
    /**
     * Creates a URL according to the given route and parameters.
     * @param UrlManager $manager the URL manager
     * @param string $route the route. It should not have slashes at the beginning or the end.
     * @param array $params the parameters
     * @return string|boolean the created URL, or false if this rule cannot be used for creating this URL.
     */
    public function createUrl($manager, $route, $params)
    {
        $language = $manager->getLanguageContext();

        if($path = $this->getMenuMap($language)->getMenuPathByRoute(MenuItem::toRoute($route, $params)))
            return $path;

        $event = new MenuUrlRuleEvent([
            'menuMap' => $this->getMenuMap($language),
            'requestRoute' => $route,
            'requestParams' => $params,
        ]);
        $this->trigger(self::EVENT_CREATE_URL, $event);
        return $event->result;
    }

    public function applyMetaData()
    {
        if ($this->_menu->metakey) {
            Yii::$app->getView()->registerMetaTag(['name' => 'keywords', 'content' => $this->_menu->metakey], 'keywords');
        }
        if ($this->_menu->metadesc) {
            Yii::$app->getView()->registerMetaTag(['name' => 'description', 'content' => $this->_menu->metadesc], 'description');
        }
        if ($this->_menu->robots) {
            Yii::$app->getView()->registerMetaTag(['name' => 'robots', 'content' => $this->_menu->robots], 'robots');
        }
    }
}