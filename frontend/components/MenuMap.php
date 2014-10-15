<?php
/**
 * @link https://github.com/menst/yii2-cms.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/menst/yii2-cms/blob/master/LICENSE
 * @package yii2-cms
 * @version 1.0.0
 */

namespace menst\cms\frontend\components;

use menst\cms\common\models\MenuItem;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Object;
use yii\di\Instance;
use yii\caching\Cache;

/**
 * Class MenuMap
 * @package yii2-cms
 * @author Gayazov Roman <m.e.n.s.t@yandex.ru>
 */
class MenuMap extends Object {
    const CACHE_KEY = __CLASS__;

    public $language;
    /**
     * @var Cache|string
     */
    public $cache;
    /**
     * @var integer
     */
    public $cacheDuration;
    /**
     * @var \yii\caching\Dependency
     */
    public $cacheDependency;

    private $_routes = [];
    private $_paths = [];
    private $_links = [];

    public function init()
    {
        if(!$this->language)
            throw new InvalidConfigException(get_called_class().'::language must be set.');

        if($this->cache) {
            /** @var Cache $cache */
            $this->cache = Instance::ensure($this->cache, Cache::className());
            $cacheKey = $this->language . self::CACHE_KEY;
            if ((list($paths, $routes, $links) = $this->cache->get($cacheKey)) === false) {
                //echo 'MenuMap CACHE GEN!';
                $this->createMap();
                $this->cache->set($cacheKey, [$this->_paths, $this->_routes, $this->_links], $this->cacheDuration, $this->cacheDependency);
            } else {
                $this->_paths = $paths;
                $this->_routes = $routes;
                $this->_links = $links;
            }
        } else
            $this->createMap();
    }

    private function createMap()
    {
        $items = MenuItem::find()->published()->language($this->language)->asArray()->all();
        //$this->createPathsMap($items, $this->_paths);

        foreach ($items as $item) {
            if($item['link_type'] == MenuItem::LINK_ROUTE) {
                $this->_paths[$item['id']] = $item['path'];
                $this->_routes[$item['id']] = $item['link'];
            } else
                $this->_links[$item['id']] = $item['link'];

        }
    }

    /*private function createPathsMap(&$items, &$result, $level=0, $prefix='')
    {
        $path = $prefix;
        while($item = each($items)) {
            $row = $item[1];
            if($row['level']>$level) {
                prev($items);
                $this->createPathsMap($items, $result, $row['level'], $path);
            } else if($row['level']<$level) {
                prev($items);
                return;
            } else
                if($row['type']==MenuItem::LINK_ROUTE) $result[$row['id']] = $path = $prefix ? $prefix.'/'.$row['alias'] : $row['alias'];
        }
    }*/

    /**
     * @param $path
     * @return MenuItem
     */
    public function getMenuByPath($path)
    {
        $menuId = array_search($path, $this->_paths);
        return $menuId ? MenuItem::findOne($menuId) : null;
    }

    /**
     * @param $route
     * @return MenuItem
     */
    public function getMenuByRoute($route)
    {
        $menuId = array_search($route, $this->_routes);
        return $menuId ? MenuItem::findOne($menuId) : null;
    }

    /**
     * @param $route
     * @return string
     */
    public function getMenuPathByRoute($route)
    {
        $menuId = array_search($route, $this->_routes);
        return $menuId ? $this->_paths[$menuId] : null;
    }

    /**
     * @param $menuId integer
     * @return string|null
     */
    public function getMenuPathById($menuId)
    {
        return @$this->_paths[$menuId];
    }

    /**
     * @param $route
     * @return MenuItem
     */
    public function getMenuByLink($link)
    {
        $menuId = array_search($link, $this->_links);
        return $menuId ? MenuItem::findOne($menuId) : null;
    }


    /**
     * @param $path
     * @return MenuItem
     */
    public function getApplicableMenuByPath($path)
    {
        $segments = explode('/', $path);
        $menu = null;
        do {
            array_pop($segments);
        } while(count($segments) && !$menu=$this->getMenuByPath(implode('/', $segments)));

        return $menu;
    }

    /**
     * @param $path
     * @return MenuItem
     */
    public function getMenuByPathRecursive($path)
    {
        $segments = explode('/', $path);
        $menu = null;
        while(count($segments) && !$menu=$this->getMenuByPath(implode('/', $segments))) {
            array_pop($segments);
        }
        return $menu;
    }

    public function getLinks()
    {
        return $this->_links;
    }

    public function getPaths()
    {
        return $this->_paths;
    }

    public function getRoutes()
    {
        return $this->_routes;
    }
}