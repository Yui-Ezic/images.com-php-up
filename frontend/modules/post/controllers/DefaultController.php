<?php

namespace frontend\modules\post\controllers;

use yii\web\Controller;
use frontend\modules\post\models\forms\PostForm;
use yii\web\UploadedFile;
use Yii;
use frontend\models\Post;

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
        return $this->render('view', [
            'post' => $this->findPost($id),
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
}
