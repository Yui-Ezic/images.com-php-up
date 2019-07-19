<?php
/* @var $user frontend\models\User */
/* @var $currentUser frontend\models\User */
/* @var $modelPicture frontend\modules\user\models\forms\PictureForm */
/* @var $posts[] frontend\models\Post */

use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\helpers\Url;
use yii\web\JqueryAsset;

$this->title = Html::encode($user->username);
?>
<section class='user-info'>
    <div class="container">
        <div class="row justify-content-center align-items-center">
            <div class="col-lg-3 col-md-4">
                <div class="user-avatar center-cropped" style="background-image: url('<?= $user->getPicture() ?>');">
                    <img src="<?= $user->getPicture() ?>" alt="">
                </div>
            </div>
            <div class="col-lg-9 col-md-8">
                <div class="user-right-top d-flex">
                    <div class="user-name">
                        <a href="<?= Url::to(['/user/profile/view', 'nickname' => $user->getNickname()])?>">
                            <?= Html::encode($user->username) ?>
                        </a>
                    </div>
                    <div class="user-settings">
                        <?php if ($currentUser): ?>
                            <?php if ($user->equals($currentUser)): ?>
                            <a href="#" class="btn btn-default"> Edit profile</a>
                            <?php else: ?>
                                <?php if (!$currentUser->isFollowing($user)): ?>
                                <a href="<?= Url::to(['/user/profile/subscribe', 'id' => $user->getId()]) ?>" class="btn btn-info">Subscribe</a>
                                <?php else: ?>
                                <a href="<?= Url::to(['/user/profile/unsubscribe', 'id' => $user->getId()]) ?>" class="btn btn-info">Unsubscribe</a>
                                <?php endif; ?>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="user-right-info d-flex">
                    <div class="user-info-post">
                        <a href="#"><?= $user->getPostCount() ?> posts</a>
                    </div>
                    <div class="user-info-followers">
                        <a href="#" data-toggle="modal" data-target="#FollowersModal"> <?= $user->countFollowers() ?> followers</a>
                    </div>
                    <div class="user-info-following">
                        <a href="#" data-toggle="modal" data-target="#SubcriptionsModal"><?= $user->countSubscriptions() ?> following</a>  
                    </div>
                </div>
                <div class="user-right-description">
                    <?= HtmlPurifier::process($user->about) ?>
                </div>
                <div class="">
                    <?php if ($mutualSubscriptions = $currentUser->getMutualSubscriptionsTo($user)): ?>
                        <br/>Friends, who also following <?= Html::encode($user->username) ?>:
                        <?php foreach ($mutualSubscriptions as $item): ?>
                            <a href="<?= Url::to(['/user/profile/view', 'nickname' => ($item['nickname'] ? $item['nickname'] : $item['id'])]) ?>">
                                <?= Html::encode($item['username']) ?> 
                            </a>
                        <?php endforeach; ?>
                        <br/>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="container">
    <hr>
</div>

<section class="gallery">
    <div class="container">
        <div class="row">
            <?php foreach ($posts as $post): ?>
            <div class="col-sm-6 col-md-4 col-lg-3">
                <div class="gallery-item center-cropped" style="background-image: url('<?= Yii::$app->storage->getFile($post->filename) ?>');">
                    <a href="<?= Url::to(['/post/default/view', 'id' => $post->getId()]) ?>">
                        <img src="<?= Yii::$app->storage->getFile($post->filename) ?>" alt="">
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Modal -->
<div class="modal fade" id="SubcriptionsModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Subcriptions</h4>
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
                <h4 class="modal-title" id="myModalLabel">Followers</h4>
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
