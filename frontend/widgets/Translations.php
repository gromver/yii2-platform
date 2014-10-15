<?php
/**
 * @link https://github.com/menst/yii2-cms.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/menst/yii2-cms/blob/master/LICENSE
 * @package yii2-cms
 * @version 1.0.0
 */

namespace menst\cms\frontend\widgets;

use Yii;
use yii\bootstrap\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * Class Translations
 * @package yii2-cms
 * @author Gayazov Roman <m.e.n.s.t@yandex.ru>
 */
class Translations extends Widget {
    public $model;
    public $options;
    public $linkTemplate = '<a class="btn btn-default" href="{url}">{label}</a>';
    public $labelTemplate = '<button type="button" class="btn btn-primary">{label}</button>';

    public function run()
    {
        $tag = ArrayHelper::remove($this->options, 'tag', 'div');
        Html::addCssClass($this->options, 'btn-group btn-group-xs');
        echo Html::tag($tag, $this->renderItems(), $this->options);
    }

    protected function renderItems()
    {
        $items = '';

        foreach($this->model->translations as $language => $item) {
            /** @var $item \menst\cms\common\interfaces\ViewableInterface */
            if ($this->model->language === $language) {
                $items = strtr($this->labelTemplate, [
                        '{label}' => $language
                    ]) . $items;
            } else {
                $items .=  strtr($this->linkTemplate, [
                    '{label}' => $language,
                    '{url}' => Yii::$app->urlManager->createUrl($item->getViewLink(), $language)
                ]);
            }
        }

        return $items;
    }
} 