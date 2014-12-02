<?php
/**
 * @link https://github.com/gromver/yii2-cmf.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/gromver/yii2-grom/blob/master/LICENSE
 * @package yii2-cmf
 * @version 1.0.0
 */

namespace gromver\platform\frontend\widgets;

use gromver\platform\backend\assets\CkeditorHighlightAsset;
use gromver\platform\common\widgets\Widget;
use gromver\platform\common\models\Post;
use yii\base\InvalidConfigException;
use Yii;

/**
 * Class PostView
 * @package yii2-cmf
 * @author Gayazov Roman <gromver5@gmail.com>
 */
class PostView extends Widget {
    /**
     * Post model or PostId or PostId:PageAlias
     * @var Post|string
     * @type modal
     * @url /grom/default/select-post
     */
    public $post;
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

    protected function launch()
    {
        if ($this->post && !$this->post instanceof Post) {
            $this->post = Post::findOne(intval($this->post));
        }

        if (empty($this->post)) {
            throw new InvalidConfigException(Yii::t('gromver.platform', 'Post not found.'));
        }

        if ($this->useHighlights) {
            CkeditorHighlightAsset::register($this->getView());
        }

        echo $this->render($this->layout, [
            'model' => $this->post
        ]);
    }

    public function customControls()
    {
        return [
            [
                'url' => Yii::$app->urlManagerBackend->createUrl(['grom/news/post/update', 'id' => $this->post->id, 'backUrl' => $this->getBackUrl()]),
                'label' => '<i class="glyphicon glyphicon-pencil"></i>',
                'options' => ['title' => Yii::t('gromver.platform', 'Update Post')]
            ],
        ];
    }

    public static function layouts()
    {
        return [
            'post/viewArticle' => Yii::t('gromver.platform', 'Article'),
            'post/viewIssue' => Yii::t('gromver.platform', 'Issue'),
        ];
    }
} 