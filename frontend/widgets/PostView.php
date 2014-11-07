<?php
/**
 * @link https://github.com/gromver/yii2-cms.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/gromver/yii2-cmf/blob/master/LICENSE
 * @package yii2-cms
 * @version 1.0.0
 */

namespace gromver\cmf\frontend\widgets;

use gromver\cmf\backend\assets\CkeditorHighlightAsset;
use gromver\cmf\common\widgets\Widget;
use gromver\cmf\common\models\Post;
use yii\base\InvalidConfigException;
use Yii;

/**
 * Class PostView
 * @package yii2-cms
 * @author Gayazov Roman <gromver5@gmail.com>
 */
class PostView extends Widget {
    /**
     * Post model or PostId or PostId:PageAlias
     * @var Post|string
     * @type modal
     * @url /cmf/default/select-post
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
    public $layout = 'post/viewIssue';
    /**
     * @type yesno
     */
    public $showTranslations;
    /**
     * @type yesno
     */
    public $useHighlights = true;


    protected function normalizeSource()
    {
        if ($this->source && !$this->source instanceof Post) {
            @list($id, $postAlias, $categoryAlias) = explode(':', $this->source);
            $this->source = null;

            if ($postAlias && $categoryAlias) {
                $this->language or $this->language = Yii::$app->language;

                $this->source = Post::find()->innerJoinWith([
                    'category' => function($query) use($categoryAlias) {
                            /** @var $query \yii\db\ActiveQuery */
                            $query->andOnCondition(['{{%cms_category}}.language' => $this->language, '{{%cms_category}}.path' => $categoryAlias]);
                        }
                ])->andWhere(['{{%cms_post}}.alias' => $postAlias])->one();
            }

            if (empty($this->source)) {
                $this->source = Post::findOne($id);
            }
        }

        if (empty($this->source)) {
            throw new InvalidConfigException(Yii::t('menst.cms', 'Post not found.'));
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
            'post/viewArticle' => Yii::t('menst.cms', 'Article'),
            'post/viewIssue' => Yii::t('menst.cms', 'Issue'),
        ];
    }

    public static function languages()
    {
        return ['' => Yii::t('menst.cms', 'Autodetect')] + Yii::$app->getLanguagesList();
    }
} 