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
				'brandLabel' => Yii::$app->cms->siteName,
				'brandUrl' => Yii::$app->homeUrl,
				'options' => [
					'class' => 'navbar-inverse navbar-fixed-top',
				],
			]);

            echo Html::beginForm(['/cms/search/default/index'], 'get', ['class' => 'navbar-form navbar-left']);

            echo Html::textInput('q', null, ['class' => 'form-control', 'placeholder' => Yii::t('menst.cms', 'Search')]);

            echo '&nbsp;' . Html::submitButton('<i class="glyphicon glyphicon-search"></i>', ['class' => 'btn btn-default']);

            echo Html::endForm();

			$menuItems = [
                ['label' => 'System', 'items' => [
                    ['label' => 'Control Panel', 'url' => ['/cms/default/index']],
                    '<li class="divider"></li>',
                    ['label' => 'Configuration', 'url' => ['/cms/default/params']],
                    '<li class="divider"></li>',
                    ['label' => 'Users', 'url' => ['/cms/user/default/index']],
                    '<li class="divider"></li>',
                    ['label' => 'Flush Cache', 'url' => ['/cms/default/flush-cache']],
                ]],
                ['label' => 'Menu', 'items' => array_merge([
                    ['label' => 'Menu Types', 'url' => ['/cms/menu/type/index']],
                    ['label' => 'Menu Items', 'url' => ['/cms/menu/item/index']],
                    '<li class="divider"></li>',
                ], array_map(function ($value) {
                    /** @var $value \menst\cms\common\models\MenuType */
                    return ['label' => $value->title, 'url' => ['/cms/menu/item/index', 'MenuItemSearch' => ['menu_type_id' => $value->id]]];
                }, \menst\cms\common\models\MenuType::find()->all()))],
                ['label' => 'Content', 'items' => [
                    ['label' => 'Pages', 'url' => ['/cms/page/default/index']],
                    '<li class="divider"></li>',
                    ['label' => 'Categories', 'url' => ['/cms/news/category/index']],
                    ['label' => 'Posts', 'url' => ['/cms/news/post/index']],
                    '<li class="divider"></li>',
                    ['label' => 'Tags', 'url' => ['/cms/tag/default/index']],
                    '<li class="divider"></li>',
                    ['label' => 'Media Manager', 'url' => ['/cms/media/default/index']],
                ]],
				['label' => 'Components', 'items' => [
                    ['label' => 'Version Manager', 'url' => ['/cms/version/default/index']],
                    ['label' => "Widget's Settings", 'url' => ['/cms/widget/default/index']],
                    ['label' => 'Search', 'url' => ['/cms/search/default/index']],
                ]],
			];
			if (Yii::$app->user->isGuest) {
				$menuItems[] = ['label' => 'Login', 'url' => Yii::$app->user->loginUrl];
			} else {
				$menuItems[] = [
                    'label' => '<i class="glyphicon glyphicon-user"></i> ' . Yii::$app->user->identity->username,
					'items' => [
                        ['label' => '<i class="glyphicon glyphicon-cog"></i> Profile', 'url' => ['/cms/user/default/update', 'id' => Yii::$app->user->id]],
                        ['label' => '<i class="glyphicon glyphicon-log-out"></i> Logout', 'url' => ['/cms/auth/default/logout']]
                    ]
				];
			}
            $menuItems[] = [
                'label' => 'Language',
                'items' => array_map(function($language) {
                    return ['label' => $language, 'active' => $language === Yii::$app->language, 'url' => Yii::$app->urlManager->createUrl([Yii::$app->request->getPathInfo()] + Yii::$app->request->getQueryParams(), $language)];
                }, Yii::$app->languages)
            ];

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
            <p class="pull-left">&copy; <?= Yii::$app->cms->siteName . ' ' . date('Y') ?></p>
            <p class="pull-right"><?= Yii::powered() ?></p>
		</div>
	</footer>

	<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
