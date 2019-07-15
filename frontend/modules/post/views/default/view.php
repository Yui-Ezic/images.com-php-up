<?php
/* @var $this yii\web\View */
/* @var $post frontend\models\Post */
/* @var $currentUser frontend\models\User */
/* @var $commentForm frontend\modules\post\models\forms\CommentForm */
/* @var $comments[] frontend\models\Comment*/

use yii\bootstrap\Html;
use yii\web\JqueryAsset;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
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
                                
                                <?php if($currentUser): ?>
                                    <a href="#" class="btn btn-default button-unlike <?php echo ($currentUser->likesPost($post->id)) ? "" : "display-none"; ?>" data-id="<?= $post->id; ?>">
                                        Unlike&nbsp;&nbsp;<span class="glyphicon glyphicon-thumbs-down"></span>
                                    </a>
                                    <a href="#" class="btn btn-default button-like <?php echo ($currentUser->likesPost($post->id)) ? "display-none" : ""; ?>" data-id="<?= $post->id; ?>">
                                        Like&nbsp;&nbsp;<span class="glyphicon glyphicon-thumbs-up"></span>
                                    </a>
                                <?php endif; ?>
                            </div>
                            <div class="post-comments">
                                <a href="#"><?= count($comments)?> comments</a>

                            </div>
                            <div class="post-date">
                                <span><?= Yii::$app->formatter->asDatetime($post->created_at) ?></span>    
                            </div>
                            <div class="post-report">
                                <?php if(!$post->isReported($currentUser)):?>
                                <a href="#" class="btn btn-default button-complain" data-id="<?= $post->id ?>">
                                    Report post <i class="fa fa-cog fa-spin fa-fw icon-preloader" style="display:none"></i>
                                </a>    
                                <?php else: ?>
                                <p>Post has been reported</p>
                                <?php endif; ?>  
                            </div>
                        </div>
                    </article>
                    <!-- feed item -->


                    <div class="col-sm-12 col-xs-12">
                        <h4><?= count($comments)?> comments</h4>
                        <div class="comments-post">

                            <div class="single-item-title"></div>
                            <div class="row">
                                <ul class="comment-list">

                                    <!-- comment item -->
                                    <?php foreach($comments as $comment): ?>
                                        <li id='comment-<?=$comment->id?>' class="comment">
                                            <div class="comment-user-image">
                                                <img src="<?= $comment->user->getPicture()?>">
                                            </div>
                                            <div class="comment-info">
                                                <h4 class="author">
                                                    <a href="<?= Url::to(['/user/profile/view', 'nickname' => $comment->user->getNickname()]) ?>">
                                                        <?= $comment->user->username ?>
                                                    </a> 
                                                    <span>(<?= Yii::$app->formatter->asDatetime($comment->created_at) ?>)</span>
                                                </h4>
                                                <p id="textArea-<?=$comment->id?>"><?= Html::encode($comment->text)?></p>
                                                <?php if($currentUser && $currentUser->equals($comment->user)): ?>
                                                    <br/>  
                                                    <a href="#" class="btn btn-default comment-edit">Edit</a>
                                                    <a href="#" class="btn btn-default comment-delete" data-id="<?=$comment->id?>">Delete</a>
                                                    <a href="#" class="btn btn-default comment-refresh display-none" data-id="<?=$comment->id?>">Refresh</a>
                                                <?php endif; ?>
                                            </div>
                                        </li>
                                    <?php endforeach;?>
                                    <!-- comment item -->

                                </ul>
                            </div>

                        </div>
                        <!-- comments-post -->
                    </div>

                    <div class="col-sm-12 col-xs-12">
                        <div class="comment-respond">
                            <h4>Leave a Reply</h4>
                            <?php
                            $form = ActiveForm::begin([
                                        'action' => Url::to(['/post/default/add-comment', 'id' => $post->getId()])
                                    ])
                            ?>
                            <p class="comment-form-comment">
                                <?=
                                $form->field($commentForm, 'text')->textarea([
                                    'rows' => 6,
                                    'aria-required' => 'true',
                                    'class' => 'form-control',
                                    'placeholder' => 'Text'
                                ])->label(false)
                                ?>
                            </p>
                            <p class="form-submit">
                                <?= Html::submitButton('Send', ['class' => 'btn btn-secondary']) ?>
                            </p>
                            <?php ActiveForm::end(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$this->registerJsFile('@web/js/likes.js', [
    'depends' => JqueryAsset::className(),
]);
$this->registerJsFile('@web/js/comments.js', [
    'depends' => JqueryAsset::className(),
]);
$this->registerJsFile('@web/js/complaints.js', [
    'depends' => JqueryAsset::className(),
]);