<?php

/* @var $this yii\web\View */
/* @var $post frontend\models\Post*/

use yii\bootstrap\Html;
?>
    
<div class="post-default-index">
    <div class="row">
        <div class="col-md-12">
            <?php if($post->user): ?>
                <?= $post->user->username ?>
            <?php endif; ?>
        </div>
        <div class="col-md-12">
            <img src="<?= $post->getImage() ?>" alt="post-image" />
        </div>
        <div class="col-md-12">
            <?= Html::encode($post->description) ?>
        </div>
    </div>
</div>
