<?php

/* @var $this yii\web\View */
/* @var $currentUser backend\models\User */

use yii\helpers\Url;

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Welcome, <?= $currentUser->username ?></h1>

        <p class="lead">Your roles: <?= implode(',', $currentUser->getRoles()) ? : 'empty' ?></p>
    </div>
</div>
