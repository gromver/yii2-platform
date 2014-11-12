<?php
/**
 * @link https://github.com/gromver/yii2-cmf.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/gromver/yii2-cmf/blob/master/LICENSE
 * @package yii2-cmf
 * @version 1.0.0
 */

namespace gromver\cmf\frontend\widgets;

use gromver\cmf\backend\assets\CkeditorHighlightAsset;
use gromver\cmf\common\widgets\Widget;
use gromver\cmf\common\models\Page;
use yii\base\InvalidConfigException;
use Yii;

/**
 * Class PageView
 * @package yii2-cmf
 * @author Gayazov Roman <gromver5@gmail.com>
 */
class PageView extends Widget {
    /**
     * Page model or PageId or PageId:PageAlias
     * @var Page|string
     * @type modal
     * @url /cmf/default/select-page
     */
    public $page;
    /**
     * @type list
     * @items layouts
     */
    public $layout = 'page/article';
    /**
     * @type yesno
     */
    public $showTranslations;
    /**
     * @type yesno
     */
    public $useHighlights = true;

    protected function launch()
    {
        if ($this->page && !$this->page instanceof Page) {
            $this->page = Page::findOne(intval($this->page));
        }

        if (empty($this->page)) {
            throw new InvalidConfigException(Yii::t('gromver.cmf', 'Page not found.'));
        }

        if ($this->useHighlights) {
            CkeditorHighlightAsset::register($this->view);
        }

        echo $this->render($this->layout, [
            'model' => $this->page
        ]);
    }

    public function customControls()
    {
        return [
            [
                'url' => Yii::$app->urlManagerBackend->createUrl(['cmf/page/default/update', 'id' => $this->page->id]),
                'label' => '<i class="glyphicon glyphicon-pencil"></i>',
                'options' => ['title' => Yii::t('gromver.cmf', 'Update Page')]
            ],
            [
                'url' => Yii::$app->urlManagerBackend->createUrl(['cmf/page/default/create']),
                'label' => '<i class="glyphicon glyphicon-plus"></i>',
                'options' => ['title' => Yii::t('gromver.cmf', 'Create Page')]
            ],
            [
                'url' => Yii::$app->urlManagerBackend->createUrl(['cmf/page/default/index']),
                'label' => '<i class="glyphicon glyphicon-th-list"></i>',
                'options' => ['title' => Yii::t('gromver.cmf', 'Pages list')]
            ],
        ];
    }

    public static function layouts()
    {
        return [
            'page/article' => Yii::t('gromver.cmf', 'Article'),
            'page/content' => Yii::t('gromver.cmf', 'Content'),
        ];
    }
} 