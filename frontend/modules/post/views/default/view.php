<?php
/* @var $this yii\web\View */
/* @var $post frontend\models\Post */
/* @var $currentUser frontend\models\User */

use yii\bootstrap\Html;
use yii\web\JqueryAsset;
use yii\helpers\Url;
?>

<div class="page-posts no-padding">
    <div class="row">
        <div class="page page-post col-sm-12 col-xs-12 post-82">


            <div class="blog-posts blog-posts-large">

                <div class="row">

                    <!-- feed item -->
                    <article class="post col-sm-12 col-xs-12">                                            
                        <div class="post-meta">
                            <div class="post-title">
                                <?php if ($post->user): ?>
                                    <img src="<?= $post->user->getPicture() ?>" class="author-image" />
                                    <div class="author-name">
                                        <a href="<?= Url::to(['/user/profile/view', 'nickname' => $post->user->getNickname()]) ?>">
                                            <?= $post->user->username ?>
                                        </a>
                                    </div>
                                <?php else: ?>
                                    Error: Unknown User
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="post-type-image">
                            <a href="#">
                                <img src="<?= $post->getImage() ?>" alt="">
                            </a>
                        </div>
                        <div class="post-description">
                            <p><?= Html::encode($post->description) ?></p>
                        </div>
                        <div class="post-bottom">
                            <div class="post-likes">
<!--                                <a href="#" class="btn btn-secondary"><i class="fa fa-lg fa-heart-o"></i></a>
                                <span>6 Likes</span>-->

                                Likes: <span class="likes-count"><?= $post->countLikes(); ?></span>

                                <a href="#" class="btn btn-default button-unlike <?php echo ($currentUser->likesPost($post->id)) ? "" : "display-none"; ?>" data-id="<?= $post->id; ?>">
                                    Unlike&nbsp;&nbsp;<span class="glyphicon glyphicon-thumbs-down"></span>
                                </a>
                                <a href="#" class="btn btn-default button-like <?php echo ($currentUser->likesPost($post->id)) ? "display-none" : ""; ?>" data-id="<?= $post->id; ?>">
                                    Like&nbsp;&nbsp;<span class="glyphicon glyphicon-thumbs-up"></span>
                                </a>
                            </div>
                            <div class="post-comments">
                                <a href="#">5 comments</a>

                            </div>
                            <div class="post-date">
                                <span><?= Yii::$app->formatter->asDatetime($post->created_at) ?></span>    
                            </div>
                            <div class="post-report">
                                <a href="#">Report post</a>    
                            </div>
                        </div>
                    </article>
                    <!-- feed item -->


                    <div class="col-sm-12 col-xs-12">
                        <h4>5 comments</h4>
                        <div class="comments-post">

                            <div class="single-item-title"></div>
                            <div class="row">
                                <ul class="comment-list">

                                    <!-- comment item -->
                                    <li class="comment">
                                        <div class="comment-user-image">
                                            <img src="img/demo/avatar.jpg">
                                        </div>
                                        <div class="comment-info">
                                            <h4 class="author"><a href="#">Firstname Lastname</a> <span>(April 10, 2017)</span></h4>
                                            <p>Lorem ipsum dolor sit amet, iisque bonorum consequat an vis, ea dico sonet dolorum eam!</p>
                                        </div>
                                    </li>
                                    <!-- comment item -->

                                </ul>
                            </div>

                        </div>
                        <!-- comments-post -->
                    </div>

                    <div class="col-sm-12 col-xs-12">
                        <div class="comment-respond">
                            <h4>Leave a Reply</h4>
                            <form action="#" method="post">
                                <p class="comment-form-comment">
                                    <textarea name="comment" rows="6" class="form-control" placeholder="Text" aria-required="true"></textarea>
                                </p>
                                <p class="form-submit">
                                    <button type="submit" class="btn btn-secondary">Send</button> 
                                </p>				
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->registerJsFile('@web/js/likes.js', [
    'depends' => JqueryAsset::className(),
]);
