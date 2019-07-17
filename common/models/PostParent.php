<?php
namespace common\models;

use yii\db\ActiveRecord;
use yii\redis\Connection;
use Yii;

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
class PostParent extends ActiveRecord
{
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
    
    public static function DeletePostById($id)
    {
        $post = self::findOne($id);
        if($post) {
            return $post->deletePost();           
        }
        
        self::deleteFromRedis($id);
    }
    
    public function deletePost()
    {
        /* @var $redis Connection */
        $redis = Yii::$app->redis;
        
        self::deleteFromRedis($this->id);
        
        return $this->delete();
    }
    
    private static function deleteFromRedis($id) {
        /* @var $redis Connection */
        $redis = Yii::$app->redis;
        
        $keys = [   
            "post:{$id}:complaints",
            "post:{$id}:likes",
            "post:{$id}:comments",
        ];
            
        foreach($keys as $key){
            $redis->del($key);
        }
    }
}
