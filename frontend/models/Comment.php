<?php

namespace frontend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

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
 */
class Comment extends \yii\db\ActiveRecord
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_EDITED = 2;
    
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
            [['author_id', 'post_id', 'status', 'created_at', 'edited_at'], 'integer'],
            [['text'], 'string'],
            ['status', 'default', 'value' => self::STATUS_ACTIVE]
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
    
    public function delete()
    {
        $this->status = self::STATUS_DELETED;
        return $this->save();
    }
}
