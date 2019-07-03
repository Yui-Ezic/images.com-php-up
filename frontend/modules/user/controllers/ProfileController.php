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
    public function actionView($id)
    {
        return $this->render('view', [
            'user' => $this->findUser($id),
        ]);
    }
    
    private function findUser($id){
        if($user = User::find()->where(['id' => $id])->one()) {
            return $user;
        }
        throw new NotFoundHttpException();
    }
}
