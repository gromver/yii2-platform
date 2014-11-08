<?php
use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;

/**
 * @var \yii\web\View $this
 * @var string $content
 */
AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
	<meta charset="<?= Yii::$app->charset ?>"/>
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
	<title><?= Html::encode($this->title) ?></title>
	<?php $this->head() ?>
</head>
<body>
	<?php $this->beginBody() ?>
	<div class="wrap">
		<?php
			NavBar::begin([
				'brandLabel' => Yii::$app->cmf->siteName,
				'brandUrl' => Yii::$app->homeUrl,
				'options' => [
					'class' => 'navbar-inverse navbar-fixed-top',
				],
			]);

            echo Html::beginForm(['/cmf/search/default/index'], 'get', ['class' => 'navbar-form navbar-left']);

            echo Html::textInput('q', null, ['class' => 'form-control', 'placeholder' => Yii::t('gromver.cmf', 'Search')]);

            //echo '&nbsp;' . Html::submitButton('<i class="glyphicon glyphicon-search"></i>', ['class' => 'btn btn-default']);

            echo Html::endForm();

			$menuItems = [
                ['label' => 'System', 'items' => [
                    ['label' => 'Control Panel', 'url' => ['/cmf/default/index']],
                    '<li class="divider"></li>',
                    ['label' => 'Configuration', 'url' => ['/cmf/default/params']],
                    '<li class="divider"></li>',
                    ['label' => 'Users', 'url' => ['/cmf/user/default/index']],
                    '<li class="divider"></li>',
                    ['label' => 'Flush Cache', 'url' => ['/cmf/default/flush-cache']],
                ]],
                ['label' => 'Menu', 'items' => array_merge([
                    ['label' => 'Menu Types', 'url' => ['/cmf/menu/type/index']],
                    ['label' => 'Menu Items', 'url' => ['/cmf/menu/item/index']],
                    '<li class="divider"></li>',
                ], array_map(function ($value) {
                    /** @var $value \gromver\cmf\common\models\MenuType */
                    return ['label' => $value->title, 'url' => ['/cmf/menu/item/index', 'MenuItemSearch' => ['menu_type_id' => $value->id]]];
                }, \gromver\cmf\common\models\MenuType::find()->all()))],
                ['label' => 'Content', 'items' => [
                    ['label' => 'Pages', 'url' => ['/cmf/page/default/index']],
                    '<li class="divider"></li>',
                    ['label' => 'Categories', 'url' => ['/cmf/news/category/index']],
                    ['label' => 'Posts', 'url' => ['/cmf/news/post/index']],
                    '<li class="divider"></li>',
                    ['label' => 'Tags', 'url' => ['/cmf/tag/default/index']],
                    '<li class="divider"></li>',
                    ['label' => 'Media Manager', 'url' => ['/cmf/media/default/index']],
                ]],
				['label' => 'Components', 'items' => [
                    ['label' => 'Version Manager', 'url' => ['/cmf/version/default/index']],
                    ['label' => "Widget's Settings", 'url' => ['/cmf/widget/default/index']],
                    ['label' => 'Search', 'url' => ['/cmf/search/default/index']],
                ]],
			];
			if (Yii::$app->user->isGuest) {
				$menuItems[] = ['label' => 'Login', 'url' => Yii::$app->user->loginUrl];
			} else {
				$menuItems[] = [
                    'label' => '<i class="glyphicon glyphicon-user"></i> ' . Yii::$app->user->identity->username,
					'items' => [
                        ['label' => '<i class="glyphicon glyphicon-envelope"></i> Contact', 'url' => ['/cmf/default/contact']],
                        '<li class="divider"></li>',
                        ['label' => '<i class="glyphicon glyphicon-cog"></i> Profile', 'url' => ['/cmf/user/default/update', 'id' => Yii::$app->user->id]],
                        ['label' => '<i class="glyphicon glyphicon-log-out"></i> Logout', 'url' => ['/cmf/auth/default/logout']]
                    ]
				];
			}
            $menuItems[] = Html::tag('div', Yii::t('gromver.cmf', 'Language'), ['class' => 'navbar-text']) . Html::beginTag('div', ['class' => 'btn-group navbar-right']) . implode('', array_map(function($language) {
                return Html::a($language, Yii::$app->urlManager->createUrl([Yii::$app->request->getPathInfo()] + Yii::$app->request->getQueryParams(), $language), ['class' => 'btn navbar-btn btn-xs' . ($language === Yii::$app->language ? ' btn-primary active' : ' btn-default')]);
            }, Yii::$app->languages)) . Html::endTag('div');

			echo Nav::widget([
				'options' => ['class' => 'navbar-nav navbar-right'],
				'items' => $menuItems,
                'encodeLabels' => false
			]);
			NavBar::end();
		?>

		<div class="container">
		<?= Breadcrumbs::widget([
			'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
		]) ?>
        <?php foreach (Yii::$app->session->getAllFlashes() as $type => $body)
            echo \kartik\widgets\Alert::widget([
                'type' => $type,
                'body' => $body
            ]) ?>
		<?= $content ?>
		</div>
	</div>

	<footer class="footer">
		<div class="container">
            <p class="pull-left">&copy; <?= Yii::$app->cmf->siteName . ' ' . date('Y') ?></p>
            <p class="pull-right"><?= Yii::powered() ?></p>
		</div>
	</footer>

	<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
