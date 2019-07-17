<?php

namespace frontend\models;

use Yii;
use frontend\models\User;
use yii\redis\Connection;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use frontend\models\Comment;
use common\models\PostParent;
use frontend\models\events\CommentCreatedEvent;

/**
 * This is the model class for table "post".
 *
 * @property int $id
 * @property int $user_id
 * @property string $filename
 * @property string $description
 * @property int $created_at
 * @property int $complaints
 */
class Post extends PostParent
{
    
    const COMMENTS_LIMIT = 10;
    
    /**
     * {@inheritdoc}
     */
    public function behaviors(){
        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                ],
            ],
        ];
    }

    
    /**
     * Find post by id
     * 
     * @param integer $id
     * @return static || null
     */
    public static function getById($id)
    {
        return static::findOne(['id' => $id]);
    }
    
    public function getImage() {
        return Yii::$app->storage->getFile($this->filename);        
    }
    
    /**
     * Get author of the post
     * @return User|null
     */
    public function getUser(){
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
    
    public function getId(){
        return $this->id;
    }
    
    /** -------------------------- LIKES SECTION --------------------------- **/
    
    /**
     * Like current postby given user
     * @param User $user
     */
    public function like(User $user)
    {
        /* @var $redis Connection*/
        $redis = Yii::$app->redis;
        $redis->sadd("post:{$this->getId()}:likes", $user->getId());
        $redis->sadd("user:{$user->getId()}:likes", $this->getId());
    }
    
    /**
     * return mixed
     */
    public function countLikes()
    {
        /* @var $redis Connection*/
        $redis = Yii::$app->redis;
        return $redis->scard("post:{$this->getId()}:likes");
    }
    
    /**
     * Unlike current postby given user
     * @param User $user
     */
    public function unlike(User $user)
    {
        /* @var $redis Connection*/
        $redis = Yii::$app->redis;
        $redis->srem("post:{$this->getId()}:likes", $user->getId());
        $redis->srem("user:{$user->getId()}:likes", $this->getId());
    }
    
    public function isLikedBy(User $user)
    {
        /* @var $redis Connection*/
        $redis = Yii::$app->redis;
        return $redis->sismember("post:{$this->getId()}:likes", $user->getId());
    }
    
    /** ------------------------- COMMENTS SECTION ------------------------- **/
    
    /**
     * Add comments to user and to post in Redis. 
     * @param CommentCreatedEvent $event
     */
    public function addCommentToRedis(CommentCreatedEvent $event)
    {
        /* @var $redis Connection*/
        $redis = Yii::$app->redis;
        
        $user_id = $event->getUserId();
        $post_id = $event->getPostId();
        $comment_id = $event->getCommentId();
        
        $redis->sadd("post:{$post_id}:comments", $comment_id);
        $redis->sadd("user:{$user_id}:comments", $comment_id);
    }
    
    /**
     * Add comments to user and to post in Redis. 
     * @param CommentDeletedEvent $event
     */
    public function deleteCommentFromRedis(CommentCreatedEvent $event)
    {
        /* @var $redis Connection*/
        $redis = Yii::$app->redis;
        
        $user_id = $event->getUserId();
        $post_id = $event->getPostId();
        $comment_id = $event->getCommentId();
        
        $redis->srem("post:{$post_id}:comments", $comment_id);
        $redis->srem("user:{$user_id}:comments", $comment_id);
    }
    
    /**
     * Return comments ids
     * @return int[]
     */
    private function getAvaliableCommentsIds()
    {
        /* @var $redis Connection*/
        $redis = Yii::$app->redis;
        return $redis->smembers("post:{$this->getId()}:comments");
    }
    
    /**
     * Get list of all comments (include deleted, unavaliable) of post
     * @param int|false $limit
     * @return array
     */
    public function getComments($limit = self::COMMENTS_LIMIT)
    {
        $order = ['comment.created_at' => SORT_DESC];
        $query = $this->hasMany(Comment::class, ['post_id' => 'id'])->orderBy($order);
        
        if($limit)
        {
            $query->limit($limit);
        }
        
        return $query->all();
    }
    
    /**
     * Get list of avaliable comments of pos
     * @param int|false $limit
     * @return array
     */
    public function getAvaliableComments($limit = self::COMMENTS_LIMIT)
    {
        $Ids = $this->getAvaliableCommentsIds();
        $order = ['comment.created_at' => SORT_DESC];
        $query = $this->hasMany(Comment::class, ['post_id' => 'id'])->orderBy($order)->where(['is_avaliable' => Comment::STATUS_ACTIVE, 'id' => $Ids]);
        
        if($limit)
        {
            $query->limit($limit);
        }
        
        return $query->all();
    }
    
    /**
     * @return mixed
     */
    public function countComments()
    {
        /* @var $redis Connection*/
        $redis = Yii::$app->redis;
        return $redis->scard("post:{$this->getId()}:comments");
    }
    
    /**
     * @return mixed
     */
    public static function countCommentsByPostId($id)
    {
        /* @var $redis Connection*/
        $redis = Yii::$app->redis;
        return $redis->scard("post:{$id}:comments");
    }
    
    /** ------------------------ COMPLAINS SECTION ------------------------- **/
    
    /**
     * Add complaint to post from given user
     * @param \frontend\models\User $user
     * @return boolean
     */
    public function complain(User $user)
    {
        /* @var $redis Connection */
        $redis = Yii::$app->redis;
        $key = "post:{$this->getId()}:complaints";
        
        if (!$redis->sismember($key, $user->getId())) {
            $redis->sadd($key, $user->getId());        
            $this->complaints++;
            return $this->save(false, ['complaints']);
        }
    }
    
    /**
     * @param \frontend\models\User $user
     */
    public function isReported(User $user)
    {
        /* @var $redis Connection */
        $redis = Yii::$app->redis;
        return $redis->sismember("post:{$this->id}:complaints", $user->getId());
    }
}
