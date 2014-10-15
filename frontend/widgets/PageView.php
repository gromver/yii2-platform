<?php
/**
 * @link https://github.com/menst/yii2-cms.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/menst/yii2-cms/blob/master/LICENSE
 * @package yii2-cms
 * @version 1.0.0
 */

namespace menst\cms\frontend\widgets;

use menst\cms\common\widgets\Widget;
use menst\cms\common\models\Page;
use yii\base\InvalidConfigException;

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


    protected function normalizeSource()
    {
        if ($this->source && !$this->source instanceof Page) {
            @list($id, $alias) = explode(':', $this->source);
            $this->source = null;

            if ($this->language && $alias) {
                $language = $this->language == 'auto' ? \Yii::$app->language : $this->language;

                $this->source = Page::find()->andWhere(['alias' => $alias, 'language' => $language])->one();
            }

            if (empty($this->source)) {
                $this->source = Page::findOne($id);
            }
        }

        if (empty($this->source)) {
            throw new InvalidConfigException('Страница не найдена.');
        }
    }

    protected function launch()
    {
        $this->normalizeSource();

        echo $this->render($this->layout, [
            'model' => $this->source
        ]);
    }

    public static function layouts()
    {
        return [
            'page/article' => 'Статья',
            'page/content' => 'Текст',
        ];
    }

    public static function languages()
    {
        return ['' => 'Не задано', 'auto' => \Yii::t('menst.cms', 'Автоопределение')] + \Yii::$app->getLanguagesList();
    }
} 