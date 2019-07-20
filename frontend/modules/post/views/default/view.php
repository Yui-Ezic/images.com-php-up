<?php
/* @var $this yii\web\View */
/* @var $post frontend\models\Post */
/* @var $currentUser frontend\models\User */
/* @var $commentForm frontend\modules\post\models\forms\CommentForm */
/* @var $comments[] frontend\models\Comment */

use yii\bootstrap\Html;
use yii\web\JqueryAsset;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-lg-offset-2">
            <div class="post">
                <div class="post-title d-flex">
                    <div class="post-avatar center-cropped" style="background-image: url('<?= $post->user->getPicture() ?>');">
                        <img src="<?= $post->user->getPicture() ?>" alt="">
                    </div>
                    <div class="post-title-right">
                        <div class="post-author">
                            <a href="<?= Url::to(['/user/profile/view', 'nickname' => $post->user->getNickname()]) ?>">
                                <?= $post->user->username ?>
                            </a>
                        </div>
                        <div class="post-date">
                            <?= Yii::$app->formatter->asDatetime($post->created_at) ?>
                        </div>
                    </div>
                </div>
                <div class="post-description">
                    <?= Html::encode($post->description) ?>
                </div>
                <div class="post-image">
                    <img src="<?= $post->getImage() ?>" alt="post image">
                </div>
                <div class="post-bottom d-flex">
                    <?php if ($currentUser): ?>
                        <div class="post-like">
                            <a href="#" class="button-like <?php echo ($currentUser->likesPost($post->id)) ? "display-none" : ""; ?>" data-id="<?php echo $post->id; ?>">
                                <i class="fa fa-heart-o "></i>
                                <span class="likes-count"><?php echo $post->countLikes(); ?></span>
                            </a>
                            <a href="#" class="button-unlike <?php echo ($currentUser->likesPost($post->id)) ? "" : "display-none"; ?>" data-id="<?php echo $post->id; ?>">
                                <i class="fa fa-heart "></i>
                                <span class="likes-count"><?php echo $post->countLikes(); ?></span>
                            </a>
                        </div>
                    <?php endif; ?>
                    <div class="post-comment">
                        <a href="#comments">
                            <i class="fa fa-comment-o"></i> <?= $post->countComments() ?>
                        </a>
                    </div>
                    <?php if ($currentUser): ?>
                        <div class="post-report">
                            <a href="#">
                                <?php if (!$post->isReported($currentUser)): ?>
                                    <a href="#" class="button-complain" data-id="<?= $post->id ?>">
                                        <i class="fa fa-bullhorn"></i> Report <i class="fa fa-cog fa-spin fa-fw icon-preloader" style="display:none"></i>
                                    </a>    
                                <?php else: ?>
                                    <i class="fa fa-bullhorn"></i> Reported
                                <?php endif; ?>
                            </a>
                        </div>
                    <?php endif; ?>
                    <?php if ($currentUser && $currentUser->getId() == $post->user_id): ?>   
                        <div class="post-delete">
                            <a href="<?= Url::to(["/post/default/delete", 'id' => $post->getId()]) ?>"><i class="fa fa-trash-o"></i></a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-lg-8 col-lg-offset-2">
            <div class="comments">
                <?php if ($currentUser): ?>
                    <div class="comment d-flex">
                        <div class="comment-author-avatar center-cropped" style="background-image: url('<?= $currentUser->getPicture() ?>');">
                            <img src="<?= $currentUser->getPicture() ?>" alt="">
                        </div>
                        <div class="comment-right">
                            <div class="comment-author-name">
                                <a href="<?= Url::to(['/user/profile/view', 'nickname' => $currentUser->getNickname()]) ?>">
                                    <?= $currentUser->username ?>
                                </a> 
                            </div>
                            <div class="comment-text">
                                <?php
                                $form = ActiveForm::begin([
                                            'action' => Url::to(['/post/default/add-comment', 'id' => $post->getId()])
                                        ])
                                ?>

                                <p class="comment-form-comment">
                                    <?=
                                    $form->field($commentForm, 'text')->textarea([
                                        'rows' => 2,
                                        'aria-required' => 'true',
                                        'class' => 'form-control',
                                        'placeholder' => 'Text'
                                    ])->label(false)
                                    ?>
                                </p>
                                <p class="form-submit">
                                    <?= Html::submitButton('Send', ['class' => 'btn btn-secondary']) ?>
                                </p>
                                <?php ActiveForm::end() ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <?php foreach ($comments as $comment): ?>
                    <div id='comment-<?= $comment->id ?>' class="comment d-flex">
                        <div class="comment-author-avatar center-cropped" style="background-image: url('<?= $comment->user->getPicture() ?>');">
                            <img src="<?= $comment->user->getPicture() ?>" alt="">
                        </div>
                        <div class="comment-right">
                            <div class="comment-author-name">
                                <a href="<?= Url::to(['/user/profile/view', 'nickname' => $comment->user->getNickname()]) ?>">
                                    <?= $comment->user->username ?>
                                </a> 
                            </div>
                            <div id="textArea-<?= $comment->id ?>" class="comment-text">
                                <?= Html::encode($comment->text) ?>
                            </div>
                            <?php if ($currentUser && $currentUser->equals($comment->user)): ?>
                                <div class="comment-buttons d-flex">
                                    <a href="#" class="comment-delete" data-id="<?= $comment->id ?>"><i class="fa fa-trash-o"></i></a>
                                    <a href="#" class="comment-refresh display-none" data-id="<?= $comment->id ?>"><i class="fa fa-refresh"></i></a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
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
