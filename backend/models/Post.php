<?php

namespace backend\models;

use Yii;
use common\models\PostParent;

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
    public function findComplaints()
    {
        return Post::find()->where('complaints > 0')->orderBy('complaints DESC');
    }
    
    /**
     * @return string
     */
    public function getImage()
    {
        return Yii::$app->storage->getFile($this->filename);
    }

    /**
     * Approve post (delete complaints) if it looks ok
     * @return boolean
     */
    public function approve()
    {
        /* @var $redis Connection */
        $redis = Yii::$app->redis;
        $key = "post:{$this->id}:complaints";
        $redis->del($key);
        
        $this->complaints = 0;
        return $this->save(false, ['complaints']);
    }
}
