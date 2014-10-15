<?php
/**
 * @var yii\web\View $this
 * @var yii\bootstrap\ActiveForm $form
 * @var menst\cms\common\models\User $model
 */

/** @var \menst\cms\common\models\MenuItem $menu */
$menu = Yii::$app->menuManager->getActiveMenu();
if ($menu) {
    $this->title = $menu->isProperContext() ? $menu->title : Yii::t('menst.cms', 'Reset Password');
    $this->params['breadcrumbs'] = $menu->getBreadcrumbs($menu->isApplicableContext());
} else {
    $this->title = Yii::t('menst.cms', 'Reset Password');
}
//$this->params['breadcrumbs'][] = $this->title;?>

<div class="form-auth-heading row">
    <h1><?= \yii\helpers\Html::encode($this->title) ?></h1>
</div>

<?php echo \menst\cms\frontend\widgets\AuthResetPassword::widget([
    'id' => 'auth-pass',
    'model' => $model
]);