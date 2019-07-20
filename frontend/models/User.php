<?php
namespace frontend\models;

use Yii;
use common\models\UserParent;
use yii\redis\Connection;
use frontend\models\events\UserNewFollowedEvent;
use frontend\models\events\UserUnfollowedEvent;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $verification_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $about
 * @property integer $type
 * @property string $nickname
 * @property string $picture
 * @property string $password write-only password
 */
class User extends UserParent
{   
    
    const MUTUAL_SUBSCRIPTIONS_LIMIT = 3;
    
    /**
     * {@inheritdoc}
     */
    public function init() {
        parent::init();
        $this->on(self::EVENT_USER_UNFOLLOWED, [Yii::$app->feedService, 'unfollowUser']);
        $this->on(self::EVENT_USER_NEW_FOLLOWED, [Yii::$app->feedService, 'followUser']);
    }
    
    /**
     * @return mixed
     */
    public function getNickname() 
    {
        return $this->nickname ? $this->nickname : $this->getId();
    }
    
    /**
     * Subscribe current user to given user
     * @param \frontend\models\User $user
     */
    public function followUser(User $user)
    {       
        /* @var $redis Connection*/
        if ($this->getId() == $user->getId()) {
            return;
        }
        $redis = Yii::$app->redis;
        $redis->sadd("user:{$this->getId()}:subscriptions", $user->getId());
        $redis->sadd("user:{$user->getId()}:followers", $this->getId());
        
        $event = new UserNewFollowedEvent();
        $event->user_id = $this->getId();
        $event->author_id = $user->getId();
        $this->trigger(self::EVENT_USER_NEW_FOLLOWED, $event);
    }
    
    /**
     * Unsubscribe current user to given user
     * @param \frontend\models\User $user
     */
    public function unfollowUser(User $user)
    {       
        /* @var $redis Connection*/
        $redis = Yii::$app->redis;
        $redis->srem("user:{$this->getId()}:subscriptions", $user->getId());
        $redis->srem("user:{$user->getId()}:followers", $this->getId());
        
        $event = new UserUnfollowedEvent();
        $event->user_id = $this->getId();
        $event->author_id = $user->getId();
        $this->trigger(self::EVENT_USER_UNFOLLOWED, $event);
    }
    
    /**
     * @return array
     */
    public function getSubscriptions()
    {
        /* @var $redis Connection*/
        $redis = Yii::$app->redis;
        $ids = $redis->smembers("user:{$this->getId()}:subscriptions");
        return User::find()->select('id, username, nickname')->where(['id' => $ids])->orderBy('username')->asArray()->all();
    }
    
    /**
     * @return array
     */
    public function getFollowers()
    {
        /* @var $redis Connection*/
        $redis = Yii::$app->redis;
        $ids = $redis->smembers("user:{$this->getId()}:followers");
        return User::find()->select('id, username, nickname')->where(['id' => $ids])->orderBy('username')->asArray()->all();
    }
    
    /**
     * @return integer
     */
    public function countSubscriptions()
    {
        /* @var $redis Connection*/
        $redis = Yii::$app->redis;
        return $redis->scard("user:{$this->getId()}:subscriptions");
    }
    
    /**
     * @return ineger
     */
    public function countFollowers()
    {
        /* @var $redis Connection*/
        $redis = Yii::$app->redis;
        return $redis->scard("user:{$this->getId()}:followers");
    }
    
    /**
     * @param \frontend\models\User $user
     * @return array
     */
    public function getMutualSubscriptionsTo(User $user, $limit = self::MUTUAL_SUBSCRIPTIONS_LIMIT)
    {
        $thisSub_key = "user:{$this->getId()}:subscriptions";
        $userFollowers_key = "user:{$user->getId()}:followers";
        
        /* @var $redis Connection*/
        $redis = Yii::$app->redis;
        
        $ids = $redis->sinter($thisSub_key, $userFollowers_key);
        return User::find()->select('id, username, nickname')->where(['id' => $ids])->orderBy('username')->limit($limit)->asArray()->all();
    }
    
    /**
     * Check whether current user if following given user
     * @param \frontend\models\User $user
     * @return boolean
     */
    public function isFollowing(User $user)
    {
        /* @var $redis Connection */
        $redis = Yii::$app->redis;
        return (bool) $redis->sismember("user:{$this->getId()}:subscriptions", $user->getId());
    }
    
    /**
     * Delete picture from user profile
     * @return boolean
     */
    public function deletePicture() {
        if ($this->picture) {
            $this->picture = null;
            return $this->save(false, ['picture']);
        }
        return true;        
    }
    
    /**
     * Get data for newsfeed
     * @param integer $limit
     * @return array
     */
    public function getFeed($limit) {
        $order = ['post_created_at' => SORT_DESC];
        return $this->hasMany(Feed::class, ['user_id' => 'id'])->orderBy($order)->limit($limit)->all();
    }
    
    /**
     * Check whether current user likes post with given id
     * @param integer $postId
     * @return boolean
     */
    public function likesPost(int $postId)
    {
        /* @var $redis Connection */
        $redis = Yii::$app->redis;
        return (bool) $redis->sismember("user:{$this->getId()}:likes", $postId);
    }
    
    /**
     * Get users posts
     * @return array
     */
    public function getPosts() {
        $order = ['created_at' => SORT_DESC];
        return $this->hasMany(Post::class, ['user_id' => 'id'])->orderBy($order)->all();
    }
    
    /**
     * @return integer
     */
    public function getPostCount() {
        return $this->hasMany(Post::class, ['user_id' => 'id'])->count();
    }
}
