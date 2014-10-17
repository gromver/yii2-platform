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

			$menuItems = [
				['label' => Yii::t('menst.cms', 'Home'), 'url' => ['/site/index']],
			];
			if (Yii::$app->user->isGuest) {
				$menuItems[] = ['label' => Yii::t('menst.cms', 'Login'), 'url' => Yii::$app->user->loginUrl];
			} else {
                $menuItems[] = [
                    'label' => '<i class="glyphicon glyphicon-user"></i> ' . Yii::$app->user->identity->username,
                    'items' => [
                        ['label' => '<i class="glyphicon glyphicon-cog"></i> ' . Yii::t('menst.cms', 'Profile'), 'url' => ['/cms/user/default/update', 'id' => Yii::$app->user->id]],
                        ['label' => '<i class="glyphicon glyphicon-log-out"></i> ' . Yii::t('menst.cms', 'Logout'), 'url' => ['/cms/auth/default/logout']]
                    ]
                ];
			}
            $menuItems[] = [
                'label' => 'Language',
                'items' => array_map(function($language){
                    return ['label' => $language, 'url' => Yii::$app->urlManager->createUrl(Yii::$app->request->getPathInfo(), $language)];
                }, Yii::$app->languages)
            ];

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
		<p class="pull-left">&copy; <?= Yii::$app->cms->siteName . ' ' . date('Y') ?></p>
		<p class="pull-right"><?= Yii::powered() ?></p>
		</div>
	</footer>

	<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
