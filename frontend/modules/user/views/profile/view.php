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

$this->title = Html::encode($user->username);
?>
<div class="alert alert-success display-none" id="profile-image-success">Profile image updated</div>
<div class="alert alert-danger display-none" id="profile-image-fail"></div>

<div class="page-posts no-padding">
    <div class="row">
        <div class="page page-post col-sm-12 col-xs-12 post-82">


            <div class="blog-posts blog-posts-large">

                <div class="row">

                    <!-- profile -->
                    <article class="profile col-sm-12 col-xs-12">                                            
                        <div class="profile-title">
                            <img src="<?= $user->getPicture() ?>" id="profile-picture" class="author-image" />
                            <div class="author-name"><?= Html::encode($user->username) ?></div>

                            <?php if ($currentUser && $user->equals($currentUser)): ?>                              
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

                                <a href="#" class="btn btn-default">Edit profile</a>
                            <?php endif; ?>

                            <?php if ($currentUser && !$user->equals($currentUser)): ?>
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
                                    <hr/>
                                <?php endif; ?>
                            <?php endif; ?>

                        </div>

                        <div class="profile-description">
                            <p><?= HtmlPurifier::process($user->about) ?></p>
                        </div>
                        <div class="profile-bottom">
                            <div class="profile-post-count">
                                <span><?= $user->getPostCount() ?> posts</span>
                            </div>
                            <div class="profile-followers">
                                <a href="#" data-toggle="modal" data-target="#FollowersModal"> <?= $user->countFollowers() ?> followers</a>
                            </div>
                            <div class="profile-following">
                                <a href="#" data-toggle="modal" data-target="#SubcriptionsModal"><?= $user->countSubscriptions() ?> following</a>    
                            </div>
                        </div>
                    </article>

                    <div class="col-sm-12 col-xs-12">
                        <div class="row profile-posts">
                            <?php foreach ($posts as $post): ?>
                                <div class="col-md-4 profile-post">
                                    <a href="<?= Url::to(['/post/default/view', 'id' => $post->getId()])?>">
                                        <img src="<?=Yii::$app->storage->getFile($post->filename) ?>" class="author-image" />
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>


                </div>

            </div>
        </div>

    </div>
</div>


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


<?php
$this->registerJsFile('@web/js/likes.js', [
    'depends' => JqueryAsset::className(),
]);
