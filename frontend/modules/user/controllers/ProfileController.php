<?php

namespace frontend\modules\user\controllers;

use yii\web\Controller;
use frontend\models\User;
use Yii;
use frontend\modules\user\models\forms\PictureForm;
use yii\web\UploadedFile;
use yii\web\Response;


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
        /* @var $currentUser User */
        $currentUser = Yii::$app->user->identity;
        
        $modelPicture = new PictureForm;
        
        return $this->render('view', [
            'user' => $this->findUser($nickname),
            'currentUser' => $currentUser,
            'modelPicture' => $modelPicture,
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
        
        if ($currentUser->getId() == $user->getId()) {
            Yii::$app->session->setFlash('error', 'Невозможно подписаться на себя.');
            return $this->redirect(['/user/profile/view', 'nickname' => $user->getNickname()]);
        }
        
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
    
    public function actionUploadPicture()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        if (Yii::$app->user->isGuest) {
            return ['success' => false, 'errors' => 'User is guest'];
        }
        
        $model = new PictureForm;
        $model->picture = UploadedFile::getInstance($model, 'picture');
        
        if ($model->validate()) 
        {    
            /* @var $user frontend\models\User */
            $user = Yii::$app->user->identity;
            $user->picture = Yii::$app->storage->saveUploadedFile($model->picture);
            
            if ($user->save(false, ['picture'])) {
                return ['success' => true, 
                    'pictureUri' => Yii::$app->storage->getFile($user->picture),
                ];
            }
        }
        
        return ['success' => false, 'errors' => $model->getErrors()['picture']];
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
