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

            <h1>Not alaliable at this time</h1>
        </div>
    </div>
</div>