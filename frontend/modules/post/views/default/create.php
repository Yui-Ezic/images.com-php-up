<?php

/* @var $this yii\web\View */
/* @var $model frontend\modules\post\models\forms\PostForm*/

use yii\widgets\ActiveForm;
use yii\helpers\Html;

?>

<div class="post-default-index">
    <h1>Create post</h1>
    
    <?php $form = ActiveForm::begin(); ?>
        
        <?= $form->field($model, 'picture')->fileInput(); ?>
    
        <?= $form->field($model, 'description'); ?>
    
        <?= Html::submitButton('Create'); ?>
    
    <?php ActiveForm::end() ?>
</div>


