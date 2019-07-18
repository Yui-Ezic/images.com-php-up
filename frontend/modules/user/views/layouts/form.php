<!DOCTYPE html>
<?php
/* @var $this \yii\web\View */
/* @var $content string */

use Yii;
use frontend\modules\user\assets\FormAsset;
use yii\bootstrap\Html;
use common\widgets\Alert;

FormAsset::register($this);
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?php $this->registerCsrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <!--===============================================================================================-->
</head>
<body>
    <?php $this->beginBody() ?>
    <div class="limiter">
        <div class="container-login100" style="background-image: url('<?= Yii::$app->params['frontendUri'] . 'user/images/bg-01.jpg'?>');">
            <div class="wrap-login100 p-l-110 p-r-110 p-t-62 p-b-33">
                <?= Alert::widget() ?>
                <?= $content ?>
            </div>
        </div>
    </div>
    <div id="dropDownSelect1"></div>	
    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>