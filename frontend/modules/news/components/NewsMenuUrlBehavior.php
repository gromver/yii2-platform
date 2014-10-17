<?php
/**
 * @link https://github.com/menst/yii2-cms.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/menst/yii2-cms/blob/master/LICENSE
 * @package yii2-cms
 * @version 1.0.0
 */

namespace menst\cms\frontend\modules\news\components;

use menst\cms\common\models\Category;
use menst\cms\common\models\MenuItem;
use menst\cms\common\models\Post;
use menst\cms\common\models\Tag;
use menst\cms\frontend\behaviors\MenuUrlRuleBehavior;

/**
 * Class NewsMenuUrlBehavior
 * @package yii2-cms
 * @author Gayazov Roman <m.e.n.s.t@yandex.ru>
 */
class NewsMenuUrlBehavior extends MenuUrlRuleBehavior
{
    public $postSuffix = 'html';
    public $tagSuffix = 'tag';

    /**
     * @inheritdoc
     */
    public function parseRequest($event)
    {
        if ($event->menuRoute=='cms/news/category/view') {
            if (preg_match("#((.*)/)?(rss)$#", $event->requestRoute, $matches)) {
                //rss лента
                if ($menuCategory = Category::findOne($event->menuParams['id'])) {
                    $categoryPath = $matches[2];
                    $category = empty($categoryPath) ? $menuCategory : Category::findOne([
                        'path' => $menuCategory->path . '/' . $categoryPath,
                        'language' => $menuCategory->language
                    ]);
                    if ($category) {
                        $event->resolve(['cms/news/post/rss', ['category_id' => $category->id]]);
                    }
                }
            } elseif (preg_match("#((.*)/)?(\d{4})/(\d{1,2})/(\d{1,2})$#", $event->requestRoute, $matches)) {
                //новости за определенную дату
                if ($menuCategory = Category::findOne($event->menuParams['id'])) {
                    $categoryPath = $matches[2];
                    $year = $matches[3];
                    $month = $matches[4];
                    $day = $matches[5];
                    $category = empty($categoryPath) ? $menuCategory : Category::findOne([
                        'path' => $menuCategory->path . '/' . $categoryPath,
                        'language' => $menuCategory->language
                    ]);
                    if ($category) {
                        $event->resolve(['cms/news/post/day', ['category_id' => $category->id, 'year' => $year, 'month' => $month, 'day' => $day]]);
                    }
                }
            } elseif (preg_match("#((.*)/)?(([^/]+)\.{$this->postSuffix})$#", $event->requestRoute, $matches)) {
                //ищем пост
                if ($menuCategory = Category::findOne($event->menuParams['id'])) {
                    $categoryPath = $matches[2];
                    $postAlias = $matches[4];
                    $category = empty($categoryPath) ? $menuCategory : Category::findOne([
                            'path' => $menuCategory->path . '/' . $categoryPath,
                            'language' => $menuCategory->language
                        ]);
                    if ($category && $postId = Post::find()->select('id')->where(['alias' => $postAlias, 'category_id' => $category->id])->scalar()) {
                        $event->resolve(['cms/news/post/view', ['id' => $postId]]);
                    }
                }
            } elseif (preg_match("#((.*)/)?(([^/]+)\.{$this->tagSuffix})$#", $event->requestRoute, $matches)) {
                //ищем тэг
                if ($menuCategory = Category::findOne($event->menuParams['id'])) {
                    $categoryPath = $matches[2];
                    $tagAlias = $matches[4];
                    $category = empty($categoryPath) ? $menuCategory : Category::findOne([
                        'path' => $menuCategory->path . '/' . $categoryPath,
                        'language' => $menuCategory->language
                    ]);
                    if ($category && $tagId = Tag::find()->select('id')->where(['alias' => $tagAlias])->scalar()) {
                        $event->resolve(['cms/tag/default/posts', ['id' => $tagId, 'category_id' => $category->id]]);
                    }
                }
            } else {
                //ищем категорию
                if ($menuCategory = Category::findOne($event->menuParams['id'])) {
                    if ($category = Category::findOne([
                        'path' => $menuCategory->path . '/' . $event->requestRoute,
                        'language' => $menuCategory->language
                    ])) {
                        $event->resolve(['cms/news/category/view', ['id' => $category->id]]);
                    }
                }
            }
        }

        if ($event->menuRoute=='cms/news/post/index') {
            //маршрутизация для всех постов
            if ($event->requestRoute == 'rss') {
                $event->resolve(['cms/news/post/rss', []]);
            } elseif (preg_match("#^(\d{4})/(\d{1,2})/(\d{1,2})$#", $event->requestRoute, $matches)) {
                //новости за определенную дату
                $event->resolve(['cms/news/post/day', ['year' => $matches[1], 'month' => $matches[2], 'day' => $matches[3]]]);
            } elseif (preg_match("#^((.*)/)(([^/]+)\.{$this->postSuffix})$#", $event->requestRoute, $matches)) {
                //ищем пост
                $categoryPath = $matches[2];    //путь категории поста
                $postAlias = $matches[4];       //алиас поста
                $category = Category::findOne([
                    'path' => $categoryPath,
                    'language' => $event->menuMap->language
                ]);
                if ($category && $postId = Post::find()->select('id')->where(['alias' => $postAlias, 'category_id' => $category->id])->scalar()) {
                    $event->resolve(['cms/news/post/view', ['id' => $postId]]);
                }
            } elseif (preg_match("#^(([^/]+)\.{$this->tagSuffix})$#", $event->requestRoute, $matches)) {
                //ищем тег
                $tagAlias = $matches[2];
                if ($tagId = Tag::find()->select('id')->where(['alias' => $tagAlias, 'language' => $event->menuMap->language])->scalar()) {
                    $event->resolve(['cms/tag/default/posts', ['id' => $tagId]]);
                }
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function createUrl($event)
    {
        if ($event->requestRoute === 'cms/news/post/view') {
            //пытаемся найти пункт меню ссылющийся на данный пост
            if ($path = $event->menuMap->getMenuPathByRoute(MenuItem::toRoute('cms/news/post/view', ['id' => $event->requestParams['id']]))) {
                unset($event->requestParams['id'], $event->requestParams['category_id'], $event->requestParams['alias']);
                $event->resolve(MenuItem::toRoute($path, $event->requestParams));
                return;
            }
            //ищем пункт меню ссылающийся на категорию данного поста либо ее предков
            if (isset($event->requestParams['category_id']) && isset($event->requestParams['alias'])) {
                //можем привязаться к пункту меню ссылающемуся на категорию новостей к которой принадлежит данный пост(напрямую либо косвенно)
                if ($path = $this->findCategoryMenuPath($event->requestParams['category_id'], $event->menuMap)) {
                    $path .= '/' . $event->requestParams['alias'] . '.' . $this->postSuffix;
                    unset($event->requestParams['id'], $event->requestParams['category_id'], $event->requestParams['alias']);
                    $event->resolve(MenuItem::toRoute($path, $event->requestParams));
                    return;
                }
            }
            //привязываем ко всем новостям, если пукнт меню существует
            if ($path = $event->menuMap->getMenuPathByRoute('cms/news/post/index')) {
                $path .= '/' . Post::findOne($event->requestParams['id'])->category->path . '/' . $event->requestParams['alias'] . '.' . $this->postSuffix;
                unset($event->requestParams['id'], $event->requestParams['category_id'], $event->requestParams['alias']);
                $event->resolve(MenuItem::toRoute($path, $event->requestParams));
                return;
            }

            return;
        }

        if ($event->requestRoute === 'cms/news/category/view' && isset($event->requestParams['id'])) {
            if ($path = $this->findCategoryMenuPath($event->requestParams['id'], $event->menuMap)) {
                unset($event->requestParams['id']);
                $event->resolve(MenuItem::toRoute($path, $event->requestParams));
            }
        }

        if ($event->requestRoute === 'cms/news/post/day' && isset($event->requestParams['year'], $event->requestParams['month'], $event->requestParams['day'])) {
            if ($event->requestParams['category_id']) {
                $path = $this->findCategoryMenuPath($event->requestParams['category_id'], $event->menuMap);
            } else {
                $path = $event->menuMap->getMenuPathByRoute('cms/news/post/index');
            }

            if ($path) {
                $path .= "/{$event->requestParams['year']}/{$event->requestParams['month']}/{$event->requestParams['day']}";
                unset($event->requestParams['category_id'], $event->requestParams['year'], $event->requestParams['month'], $event->requestParams['day']);
                $event->resolve(MenuItem::toRoute($path, $event->requestParams));
            }
        }

        if ($event->requestRoute === 'cms/news/post/index') {
            if ($path = $event->menuMap->getMenuPathByRoute('cms/news/post/index')) {
                $event->resolve(MenuItem::toRoute($path, $event->requestParams));
            }
        }

        if ($event->requestRoute === 'cms/tag/default/posts') {
            //todo сделать привязку к категории новости по category_id здесь и в парсере
            //строим ссылку на основе пункта меню на категорию
            if (isset($event->requestParams['category_id']) && isset($event->requestParams['tag_alias']) && $path = $event->menuMap->getMenuPathByRoute(MenuItem::toRoute('cms/news/category/view', ['id' => $event->requestParams['category_id']]))) {
                $path .= '/' . $event->requestParams['tag_alias'] . '.' . $this->tagSuffix;
                unset($event->requestParams['tag_alias'], $event->requestParams['category_id'], $event->requestParams['tag_id']);
            }
            //строим ссылку на основе пункта меню на все новости
            if (isset($event->requestParams['tag_alias']) && $path = $event->menuMap->getMenuPathByRoute('cms/news/post/index')) {
                $path .= '/' . $event->requestParams['tag_alias'] . '.' . $this->tagSuffix;
                unset($event->requestParams['tag_alias'], $event->requestParams['category_id'], $event->requestParams['tag_id']);
            }

            if (isset($path)) {
                $event->resolve(MenuItem::toRoute($path, $event->requestParams));
            }
        }

        if ($event->requestRoute == 'cms/news/post/rss') {
            if (isset($event->requestParams['category_id'])) {
                if ($path = $this->findCategoryMenuPath($event->requestParams['category_id'], $event->menuMap)) {
                    unset($event->requestParams['category_id']);
                    $event->resolve(MenuItem::toRoute($path . '/rss', $event->requestParams));
                }
            } else {
                if ($path = $event->menuMap->getMenuPathByRoute('cms/news/post/index')) {
                    $event->resolve(MenuItem::toRoute($path . '/rss', $event->requestParams));
                }
            }
        }
    }

    private $_categoryPaths = [];

    /**
     * @param $categoryId
     * @param $menuMap \menst\cms\frontend\components\MenuMap
     * @return mixed
     */
    private function findCategoryMenuPath($categoryId, $menuMap)
    {
        if (!isset($this->_categoryPaths[$menuMap->language][$categoryId])) {
            if ($path = $menuMap->getMenuPathByRoute(MenuItem::toRoute('cms/news/category/view', ['id' => $categoryId]))) {
                $this->_categoryPaths[$menuMap->language][$categoryId] = $path;
            } elseif (($category = Category::findOne($categoryId)) && !$category->isRoot() && $path = $this->findCategoryMenuPath($category->parent_id, $menuMap)) {
                $this->_categoryPaths[$menuMap->language][$categoryId] = $path . '/' . $category->alias;
            } else {
                $this->_categoryPaths[$menuMap->language][$categoryId] = false;
            }
        }

        return $this->_categoryPaths[$menuMap->language][$categoryId];
    }
}