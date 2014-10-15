<?php
/**
 * @link https://github.com/menst/yii2-cms.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/menst/yii2-cms/blob/master/LICENSE
 * @package yii2-cms
 * @version 1.0.0
 */

namespace menst\cms\common\helpers;

use Yii;
use yii\base\Exception;
use yii\base\Module;
use yii\base\Object;
use yii\caching\Cache;
use yii\caching\ExpressionDependency;
use yii\di\Instance;

/**
 * Class ModuleQuery
 * @package yii2-cms
 * @author Gayazov Roman <m.e.n.s.t@yandex.ru>
 */
class ModuleQuery extends Object {
    const CACHE_KEY = __CLASS__;
    //методы слияния результатов функции execute
    const AGGREGATE_MERGE = 1;    //слияние с использованием array_merge, применимо если все результаты являются массивами
    const AGGREGATE_PUSH = 2;     //добавление результатов Push методом

    /**
     * @var Cache
     */
    public $cache;
    public $cacheDuration;
    public $cacheDependency;

    /**
     * @var Module корневой модуль с которого начинать поиск
     */
    private $_nodeOf;
    /**
     * @var integer глубина поиска
     */
    private $_depth;
    /**
     * @var string имя интерфейса, класса и трейта которое должны наследовать искомые модули
     */
    private $_implement;
    /**
     * @var string название своиства модуля по которому будет производится сортировка
     */
    private $_orderBy;
    /**
     * @var integer SORT_ASC | SORT_DESC порядок сортировки
     */
    private $_sort;

    /**
     * @return static
     */
    public static function instance()
    {
        return Yii::createObject(get_called_class());//new static();
    }


    public function nodeOf(Module $module)
    {
        $this->_nodeOf = $module;

        return $this;
    }

    public function depth($value)
    {
        $this->_depth = $value;

        return $this;
    }

    public function implement($className)
    {
        $this->_implement = $className;

        return $this;
    }

    public function orderBy($orderBy, $sort = SORT_ASC)
    {
        $this->_orderBy = $orderBy;
        $this->_sort = $sort;

        return $this;
    }

    public function cache(Cache $cache)
    {
        $this->cache = $cache;

        return $this;
    }

    public function cacheDuration($cacheDuration)
    {
        $this->cacheDuration = $cacheDuration;

        return $this;
    }

    public function cacheDependency($cacheDependency)
    {
        $this->cacheDependency = $cacheDependency;

        return $this;
    }

    public function invoke($method, $params = [])
    {
        foreach ($this->find() as $moduleId) {
            $module = Yii::$app->getModule($moduleId);
            call_user_func_array([$module, $method], $params);
        }
    }

    public function execute($method, $params = [], $aggregate = self::AGGREGATE_PUSH)
    {
        if ($this->cache) {
            $cacheKey = [$this->getFindCacheKey(), $method, $params, $aggregate];
            if (($result = $this->cache->get($cacheKey)) === false) {
                $result = $this->executeModules($this->find(), $method, $params, $aggregate);
                $this->cache->set($cacheKey, $result, $this->cacheDuration, $this->cacheDependency);
            }

            return $result;
        }
        return $this->executeModules($this->find(), $method, $params, $aggregate);
    }

    private function executeModules($modules, $method, $params, $aggregate = self::AGGREGATE_PUSH)
    {
        $result = [];
        foreach ($modules as $moduleId) {
            $module = Yii::$app->getModule($moduleId);
            if ($aggregate === self::AGGREGATE_MERGE) {
                $result = array_merge($result, call_user_func_array([$module, $method], $params));
            } else {
                $result[] = call_user_func_array([$module, $method], $params);
            }
        }

        return $result;
    }

    /**
     * @return string[] Module Id's
     */
    public function find()
    {
        if ($this->cache) {
            $cacheKey = $this->getFindCacheKey();
            if (($result = $this->cache->get($cacheKey)) === false) {
                $modules = $this->findModules($this->_nodeOf ? $this->_nodeOf : Yii::$app);
                if ($this->_orderBy) {
                    usort($modules, [$this, 'compareModules']);
                }
                $result = $this->extractModuleIds($modules);
                $this->cache->set($cacheKey, $result, $this->cacheDuration, $this->cacheDependency);
            }

            return $result;
        }

        $modules = $this->findModules($this->_nodeOf ? $this->_nodeOf : Yii::$app);
        if ($this->_orderBy) {
            usort($modules, [$this, 'compareModules']);
        }
        return $this->extractModuleIds($modules);
    }

    private function getFindCacheKey()
    {
        return [self::CACHE_KEY, Yii::$app->name, $this->_implement, $this->_orderBy, $this->_sort];
    }

    /**
     * @param $parentModule Module
     */
    private function findModules($parentModule, $level = 1)
    {
        /** @var Module[] $modules */
        $modules = [];
        if (isset($this->_depth) && $this->_depth < $level) {
            return $modules;
        }

        foreach ($parentModule->getModules() as $name => $config) {
            $module = $parentModule->getModule($name);
            if ($this->_implement === null) {
                $modules[] = $module;
            } elseif(is_a($module, $this->_implement)) {
                $modules[] = $module;
            }

            $modules = array_merge($modules, $this->findModules($module, $level + 1));
        }

        return $modules;
    }

    /**
     * @param $a Module
     * @param $b Module
     * @return int
     */
    protected function compareModules($a, $b)
    {
        $aOrder = $a->canGetProperty($this->_orderBy) ? $a->{$this->_orderBy} : null;
        $bOrder = $b->canGetProperty($this->_orderBy) ? $b->{$this->_orderBy} : null;

        if ($bOrder === null) {
            return -1;
        }

        if ($aOrder === null) {
            return 1;
        }

        $result = $aOrder < $bOrder ? -1 : 1;
        return $this->_sort === SORT_DESC ? -1 * $result : $result;
    }

    /**
     * @param $modules Module[]
     */
    protected function extractModuleIds(&$modules)
    {
        return array_map(function($module) {
            /** @var Module $module */
            return $module->getUniqueId();
        }, $modules);
    }


} 