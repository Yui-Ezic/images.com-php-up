<?php
/* @var $this yii\web\View */
/* @var $currentUser frontend\models\User */
/* @var $modelPicture frontend\modules\user\models\forms\PictureForm */

use yii\helpers\Html;
use yii\helpers\Url;
use dosamigos\fileupload\FileUpload;

$this->title = "Change avatar: " . $currentUser->username;
?>

<div id="w2-danger-0" class="alert-danger alert fade in display-none">
<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
Error occured
</div>

<div id="w2-success-0" class="alert-success alert fade in display-none">
<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
Profile image updated
</div>

<div class ="settings-window">
    <div class="row">
        <?= $this->render('_navigation'); ?>

        <div class="col-sm-9">
            <div class="user-avatar center-cropped" style="background-image: url('<?= $currentUser->getPicture() ?>');">
                <img src="<?= $currentUser->getPicture() ?>" alt="">
            </div>

            <div class="user-avatar-button d-flex">
                <?=
                FileUpload::widget([
                    'model' => $modelPicture,
                    'attribute' => 'picture',
                    'url' => ['/user/settings/upload-picture'], // your url, this is just for demo purposes,
                    'options' => ['accept' => 'image/*'],
                    'clientOptions' => [
                        'maxFileSize' => 2000000
                    ],
                    // Also, you can specify jQuery-File-Upload events
                    // see: https://github.com/blueimp/jQuery-File-Upload/wiki/Options#processing-callback-options
                    'clientEvents' => [
                        'fileuploaddone' => 'function(e, data) {
                                            if(data.result.success)
                                            {
                                                $("#w2-success-0").show();
                                                $("#w2-danger-0").hide();
                                                $(".user-avatar").css("background-image", "url(" + data.result.pictureUri + ")");
                                                $(".user-avatar img").attr("src", data.result.pictureUri);
                                            } else {
                                                $("#w2-success-0").hide();
                                                $("#w2-danger-0").html(data.result.errors).show();
                                            }
                                        }',
                    ],
                ]);
                ?>
                <a href="<?= Url::to(['/user/settings/delete-picture']); ?>" class="btn btn-danger">Delete picture</a>
            </div>

        </div>
    </div>
</div>