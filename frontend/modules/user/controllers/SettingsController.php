<?php

namespace frontend\modules\user\controllers;

use Yii;
use frontend\models\User;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\web\Response;
use frontend\modules\user\models\forms\PictureForm;

class SettingsController extends \yii\web\Controller {

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionEdit() {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/user/default/login']);
        }

        /* @var $currentUser User */
        $currentUser = Yii::$app->user->identity;

        if ($currentUser->load(Yii::$app->request->post()) && $currentUser->save()) {
            return $this->redirect(['/user/profile/view', 'nickname' => $currentUser->getNickname()]);
        }

        return $this->render('edit', [
                    'currentUser' => $currentUser,
        ]);
    }

    public function actionAvatar() {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/user/default/login']);
        }

        $modelPicture = new PictureForm;

        /* @var $currentUser User */
        $currentUser = Yii::$app->user->identity;

        return $this->render('avatar', [
                    'currentUser' => $currentUser,
                    'modelPicture' => $modelPicture,
        ]);
    }

    /**
     * @return json array
     */
    public function actionUploadPicture() {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (Yii::$app->user->isGuest) {
            return ['success' => false, 'errors' => 'User is guest'];
        }

        $model = new PictureForm;
        $model->picture = UploadedFile::getInstance($model, 'picture');

        if ($model->validate()) {
            /* @var $user User */
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

    public function actionDeletePicture() {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/user/default/login']);
        }

        /* @var $user User */
        $user = Yii::$app->user->identity;
        if ($user->deletePicture()) {
            Yii::$app->session->setFlash('success', 'Picture deleted');
        } else {
            Yii::$app->session->setFlash('danger', 'Error occured');
        }

        return $this->redirect(['/user/settings/avatar']);
    }

    public function actionChangePassword() {
        /* @var $currentUser User */
        $currentUser = Yii::$app->user->identity;

        return $this->render('ChangePassword', [
            'currentUser' => $currentUser,
        ]);
    }

}
