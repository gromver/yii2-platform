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
use gromver\cmf\common\models\Post;
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
     * @url /cmf/default/select-post
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
            throw new InvalidConfigException(Yii::t('gromver.cmf', 'Post not found.'));
        }

        if ($this->useHighlights) {
            CkeditorHighlightAsset::register($this->view);
        }

        echo $this->render($this->layout, [
            'model' => $this->post
        ]);
    }

    public function customControls()
    {
        return [
            [
                'url' => Yii::$app->urlManagerBackend->createUrl(['cmf/news/post/update', 'id' => $this->post->id, 'backUrl' => $this->getBackUrl()]),
                'label' => '<i class="glyphicon glyphicon-pencil"></i>',
                'options' => ['title' => Yii::t('gromver.cmf', 'Update Post')]
            ],
        ];
    }

    public static function layouts()
    {
        return [
            'post/viewArticle' => Yii::t('gromver.cmf', 'Article'),
            'post/viewIssue' => Yii::t('gromver.cmf', 'Issue'),
        ];
    }
} 