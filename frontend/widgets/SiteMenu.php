<?php
/**
 * @link https://github.com/gromver/yii2-cmf.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/gromver/yii2-cmf/blob/master/LICENSE
 * @package yii2-cmf
 * @version 1.0.0
 */

namespace gromver\cmf\frontend\widgets;

use gromver\cmf\common\widgets\Widget;
use gromver\cmf\common\models\MenuItem;
use gromver\cmf\common\models\Table;
use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

/**
 * Class SiteMenu
 * @package yii2-cmf
 * @author Gayazov Roman <gromver5@gmail.com>
 */
class SiteMenu extends Widget {
    /**
     * MenuTypeId or MenuTypeId:MenuTypeAlias
     * @var string
     * @type modal
     * @url /cmf/default/select-menu
     */
    public $type;
    /**
     * @type list
     * @items languages
     */
    public $language;
    /**
     * @type yesno
     */
    public $showInaccessible = true;
    /**
     * @var int
     */
    public $cacheDuration = 3600;

    /**
     * @ignore
     */
    public $widgetConfig = [
        'activeCssClass' => 'active',
        'firstItemCssClass' => 'first',
        'lastItemCssClass' => 'last',
        //'activateItems'=>true,
        'activateParents' => true,
        'options' => ['class' => 'level-1']
    ];

    private $_rawItems;     //выгрузка пунктов меню из БД
    private $_items;        //сфорированный на основе self::_rawItems массив с пунктами меню для рендеринга в виджете Menu

    public function init()
    {
        parent::init();

        if (empty($this->type)) {
            throw new InvalidConfigException(Yii::t('gromver.cmf', 'Menu type must be set.'));
        }

        $this->language or $this->language = Yii::$app->language;

        $this->_rawItems = Yii::$app->db->cache(function ($db) {
            return MenuItem::find()->type($this->type)->published()->language($this->language)->asArray()->orderBy('lft')->all($db);
        }, $this->cacheDuration, Table::dependency(MenuItem::tableName()));

        $i = 0;

        $this->_items = $this->prepareMenuItems($i, 2);
    }

    public function getItems()
    {
        return $this->_items;
    }

    private function prepareMenuItems(&$index, $level)
    {
        $items = array();
        $activeMenuIds = Yii::$app->menuManager->getActiveMenuIds();
        $urlManager = Yii::$app->urlManager;

        while ($item = @$this->_rawItems[$index]){
            /* @var $item MenuItem */
            if ($level == $item['level']) {
                $canAccess = empty($item['access_rule']) || Yii::$app->user->can($item['access_rule']);
                $linkParams = (array)Json::decode($item['link_params']);
                $items[] = [
                    'id' => $item['id'],
                    'label' => @$linkParams['title'] ? $linkParams['title'] : $item['title'],
                    'url' => $item['link_type'] == MenuItem::LINK_ROUTE ? (@$linkParams['secure'] ? $urlManager->createAbsoluteUrl($item['path'], 'https') : $urlManager->createUrl([$item['path']], $item['language'])) : $item['link'],
                    'visible' => $canAccess || $this->showInaccessible,
                    'submenuOptions' => array('class'=>'level-'.$item['level']),
                    'active' => in_array($item['id'], $activeMenuIds) ? true : null,
                    'options' => array(
                        'class' => @$linkParams['class'] ? $linkParams['class'] : null,
                        'target' => @$linkParams['target'] ? $linkParams['target'] : null,
                        'style' => @$linkParams['style'] ? $linkParams['style'] : null,
                        'rel' => @$linkParams['rel'] ? $linkParams['rel'] : null,
                        'onclick' => @$linkParams['onclick'] ? $linkParams['onclick'] : null,
                    )
                ];
                $index++;
            } elseif ($level<$item['level']) {
                $items[count($items)-1]['items'] = $this->prepareMenuItems($index, $item['level']);
            } else {
                return $items;
            }
        }

        return $items;
    }

    protected function launch()
    {
        $widgetClass = ArrayHelper::remove($this->widgetConfig, 'class', '\yii\widgets\Menu');
        $this->widgetConfig['id'] = $this->getId();
        $this->widgetConfig['items'] = $this->_items;

        echo $widgetClass::widget($this->widgetConfig);
    }

    public static function languages()
    {
        return ['' => Yii::t('gromver.cmf', 'Autodetect')] + Yii::$app->getLanguagesList();
    }
}