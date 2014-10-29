<?php
/**
 * @link https://github.com/menst/yii2-cms.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/menst/yii2-cms/blob/master/LICENSE
 * @package yii2-cms
 * @version 1.0.0
 */

namespace menst\cms\frontend\widgets;

use menst\cms\frontend\modules\main\Module;
use menst\widgets\ModalIFrame;
use Yii;
use yii\bootstrap\Modal;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\bootstrap\Widget;
use yii\helpers\Html;

/**
 * Class CmsPanel
 * @package yii2-cms
 * @author Gayazov Roman <m.e.n.s.t@yandex.ru>
 */
class CmsPanel extends Widget {
    public function run()
    {
        NavBar::begin([
            'brandLabel' => Yii::$app->cms->siteName,
            'brandUrl' => Yii::$app->homeUrl,
            'options' => [
                'class' => 'navbar-inverse navbar-fixed-top',
            ],
        ]);

        echo Html::beginTag('div', ['class'=>'nav']);

        echo Html::beginForm(['/cms/search/default/index'], 'get', ['class' => 'navbar-form navbar-left',  'role' => "search"]);

        echo Html::beginTag('div', ['class' => 'form-group']);

        echo Html::textInput('q', null, ['class' => 'form-control', 'placeholder' => Yii::t('menst.cms', 'Search')]);

        echo Html::endTag('div') . "\n";

        echo Html::submitButton('<i class="glyphicon glyphicon-search"></i>', ['class' => 'btn btn-default']);

        echo Html::endForm();

        if (Yii::$app->user->can('edit')) {
            echo Html::tag('p', Yii::t('menst.cms', 'Editing mode'), ['class' => 'navbar-text']);

            echo Html::beginTag('div', ['class'=>'btn-group']);

            if (Yii::$app->cms->mode === Module::MODE_EDIT) {
                echo Html::button(Yii::t('menst.cms', 'On'), ['class'=>'btn btn-success navbar-btn btn-xs active']);
                echo Html::a(Yii::t('menst.cms', 'Off'), ['/cms/default/mode', 'mode' => Module::MODE_VIEW, 'backUrl' => Yii::$app->request->getUrl()], ['class'=>'btn btn-default navbar-btn btn-xs']);
            } else {
                echo Html::a(Yii::t('menst.cms', 'On'), ['/cms/default/mode', 'mode' => Module::MODE_EDIT, 'backUrl' => Yii::$app->request->getUrl()], ['class'=>'btn btn-default navbar-btn btn-xs']);
                echo Html::button(Yii::t('menst.cms', 'Off'), ['class'=>'btn btn-success navbar-btn btn-xs active']);
            }

            echo Html::endTag('div');
        }

        if (Yii::$app->user->isGuest) {
            echo Html::beginTag('div',  ['class' => 'navbar-right']) . Html::tag('div', Yii::t('menst.cms', 'Language'), ['class' => 'navbar-text']) . Html::beginTag('div', ['class' => 'btn-group']) . implode('', array_map(function($language) {
                    return Html::a($language, Yii::$app->urlManager->createUrl(Yii::$app->getHomeUrl(), $language), ['class' => 'btn navbar-btn btn-xs' . ($language === Yii::$app->language ? ' btn-primary active' : ' btn-default')]);
                }, Yii::$app->languages)) . Html::endTag('div') . Html::endTag('div');

            $loginUrl = Yii::$app->user->loginUrl;
            $loginUrl['modal'] = 1;

            echo ModalIFrame::widget([
                'buttonOptions' => [
                    'tag' => 'div',
                    'class' => 'navbar-text navbar-right'
                ],
                'modalOptions' => [
                    'size' => Modal::SIZE_DEFAULT,
                    'closeButton' => false
                ],
                'iframeOptions' => [
                    'height' => '320px'
                ],
                'buttonContent' => Html::a('<i class="glyphicon glyphicon-log-in"></i> ' . Yii::t('menst.cms', 'Login'), $loginUrl, ['class' => 'navbar-link'])
            ]);
        } else {
            $items = [];
            if(Yii::$app->user->can('administrate')) {
                $items[] = ['label' => '<i class="glyphicon glyphicon-cog"></i> ' . Yii::t('menst.cms', 'Admin Panel'), 'url' => Yii::$app->urlManagerBackend->createUrl('/')];
                /*$items[] = ModalIFrame::widget([
                    'buttonOptions' => [
                        'tag' => 'li'
                    ],
                    'modalOptions' => [
                        'size' => Modal::SIZE_LARGE,
                        'closeButton' => false
                    ],
                    'buttonContent' => Html::a('<i class="glyphicon glyphicon-pencil"></i> ' . Yii::t('menst.cms', 'Configuration'), ['/cms/default/params', 'modal' => 1])
                ]);*/
                $items[] = ['label' => '<i class="glyphicon glyphicon-trash"></i> ' . Yii::t('menst.cms', 'Flush Cache'), 'url' => ['/cms/default/flush-cache']];
                $items[] = '<li class="divider"></li>';
            }
            $items[] = ['label' => '<i class="glyphicon glyphicon-log-out"></i> ' . Yii::t('menst.cms', 'Logout'), 'url' => ['/cms/auth/default/logout']];

            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => [
                    [
                        'label' => '<i class="glyphicon glyphicon-user"></i> ' . Yii::$app->user->identity->username,
                        'items' => $items,
                    ],
                    Html::tag('div', Yii::t('menst.cms', 'Language'), ['class' => 'nav navbar-text']) . Html::beginTag('div', ['class' => 'btn-group']) . implode('', array_map(function($language) {
                        return Html::a($language, Yii::$app->urlManager->createUrl(Yii::$app->getHomeUrl(), $language), ['class' => 'btn navbar-btn btn-xs' . ($language === Yii::$app->language ? ' btn-primary active' : ' btn-default')]);
                    }, Yii::$app->languages)) . Html::endTag('div')
                ],
                'encodeLabels' => false
            ]);
        }

        echo Html::endTag('div');

        NavBar::end();
    }
}