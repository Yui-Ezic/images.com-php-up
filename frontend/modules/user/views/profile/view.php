<?php
/* @var $user frontend\models\User */
/* @var $currentUser frontend\models\User */
/* @var $modelPicture frontend\modules\user\models\forms\PictureForm */

use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\helpers\Url;
use dosamigos\fileupload\FileUpload;

?>
<h1> <?= Html::encode($user->username) ?> </h1>
<p> <?= HtmlPurifier::process($user->about) ?> </p>
<hr/>

<img src="<?= $user->getPicture() ?>" alt="User picture">
<?= FileUpload::widget([
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
                                console.log(e);
                                console.log(data);
                            }',
        'fileuploadfail' => 'function(e, data) {
                                console.log(e);
                                console.log(data);
                            }',
    ],
]); ?>

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
