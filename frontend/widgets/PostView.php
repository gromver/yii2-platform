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
use menst\cms\common\models\Post;
use yii\base\InvalidConfigException;

/**
 * Class PostView
 * @package yii2-cms
 * @author Gayazov Roman <m.e.n.s.t@yandex.ru>
 */
class PostView extends Widget {
    /**
     * Post model or PostId or PostId:PageAlias
     * @var Post|string
     * @type modal
     * @url /cms/default/select-post
     */
    public $source;
    /**
     * @type list
     * @items languages
     */
    public $language;
    /**
     * @type list
     * @items layouts
     * @editable
     */
    public $layout = 'post/viewArticle';
    /**
     * @type yesno
     */
    public $showTranslations;


    protected function normalizeSource()
    {
        if ($this->source && !$this->source instanceof Post) {
            @list($id, $postAlias, $categoryAlias) = explode(':', $this->source);
            $this->source = null;

            if ($this->language && $postAlias && $categoryAlias) {
                $language = $this->language == 'auto' ? \Yii::$app->language : $this->language;

                $this->source = Post::find()->innerJoinWith([
                    'category' => function($query) use($categoryAlias, $language) {
                            /** @var $query \yii\db\ActiveQuery */
                            $query->andOnCondition(['{{%cms_category}}.language' => $language, '{{%cms_category}}.path' => $categoryAlias]);
                        }
                ])->andWhere(['{{%cms_post}}.alias' => $postAlias])->one();
            }

            if (empty($this->source)) {
                $this->source = Post::findOne($id);
            }
        }

        if (empty($this->source)) {
            throw new InvalidConfigException('Статья не найдена.');
        }
    }

    protected function launch()
    {
        echo $this->render($this->layout, [
            'model' => $this->source
        ]);
    }


    public static function layouts()
    {
        return [
            'post/viewArticle' => 'Статья',
            'post/viewNews' => 'Новость',
        ];
    }

    public static function languages()
    {
        return ['' => 'Не задано', 'auto' => \Yii::t('menst.cms', 'Автоопределение')] + \Yii::$app->getLanguagesList();
    }
} 