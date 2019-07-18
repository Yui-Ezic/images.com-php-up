<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\PasswordResetRequestForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Request password reset';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php
$form = ActiveForm::begin([
            'id' => 'request-password-reset-form',
            'options' => [
                'class' => 'login100-form validate-form flex-sb flex-w'
            ],
            'fieldConfig' => [
                'options' => ['class' => 'wrap-input100 validate-input']
            ],
        ]);
?>

<span class="login100-form-title p-b-53">
    Please fill out your email. A link to reset password will be sent there.
</span>

<div class="p-t-31 p-b-9">
    <span class="txt1">
        Email
    </span>
</div>
<?= $form->field($model, 'email')->textInput(['class' => 'input100'])->label(false) ?>

<div class="form-group container-login100-form-btn m-t-17">
    <?= Html::submitButton('Send', ['class' => 'login100-form-btn']) ?>
</div>

<div class="w-full text-center p-t-55">
    <?= Html::a('Back to login', ['/user/default/login'], ['class' => 'txt2 bo1']) ?>
</div>

<?php ActiveForm::end(); ?>