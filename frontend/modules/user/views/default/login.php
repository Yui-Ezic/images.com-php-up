<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\authclient\widgets\AuthChoice;

$this->title = 'Login';
?>

<?php
$form = ActiveForm::begin([
            'id' => 'login-form',
            'options' => [
                'class' => 'login100-form validate-form flex-sb flex-w'
            ],
            'fieldConfig' => [
                'options' => ['class' => 'wrap-input100 validate-input']
            ],
        ]);
?>

<span class="login100-form-title p-b-53">
    Login With
</span>

<?php
$auth = AuthChoice::begin([
            'baseAuthUrl' => ['/user/default/auth'],
            'popupMode' => false,
            'options' => [
                'style' => 'width: 100%;',
                'class' => 'flex-sb',
            ],
        ]);
?>

<?php foreach ($auth->getClients() as $externalService): ?>
    <?php if ($externalService->getId() == 'google'): ?>
        <a href="<?= $auth->createClientUrl($externalService); ?>" class="btn-google m-b-20">
            <span class="auth-icon google" style="margin: 0 10px 0;"></span>
            Google
        </a>
    <?php elseif ($externalService->getId() == 'facebook'): ?>
        <a href="<?= $auth->createClientUrl($externalService); ?>" class="btn-face m-b-20">
            <i class="fa fa-facebook-official"></i> Facebook
        </a>
    <?php endif; ?>
<?php endforeach; ?>

<?php AuthChoice::end() ?>

<div class="p-t-31 p-b-9">
    <span class="txt1">
        Email
    </span>
</div>
<?= $form->field($model, 'email')->textInput(['class' => 'input100'])->label(false) ?>

<div class="p-t-31 p-b-9">
    <span class="txt1">
        Password
    </span>
</div>
<?= $form->field($model, 'password')->passwordInput(['class' => 'input100'])->label(false) ?>

<div class="form-group container-login100-form-btn m-t-17">
    <?= Html::submitButton('Login', ['class' => 'login100-form-btn', 'name' => 'login-button']) ?>
</div>

<div class="w-full text-center p-t-55">
    <span class="txt2">If you forgot your password you can</span>
    <?= Html::a('reset it', ['/user/default/request-password-reset'], ['class' => 'txt2 bo1']) ?>
    <br/>
    <span class="txt2">Not a member?</span>
    <?= Html::a('Sign up now', ['/user/default/signup'], ['class' => 'txt2 bo1']) ?>
</div>

<?php ActiveForm::end(); ?>
