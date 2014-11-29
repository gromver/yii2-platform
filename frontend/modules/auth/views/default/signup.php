<?php
/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var gromver\platform\common\models\User $model
 */

/** @var \gromver\platform\common\models\MenuItem $menu */
$menu = Yii::$app->menuManager->getActiveMenu();
if ($menu) {
    $this->title = $menu->isProperContext() ? $menu->title : Yii::t('gromver.platform', 'Signup');
    $this->params['breadcrumbs'] = $menu->getBreadcrumbs($menu->isApplicableContext());
} else {
    $this->title = Yii::t('gromver.platform', 'Signup');
}
//$this->params['breadcrumbs'][] = $this->title;?>

<div class="form-auth-heading row">
    <h1><?= \yii\helpers\Html::encode($this->title) ?></h1>
</div>

<?php echo \gromver\platform\frontend\widgets\AuthSignup::widget([
    'id' => 'auth-signup',
    'model' => $model
]);