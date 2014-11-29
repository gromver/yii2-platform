<?php
use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;

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
				'brandLabel' => Yii::$app->grom->siteName,
				'brandUrl' => Yii::$app->homeUrl,
				'options' => [
					'class' => 'navbar-inverse navbar-fixed-top',
				],
			]);

			$menuItems = [
				['label' => Yii::t('gromver.platform', 'Home'), 'url' => ['/site/index']],
			];
			if (Yii::$app->user->isGuest) {
				$menuItems[] = ['label' => Yii::t('gromver.platform', 'Login'), 'url' => Yii::$app->user->loginUrl];
			} else {
                $menuItems[] = [
                    'label' => '<i class="glyphicon glyphicon-user"></i> ' . Yii::$app->user->identity->username,
                    'items' => [
                        ['label' => '<i class="glyphicon glyphicon-cog"></i> ' . Yii::t('gromver.platform', 'Profile'), 'url' => ['/grom/user/default/update', 'id' => Yii::$app->user->id]],
                        ['label' => '<i class="glyphicon glyphicon-log-out"></i> ' . Yii::t('gromver.platform', 'Logout'), 'url' => ['/grom/auth/default/logout']]
                    ]
                ];
			}
            $menuItems[] = Html::tag('div', Yii::t('gromver.platform', 'Language'), ['class' => 'navbar-text']) . Html::beginTag('div', ['class' => 'btn-group navbar-right']) . implode('', array_map(function($language) {
                    return Html::a($language, Yii::$app->urlManager->createUrl([Yii::$app->request->getPathInfo()] + Yii::$app->request->getQueryParams(), $language), ['class' => 'btn navbar-btn btn-xs' . ($language === Yii::$app->language ? ' btn-primary active' : ' btn-default')]);
                }, Yii::$app->languages)) . Html::endTag('div');

			echo Nav::widget([
				'options' => ['class' => 'navbar-nav navbar-right'],
				'items' => $menuItems,
			]);
			NavBar::end();
		?>

		<div class="container">
        <?php foreach(Yii::$app->session->getAllFlashes() as $type=>$body) {
            echo \kartik\widgets\Alert::widget([
                'type' => $type,
                'body' => $body
            ]);
        }
		echo $content ?>
		</div>
	</div>

	<footer class="footer">
		<div class="container">
		<p class="pull-left">&copy; <?= Yii::$app->grom->siteName . ' ' . date('Y') ?></p>
		<p class="pull-right"><?= Yii::powered() ?></p>
		</div>
	</footer>

	<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
