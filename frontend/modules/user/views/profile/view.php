<?php
/* @var $user frontend\models\User */
/* @var $currentUser frontend\models\User */
/* @var $modelPicture frontend\modules\user\models\forms\PictureForm */
/* @var $posts[] frontend\models\Post */

use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\helpers\Url;
use dosamigos\fileupload\FileUpload;
use yii\web\JqueryAsset;
?>
<h1> <?= Html::encode($user->username) ?> </h1>
<p> <?= HtmlPurifier::process($user->about) ?> </p>
<hr/>

<img src="<?= $user->getPicture() ?>" alt="User picture" id="profile-picture" style="max-height: 320px;">

<?php if ($currentUser): ?>
    <?php if ($user->equals($currentUser)): ?>
        <div class="alert alert-success display-none" id="profile-image-success">Profile image updated</div>
        <div class="alert alert-danger display-none" id="profile-image-fail"></div>

        <?=
        FileUpload::widget([
            'model' => $modelPicture,
            'attribute' => 'picture',
            'url' => ['/user/profile/upload-picture'], // your url, this is just for demo purposes,
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
                                            $("#profile-image-success").show();
                                            $("#profile-image-fail").hide();
                                            $("#profile-picture").attr("src", data.result.pictureUri);
                                        } else {
                                            $("#profile-image-success").hide();
                                            $("#profile-image-fail").html(data.result.errors).show();
                                        }
                                    }',
            ],
        ]);
        ?>
        <a href="<?= Url::to(['/user/profile/delete-picture']); ?>" class="btn btn-danger">Delete picture</a>
    <?php else: ?>

        <?php if (!$currentUser->isFollowing($user)): ?>
            <a href="<?= Url::to(['/user/profile/subscribe', 'id' => $user->getId()]) ?>" class="btn btn-info">Subscribe</a>
        <?php else: ?>
            <a href="<?= Url::to(['/user/profile/unsubscribe', 'id' => $user->getId()]) ?>" class="btn btn-info">Unsubscribe</a>
        <?php endif; ?>

        <hr/>

        <?php if ($mutualSubscriptions = $currentUser->getMutualSubscriptionsTo($user)): ?>
            <h5>Friends, who also following <?= Html::encode($user->username) ?>: </h5>
            <div class="row">
            <?php foreach ($mutualSubscriptions as $item): ?>
                    <div class="col-md-12">
                        <a href="<?= Url::to(['/user/profile/view', 'nickname' => ($item['nickname'] ? $item['nickname'] : $item['id'])]) ?>">
                <?= Html::encode($item['username']) ?>
                        </a>
                    </div>
            <?php endforeach; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
<?php endif; ?>
<!-- Button trigger modal -->
<button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#SubcriptionsModal">
    Subcriptions: <?= $user->countSubscriptions() ?>
</button>

<button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#FollowersModal">
    Followers: <?= $user->countFollowers() ?>
</button>

<!-- Modal -->
<div class="modal fade" id="SubcriptionsModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Modal title</h4>
            </div>
            <div class="modal-body">
                <div class="row">
<?php foreach ($user->getSubscriptions() as $subscription): ?>
                        <div class="col-md-12">
                            <a href="<?= Url::to(['/user/profile/view', 'nickname' => ($subscription['nickname'] ? $subscription['nickname'] : $subscription['id'])]) ?>">
    <?= Html::encode($subscription['username']) ?>
                            </a>
                        </div>
<?php endforeach; ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="FollowersModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Modal title</h4>
            </div>
            <div class="modal-body">
                <div class="row">
<?php foreach ($user->getFollowers() as $follower): ?>
                        <div class="col-md-12">
                            <a href="<?= Url::to(['/user/profile/view', 'nickname' => ($follower['nickname'] ? $follower['nickname'] : $follower['id'])]) ?>">
    <?= Html::encode($follower['username']) ?>
                            </a>
                        </div>
<?php endforeach; ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<h1>Posts</h1>
<hr/>

<div class="row">
    <?php foreach ($posts as $post): ?>
        <div class="col-md-12">
            <div class="col-md-12">
                <img src="<?= $user->getPicture() ?>" width="60" height="60" />
                <a href="<?php echo Url::to(['/user/profile/view', 'nickname' => $user->getNickname()]); ?>">
                    <?php echo Html::encode($user->username); ?>
                </a>
                
                <img src="<?php echo Yii::$app->storage->getFile($post->filename); ?>" />
                <div class="col-md-12">
                    <?php echo HtmlPurifier::process($post->description); ?>
                </div>
                
                <div class="col-md-12">
                    <?php echo Yii::$app->formatter->asDatetime($post->created_at); ?>
                </div>
                
                <div class="col-md-12">
                    Likes: <span class="likes-count"><?php echo $post->countLikes(); ?></span>

                    <a href="#" class="btn btn-primary button-unlike <?php echo ($currentUser->likesPost($post->id)) ? "" : "display-none"; ?>" data-id="<?php echo $post->id; ?>">
                        Unlike&nbsp;&nbsp;<span class="glyphicon glyphicon-thumbs-down"></span>
                    </a>
                    <a href="#" class="btn btn-primary button-like <?php echo ($currentUser->likesPost($post->id)) ? "display-none" : ""; ?>" data-id="<?php echo $post->id; ?>">
                        Like&nbsp;&nbsp;<span class="glyphicon glyphicon-thumbs-up"></span>
                    </a>
                </div>
                
            </div>
        </div>
        <div class="col-md-12"><hr/></div> 
    <?php endforeach; ?>
</div>

<?php $this->registerJsFile('@web/js/likes.js', [
    'depends' => JqueryAsset::className(),
]);