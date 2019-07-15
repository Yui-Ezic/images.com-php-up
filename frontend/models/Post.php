<?php

namespace frontend\models;

use Yii;
use frontend\models\User;
use yii\redis\Connection;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use frontend\models\Comment;

/**
 * This is the model class for table "post".
 *
 * @property int $id
 * @property int $user_id
 * @property string $filename
 * @property string $description
 * @property int $created_at
 */
class Post extends ActiveRecord
{
    
    const COMMENTS_LIMIT = 10;
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'post';
    }
    
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
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'filename' => 'Filename',
            'description' => 'Description',
            'created_at' => 'Created At',
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
    
    public function getId(){
        return $this->id;
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
    
    public function getComments($limit = self::COMMENTS_LIMIT)
    {
        $order = ['comment.created_at' => SORT_DESC];
        return $this->hasMany(Comment::class, ['post_id' => 'id'])->orderBy($order)->limit($limit)->all();
    }
    
    public function getAvaliableComments($limit = self::COMMENTS_LIMIT)
    {
        $order = ['comment.created_at' => SORT_DESC];
        return $this->hasMany(Comment::class, ['post_id' => 'id'])->orderBy($order)->limit($limit)->where(['is_avaliable' => Comment::STATUS_ACTIVE])->all();
    }
    
    public function countComments()
    {
        return $this->hasMany(Comment::class, ['post_id' => 'id'])->where(['is_avaliable' => Comment::STATUS_ACTIVE])->count();
    }
}
