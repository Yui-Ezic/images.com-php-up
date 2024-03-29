<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use frontend\models\User;
use yii\web\Cookie;
use yii\filters\AccessControl;
use frontend\models\Post;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }
    
    /**
     * {@inheritdoc}
     */
    public function behaviors(): array {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['language'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['language'],
                        'verbs' => ['POST'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/user/default/login']);
        }
        
        /* @var $currentUser User*/
        $currentUser = Yii::$app->user->identity;
        
        $limit = Yii::$app->params['feedPostLimit'];
        $feedItems = $currentUser->getFeed($limit);
        
        return $this->render('index', [
            'feedItems' => $feedItems,
            'currentUser' => $currentUser,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
    
    /**
     * Change language
     * @return mixed
     */
    public function actionLanguage()
    {   
        $language = Yii::$app->request->post('language');
        
        $supportedLanguages = Yii::$app->params['supportedLanguages'];
        
        if (!isset($language) || !in_array($language, $supportedLanguages))
        {
            Yii::$app->session->setFlash('danger', 'Unknown language');
            return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
        }
        
        Yii::$app->language = $language;
        
        $languageCookie = new Cookie([
            'name' => 'language',
            'value' => $language,
            'expire' => time() + 60 * 60 * 24 * 30, // 30 days
        ]);
        Yii::$app->response->cookies->add($languageCookie);
        return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
    }
    
    public function actionRecentActions()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/user/default/login']);
        }
        
        /* @var $currentUser User*/
        $currentUser = Yii::$app->user->identity;
        
        $limit = Yii::$app->params['feedPostLimit'];
        $posts = Post::getRecentPosts($limit);
        
        return $this->render('recentActions', [
            'currentUser' => $currentUser,
            'posts' => $posts,
        ]);
    }
}
