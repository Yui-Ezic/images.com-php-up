<?php

namespace frontend\modules\user\components;

use frontend\modules\user\models\Auth;
use frontend\models\User;
use Yii;
use yii\authclient\ClientInterface;
use yii\helpers\ArrayHelper;

/**
 * AuthHandler handles successful authentication via Yii auth component
 */
class AuthHandler
{
    /**
     * @var ClientInterface
     */
    private $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    public function handle()
    {
        if (!Yii::$app->user->isGuest){
            return;
        }
        
        $attributes = $this->client->getUserAttributes();
        
        $auth = $this->findAuth($attributes);
        
        if($auth) {
            /* @var User $user */
            $user = $auth->user;
            return Yii::$app->user->login($user);
        }
        
        if($user = $this->createAccount($attributes)) {
            return Yii::$app->user->login($user);
        }
    }
    
    private function findAuth($attributes){
        $id = ArrayHelper::getValue($attributes, 'id');
        $params = [
            'source_id' => $id,
            'source' => $this->client->getId(),
        ];
        return Auth::find()->where($params)->one();
    }
    
    private function createAccount($attributes) {
        $id = ArrayHelper::getValue($attributes, 'id');
        $email = ArrayHelper::getValue($attributes, 'email');
        $name = ArrayHelper::getValue($attributes, 'name');
        
        if ($email !== null && User::find()->where(['email' => $email])->exists()) {
            Yii::$app->getSession()->setFlash('error', [
                        Yii::t('app', "User with the same email as in {client} account already exists but isn't linked to it. Login using email first to link it.", ['client' => $this->client->getTitle()]),
                    ]);
            return;
        }
        
        $user = $this->createUser($email, $name);
        
        $transaction = User::getDb()->beginTransaction();
        if($user->save()) {
            $auth = $this->createAuth($user->id, $id);
            if($auth->save()) {
                $transaction->commit();
                return $user;
            }
        }
        $transaction->rollBack();
    }
    
    private function createUser($email, $name){
        $password = Yii::$app->security->generateRandomString(9);
        $user = new User([
            'username' => $name,
            'email' => $email,
            'auth_key' => Yii::$app->security->generateRandomString(),
            'password_hash' => Yii::$app->security->generatePasswordHash($password),
            'created_at' => $time = time(),
            'updated_at' => $time,
        ]);
        return $user;
    }
    
    private function createAuth($userId, $sourceId) {
        return new Auth([
            'user_id' => $userId,
            'source' => $this->client->getId(),
            'source_id' => $sourceId,
        ]);
    }

        /**
     * @param User $user
     */
    private function updateUserInfo(User $user)
    {
        $attributes = $this->client->getUserAttributes();
        $github = ArrayHelper::getValue($attributes, 'login');
        if ($user->github === null && $github) {
            $user->github = $github;
            $user->save();
        }
    }
}
