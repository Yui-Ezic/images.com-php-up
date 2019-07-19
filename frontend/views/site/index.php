<?php
/* @var $this yii\web\View */
/* @var $currentUser frontend\models\User */
/* @var $feedItems[] frontend\models\Feed */

use yii\helpers\Url;
use yii\web\JqueryAsset;
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;

$this->title = 'Newsfeed';
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-lg-offset-2">
            <?php if ($feedItems): ?>
                <?php foreach ($feedItems as $feedItem): ?>
                    <?php /* @var $feedItem Feed */ ?>
                    <div class="post">
                        <div class="post-title d-flex">
                            <div class="post-avatar center-cropped" style="background-image: url('<?php echo $feedItem->author_picture; ?>');">
                                <img src="<?php echo $feedItem->author_picture; ?>" alt="">
                            </div>
                            <div class="post-title-right">
                                <div class="post-author">
                                    <a href="<?= Url::to(['/user/profile/view', 'nickname' => ($feedItem->author_nickname) ? $feedItem->author_nickname : $feedItem->author_id]); ?>">
                                        <?= Html::encode($feedItem->author_name); ?>
                                    </a>
                                </div>
                                <div class="post-date">
                                    <?= Yii::$app->formatter->asDatetime($feedItem->post_created_at); ?>9
                                </div>
                            </div>
                        </div>
                        <div class="post-description">
                            <?php echo HtmlPurifier::process($feedItem->post_description); ?>
                        </div>
                        <div class="post-image">
                            <a href="<?= Url::to(['/post/default/view', 'id' => $feedItem->post_id]) ?>">
                                <img src="<?= Yii::$app->storage->getFile($feedItem->post_filename); ?>" alt="post image">
                            </a>
                        </div>
                        <div class="post-bottom d-flex">
                            <div class="post-like">
                                <a href="#" class="button-like <?php echo ($currentUser->likesPost($feedItem->post_id)) ? "display-none" : ""; ?>" data-id="<?php echo $feedItem->post_id; ?>">
                                    <i class="fa fa-heart-o "></i>
                                    <span class="likes-count"><?php echo $feedItem->countLikes(); ?></span>
                                </a>
                                <a href="#" class="button-unlike <?php echo ($currentUser->likesPost($feedItem->post_id)) ? "" : "display-none"; ?>" data-id="<?php echo $feedItem->post_id; ?>">
                                    <i class="fa fa-heart "></i>
                                    <span class="likes-count"><?php echo $feedItem->countLikes(); ?></span>
                                </a>
                            </div>
                            <div class="post-comment">
                                <a href="#">
                                    <i class="fa fa-comment-o"></i> <?= $feedItem->countComments() ?>
                                </a>
                            </div>
                            <div class="post-report">
                                <a href="#">
                                    <?php if (!$feedItem->isReported($currentUser)): ?>
                                        <a href="#" class="button-complain" data-id="<?= $feedItem->post_id ?>">
                                            <i class="fa fa-bullhorn"></i> Report <i class="fa fa-cog fa-spin fa-fw icon-preloader" style="display:none"></i>
                                        </a>    
                                    <?php else: ?>
                                        <i class="fa fa-bullhorn"></i> Reported
                                    <?php endif; ?>
                                </a>
                            </div>
<!--                            <div class="post-delete">
                                <a href=""><i class="fa fa-trash-o"></i></a>
                            </div>-->
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-md-12">
                    Nobody posted yet!
                </div>
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
