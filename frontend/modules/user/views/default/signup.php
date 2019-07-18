<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Signup';
?>
<?php
$form = ActiveForm::begin([
            'id' => 'form-signup',
            'options' => [
                'class' => 'login100-form validate-form flex-sb flex-w'
            ],
            'fieldConfig' => [
                'options' => ['class' => 'wrap-input100 validate-input']
            ],
        ]);
?>

<span class="login100-form-title p-b-53">
    Signup
</span>

<div class="p-t-31 p-b-9">
    <span class="txt1">
        Username
    </span>
</div>
<?= $form->field($model, 'username')->textInput(['autofocus' => true, 'class' => 'input100'])->label(false) ?>

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
    <?= Html::submitButton('Signup', ['class' => 'login100-form-btn', 'name' => 'signup-button']) ?>
</div>

<div class="w-full text-center p-t-55">
    <span class="txt2">Already registered?</span>
    <?= Html::a('Login now', ['/user/default/login'], ['class' => 'txt2 bo1']) ?>
</div>

<?php ActiveForm::end(); ?>
