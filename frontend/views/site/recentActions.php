<?php
/* @var $this yii\web\View */
/* @var $currentUser frontend\models\User */
/* @var $posts[] frontend\models\Post */

use yii\helpers\Url;
use yii\web\JqueryAsset;
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;

$this->title = 'Newsfeed';
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-lg-offset-2">
            <?php if ($posts): ?>
                <?php foreach ($posts as $post): ?>
                    <?php /* @var $post frontend\models\Post*/ ?>
                    <div class="post">
                        <div class="post-title d-flex">
                            <div class="post-avatar center-cropped" style="background-image: url('<?= $post->user->getPicture() ?>');">
                                <img src="<?= $post->user->getPicture() ?>" alt="">
                            </div>
                            <div class="post-title-right">
                                <div class="post-author">
                                    <a href="<?= Url::to(['/user/profile/view', 'nickname' => $post->user->getNickname()]) ?> ">
                                        <?= Html::encode($post->user->username); ?>
                                    </a>
                                </div>
                                <div class="post-date">
                                    <?= Yii::$app->formatter->asDatetime($post->created_at); ?>9
                                </div>
                            </div>
                        </div>
                        <div class="post-description">
                            <?php echo HtmlPurifier::process($post->description); ?>
                        </div>
                        <div class="post-image">
                            <a href="<?= Url::to(['/post/default/view', 'id' => $post->id]) ?>">
                                <img src="<?= Yii::$app->storage->getFile($post->filename); ?>" alt="post image">
                            </a>
                        </div>
                        <div class="post-bottom d-flex">
                            <div class="post-like">
                                <a href="#" class="button-like <?php echo ($currentUser->likesPost($post->id)) ? "display-none" : ""; ?>" data-id="<?= $post->id; ?>">
                                    <i class="fa fa-heart-o "></i>
                                    <span class="likes-count"><?php echo $post->countLikes(); ?></span>
                                </a>
                                <a href="#" class="button-unlike <?php echo ($currentUser->likesPost($post->id)) ? "" : "display-none"; ?>" data-id="<?= $post->id; ?>">
                                    <i class="fa fa-heart "></i>
                                    <span class="likes-count"><?php echo $post->countLikes(); ?></span>
                                </a>
                            </div>
                            <div class="post-comment">
                                <a href="<?= Url::to(['/post/default/view', 'id' => $post->id]) ?>">
                                    <i class="fa fa-comment-o"></i> <?= $post->countComments() ?>
                                </a>
                            </div>
                            <div class="post-report">
                                <?php if (!$post->isReported($currentUser)): ?>
                                    <a href="#" class="button-complain" data-id="<?= $post->id ?>">
                                        <i class="fa fa-bullhorn"></i> Report <i class="fa fa-cog fa-spin fa-fw icon-preloader" style="display:none"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <h1> Nobody posted yet! </h1>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
$this->registerJsFile('@web/js/likes.js', [
    'depends' => JqueryAsset::className(),
]);

$this->registerJsFile('@web/js/complaints.js', [
    'depends' => JqueryAsset::className(),
]);
