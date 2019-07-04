<?php

namespace frontend\modules\user\controllers;

use yii\web\Controller;
use frontend\models\User;
use Yii;


/**
 * Default controller for the `user` module
 */
class ProfileController extends Controller
{
    /**
     * Renders the view for the user profile
     * @return string
     */
    public function actionView($nickname)
    {
        return $this->render('view', [
            'user' => $this->findUser($nickname),
            'currentUser' => Yii::$app->user->identity,
        ]);
    }
    
    /**
     * @param string $nickname
     * @return User
     * @throws NotFoundHttpException
     */
    private function findUser($nickname){
        $user = User::find()->where(['nickname' => $nickname])->orWhere(['id' => $nickname])->one();
        if($user) {
            return $user;
        }
        throw new NotFoundHttpException();
    }
    
    /**
     * @param integer $id
     * @return type
     */
    public function actionSubscribe($id) 
    {
        if(Yii::$app->user->isGuest){
            return $this->redirect(['/user/default/login']);
        }
        
        /* @var $currentUser User */
        $currentUser = Yii::$app->user->identity;
        $user = $this->getUserById($id);
        
        $currentUser->followUser($user);
        
        return $this->redirect(['/user/profile/view', 'nickname' => $user->getNickname()]);
    }
    
    /**
     * @param integer $id
     * @return type
     */
    public function actionUnsubscribe($id) 
    {
        if(Yii::$app->user->isGuest){
            return $this->redirect(['/user/default/login']);
        }
        
        /* @var $currentUser User */
        $currentUser = Yii::$app->user->identity;
        $user = $this->getUserById($id);
        
        $currentUser->unfollowUser($user);
        
        return $this->redirect(['/user/profile/view', 'nickname' => $user->getNickname()]);
    }
    
    
    /**
     * @param integer $id
     * @return User
     * @throws NotFoundHttpException
     */
    private function getUserById($id)
    {
        if($user = User::findOne($id)) {
            return $user;
        }
        throw new NotFoundHttpException();
    }


    public function actionGenerate() 
    {
        $faker = \Faker\Factory::create();
        
        for($i = 0; $i < 1000; $i++)
        {
            $user = new User([
                'username' => $faker->name, 
                'email' => $faker->email,
                'about' => $faker->text(200),
                'nickname' => $faker->regexify('[A-Za-z0-9_]{5,15}'),
                'auth_key' => Yii::$app->security->generateRandomString(),
                'password_hash' => Yii::$app->security->generateRandomString(),
                'created_at' => $time = time(),
                'updated_at' => $time,
            ]);
            $user->save(false);
        }
    }
}
