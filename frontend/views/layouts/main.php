<?php
/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use frontend\assets\AppAsset;
use frontend\assets\FontAwesomeAsset;
use common\widgets\Alert;
use yii\helpers\Url;

AppAsset::register($this);
FontAwesomeAsset::register($this);
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
    <body class="home page">
        <?php $this->beginBody() ?>
        <nav class="navbar navbar-default navbar-fixed-top">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">Images</a>
                </div>
                <div id="navbar" class="navbar-collapse collapse">
                    <?php
                    $menuItems = [
                        ['label' => Yii::t('menu', 'Newsfeed'), 'url' => ['/site/index']],
                    ];
                    if (Yii::$app->user->isGuest) {
                        $menuItems[] = ['label' => Yii::t('menu', 'Signup'), 'url' => ['/user/default/signup']];
                        $menuItems[] = ['label' => Yii::t('menu', 'Login'), 'url' => ['/user/default/login']];
                    } else {
                        $menuItems[] = ['label' => Yii::t('menu', 'My profile'), 'url' => ['/user/profile/view', 'nickname' => Yii::$app->user->identity->getNickname()]];
                        $menuItems[] = ['label' => Yii::t('menu', 'Create Post'), 'url' => ['/post/default/create']];
                        $menuItems[] = '<li>'
                                . Html::beginForm(['/user/default/logout'], 'post')
                                . Html::submitButton(
                                        Yii::t('menu', 'Logout ({username})', [
                                            'username' => Yii::$app->user->identity->username
                                        ]) . ' <i class="fa fa-sign-out"></i>',
                                        ['class' => 'btn btn-link logout']
                                )
                                . Html::endForm()
                                . '</li>';
                    }

                    echo Nav::widget([
                        'options' => ['class' => 'nav navbar-nav navbar-right'],
                        'items' => $menuItems,
                    ]);
                    ?>
                </div><!--/.nav-collapse -->
            </div>
        </nav>

        <div class="wrapper">
            <div class="container">
                <?= Alert::widget() ?>
                <?= $content ?>
            </div>
        </div>

        <footer>
            <div class="container">
                <div class="row">
                    <div class="col text-center">
                        <a href="#">
                            Images | 2019
                        </a>
                    </div>
                </div>
            </div>
        </footer>
        <?php $this->endBody() ?>
    </body>

</html>
<?php $this->endPage() ?>