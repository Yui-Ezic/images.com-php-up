<?php

namespace frontend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use frontend\models\events\CommentCreatedEvent;
use frontend\models\events\CommentDeletedEvent;

/**
 * This is the model class for table "comment".
 *
 * @property int $id
 * @property int $author_id
 * @property int $post_id
 * @property string $text
 * @property int $status
 * @property int $created_at
 * @property int $edited_at
 * @property int $is_avaliable
 */
class Comment extends \yii\db\ActiveRecord
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_EDITED = 2;
    
    const EVENT_COMMENT_CREATED = 'comment_created';
    const EVENT_COMMENT_DELETED = 'comment deleted';
    
    public function init() {
        parent::init();
        $this->on(self::EVENT_COMMENT_CREATED, [Post::class, 'addCommentToRedis']);
        $this->on(self::EVENT_COMMENT_DELETED, [Post::class, 'deleteCommentFromRedis']);
    }
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'comment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['author_id', 'post_id', 'status', 'created_at', 'edited_at', 'is_avaliable'], 'integer'],
            [['text'], 'string'],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['is_avaliable', 'default','value' => self::STATUS_ACTIVE],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'author_id' => 'Author ID',
            'post_id' => 'Post ID',
            'text' => 'Text',
            'status' => 'Status',
            'created_at' => 'Created At',
            'edited_at' => 'Edited At',
        ];
    }
    
    /**
     * {@inheritdoc}
     */
    public function behaviors(){
        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'edited_at'],
                    ActiveRecord::EVENT_AFTER_UPDATE => ['edited_at']
                ],
            ],
        ];
    }
    
    public function getId()
    {
        return $this->getPrimaryKey();
    }

        /**
     * @return User | null
     */
    public function getUser() {
        return Yii::$app->user->identityClass::findIdentity($this->author_id);
    }
    
    /**
     * Find comment by id
     * 
     * @param integer $id
     * @return static || null
     */
    public static function getById($id)
    {
        return static::findOne(['id' => $id]);
    }
    
    /**
     * Delete comment
     * 
     * @return bool whether the saving succeeded (i.e. no validation errors occurred)
     */
    public function delete()
    {
        $event = new CommentCreatedEvent();
        $event->user_id = $this->author_id;
        $event->post_id = $this->post_id;
        $event->comment_id = $this->getId();
        
        $this->trigger(self::EVENT_COMMENT_DELETED, $event);
        
        $this->is_avaliable = self::STATUS_DELETED;
        return $this->save();
    }
    
    /**
     * Refresh deleted comment
     * 
     * @return bool
     */
    public function refreshComment() {
        $event = new CommentCreatedEvent();
        $event->user_id = $this->author_id;
        $event->post_id = $this->post_id;
        $event->comment_id = $this->getId();
        
        $this->trigger(self::EVENT_COMMENT_CREATED, $event);
        
        $this->is_avaliable = self::STATUS_ACTIVE;
        return $this->save();
    }
    
    /**
     * Refresh deleted comment by id
     * 
     * @param int $id
     * @return bool
     */
    public static function refreshById($id)
    {
        return self::getById($id)->refreshComment();
    }
}
