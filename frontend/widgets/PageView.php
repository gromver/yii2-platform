<?php
/**
 * @link https://github.com/menst/yii2-cms.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/menst/yii2-cms/blob/master/LICENSE
 * @package yii2-cms
 * @version 1.0.0
 */

namespace menst\cms\frontend\widgets;

use menst\cms\backend\assets\CkeditorHighlightAsset;
use menst\cms\common\widgets\Widget;
use menst\cms\common\models\Page;
use yii\base\InvalidConfigException;
use Yii;

/**
 * Class PageView
 * @package yii2-cms
 * @author Gayazov Roman <m.e.n.s.t@yandex.ru>
 */
class PageView extends Widget {
    /**
     * Page or Page model or PageId or PageId:PageAlias
     * @var Page|string
     * @type modal
     * @url /cms/default/select-page
     */
    public $source;
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
     * @type list
     * @items languages
     */
    public $language;
    /**
     * @type yesno
     */
    public $useHighlights = true;


    protected function normalizeSource()
    {
        if ($this->source && !$this->source instanceof Page) {
            @list($id, $alias) = explode(':', $this->source);
            $this->source = null;

            if ($alias) {
                $this->language or $this->language = Yii::$app->language;

                $this->source = Page::find()->andWhere(['alias' => $alias, 'language' => $this->language])->one();
            }

            if (empty($this->source)) {
                $this->source = Page::findOne($id);
            }
        }

        if (empty($this->source)) {
            throw new InvalidConfigException(Yii::t('menst.cms', 'Page not found.'));
        }
    }

    protected function launch()
    {
        $this->normalizeSource();

        if ($this->useHighlights) {
            CkeditorHighlightAsset::register($this->view);
        }

        echo $this->render($this->layout, [
            'model' => $this->source
        ]);
    }

    public static function layouts()
    {
        return [
            'page/article' => Yii::t('menst.cms', 'Article'),
            'page/content' => Yii::t('menst.cms', 'Content'),
        ];
    }

    public static function languages()
    {
        return ['' => Yii::t('menst.cms', 'Autodetect')] + Yii::$app->getLanguagesList();
    }
} 