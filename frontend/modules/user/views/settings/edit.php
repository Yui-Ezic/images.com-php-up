<?php
/* @var $this yii\web\View */
/* @var $currentUser frontend\models\User */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

$this->title = "Edit user: " . $currentUser->username;
?>
<div class ="settings-window">
    <div class="row">
        <?= $this->render('_navigation'); ?>

        <div class="col-sm-9">

            <div class = "user-settings d-flex">
                <div class="user-settings-avatar center-cropped" style="background-image: url('<?= $currentUser->getPicture() ?>');">
                    <img src="<?= $currentUser->getPicture() ?>" alt="">
                </div>
                <div class="user-settings-name">
                    <a href="<?= Url::to(['/user/profile/view', 'nickname' => $currentUser->getNickname()]); ?>">
                        <?= Html::encode($currentUser->username); ?>
                    </a>
                </div>
            </div>

            <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($currentUser, 'username')->textInput() ?>

            <?= $form->field($currentUser, 'nickname')->textInput() ?>

            <?= $form->field($currentUser, 'about')->textInput() ?>


            <div class="form-group">
                <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>