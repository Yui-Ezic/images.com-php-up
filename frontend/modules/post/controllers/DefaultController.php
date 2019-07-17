<?php

namespace frontend\modules\post\controllers;

use yii\web\Controller;
use frontend\modules\post\models\forms\PostForm;
use yii\web\UploadedFile;
use Yii;
use frontend\models\Post;
use yii\web\Response;
use frontend\models\User;
use frontend\modules\post\models\forms\CommentForm;
use frontend\models\Comment;

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
        
        /* @var $currentUser User */
        $currentUser = Yii::$app->user->identity;
        $model = new PostForm($currentUser);
        
        if ($model->load(Yii::$app->request->post()))
        {
            $model->picture = UploadedFile::getInstance($model, 'picture');
            
            if($post = $model->save())
            {
                Yii::$app->session->setFlash('success', 'Post created!');
                return $this->redirect(['/post/default/view', 'id' => $post->getId()]);
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
        
        /* @var $post Post*/
        if (!$post = $this->findPost($id))
        {
            throw new \yii\web\NotFoundHttpException();
        }
        
        $commentForm = new CommentForm();
        
        $comments = $post->getAvaliableComments();
        
        return $this->render('view', [
            'post' => $post,
            'currentUser' => $currentUser,
            'commentForm' => $commentForm,
            'comments' => $comments,
        ]);
    }
    
    /**
     * @param integer $id
     * @return User
     * @throws NotFoundHttpException
     */
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
    
    public function actionAddComment ($id) {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/user/default/login']);
        }
        
        $model = new CommentForm(Yii::$app->user->identity, $id);
        
        if ($model->load(Yii::$app->request->post()))
        {            
            if($model->save())
            {
                Yii::$app->session->setFlash('success', 'Comment created!');
            }
            else 
            {
                Yii::$app->session->setFlash('danger', 'Error occurred!');
            }
        }
        
        return $this->redirect(['/post/default/view',
            'id' => $id,
        ]);
    }
    
    public function actionDeleteComment()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/user/default/login']);
        }
        
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $id = Yii::$app->request->post('id');
        
        /* @var $comment Comment*/
        $comment = Comment::getById($id);
        
        /* @var $currentUser User*/
        $currentUser = Yii::$app->user->identity;
        
        if (!$currentUser->equals($comment->user)){
            return [
                'success' => false,
                'error' => 'Wrong user'
            ];
        }
        
        if ($comment->delete())
        {
            return ['success' => true];
        }
        
        return [
                'success' => false,
                'error' => "Can\'t delete"
            ];
    }
    
    public function actionRefreshComment()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/user/default/login']);
        }
        
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $id = Yii::$app->request->post('id');
        
        /* @var $comment Comment*/
        $comment = Comment::getById($id);
        
        /* @var $currentUser User*/
        $currentUser = Yii::$app->user->identity;
        
        if (!$currentUser->equals($comment->user)){
            return [
                'success' => false,
                'error' => 'Wrong user'
            ];
        }
        
        if ($comment->refreshComment())
        {
            return ['success' => true, 'text' => $comment->text];
        }
        
        return [
                'success' => false,
                'error' => "Can\'t refresh"
            ];
    }
    
    public function actionComplain()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/user/default/login']);
        }

        Yii::$app->response->format = Response::FORMAT_JSON;

        $id = Yii::$app->request->post('id');

        /* @var $currentUser User */
        $currentUser = Yii::$app->user->identity;
        $post = $this->findPost($id);

        if ($post->complain($currentUser)) {
            return [
                'success' => true,
                'text' => 'Post reported'
            ];
        }
        return [
            'success' => false,
            'text' => 'Error',
        ];
    }
    
    /**
     * Deletes an existing Post model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
         if (Yii::$app->user->isGuest) {
            return $this->redirect(['/user/default/login']);
        }   
        
        /* @var $comment Comment*/
        $post = $this->findPost($id);
        
        /* @var $currentUser User*/
        $currentUser = Yii::$app->user->identity;
        
        if ($currentUser->getId() != $post->user_id){
            Yii::$app->session->setFlash('danger', 'Permission denied');
            return $this->redirect(['/user/default/login']);
        }
        
        if(Post::DeletePostById($id)){
            Yii::$app->session->setFlash('success', 'Post deleted');
        }

        return $this->goHome();
    }
}
