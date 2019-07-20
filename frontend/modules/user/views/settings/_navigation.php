<?php
/* @var $this yii\web\View */

use yii\bootstrap\Nav;
?>
<div class="col-sm-3 user-settings-left">
    <?php
    $menuItems = [
        ['label' => Yii::t('Edit profile', 'Edit profile'), 'url' => ['/user/settings/edit']],
        ['label' => Yii::t('Change avatar', 'Change avatar'), 'url' => ['/user/settings/avatar']],
        ['label' => Yii::t('Change password', 'Change password'), 'url' => ['/user/settings/change-password']]
    ];
    

    echo Nav::widget([
        'options' => ['class' => 'nav settings-nav'],
        'items' => $menuItems,
    ]);
    ?>

</div>