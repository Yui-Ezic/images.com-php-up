<?php

namespace frontend\modules\post\controllers;

use yii\web\Controller;
use frontend\modules\post\models\forms\PostForm;
use yii\web\UploadedFile;
use Yii;
use frontend\models\Post;
use yii\web\Response;
use frontend\models\User;

/**
 * Default controller for the `post` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the create viewfor the module
     * @return string
     */
    public function actionCreate() 
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/user/default/login']);
        }
        
        $model = new PostForm(Yii::$app->user->identity);
        
        if ($model->load(Yii::$app->request->post()))
        {
            $model->picture = UploadedFile::getInstance($model, 'picture');
            
            if($model->save())
            {
                Yii::$app->session->setFlash('success', 'Post created!');
                return $this->goHome();
            }
        }
        
        return $this->render('create', [
            'model' => $model,
        ]);
    }
    
    public function actionView($id) 
    {
        /* @var $currentUser User*/
        $currentUser = Yii::$app->user->identity;
        
        return $this->render('view', [
            'post' => $this->findPost($id),
            'currentUser' => $currentUser,
        ]);
    }
    
    private function findPost($id) 
    {
        if($post = Post::findOne($id))
        {
            return $post;
        }
        throw new NotFoundHttpException();
    }
    
    public function actionLike() {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/user/default/login']);
        }
        
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        /* @var $currentUser User*/
        $currentUser = Yii::$app->user->identity;
        
        $id = Yii::$app->request->post('id');
        $post = $this->findPost($id);
        
        $post->like($currentUser);
        
        return [
            'success' => true,
            'likesCount' => $post->countLikes(),
        ];
    }
    
    public function actionUnlike()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/user/default/login']);
        }
        
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        /* @var $currentUser User*/
        $currentUser = Yii::$app->user->identity;
        
        $id = Yii::$app->request->post('id');
        $post = $this->findPost($id);
        
        $post->unlike($currentUser);
        
        return [
            'success' => true,
            'likesCount' => $post->countLikes(),
        ];
    }
}
