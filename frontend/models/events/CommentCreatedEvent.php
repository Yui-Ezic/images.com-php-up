<?php

namespace frontend\models\events;

use yii\base\Event;


/**
 * Description of PostCreatedEvent
 *
 * @author misha
 */
class CommentCreatedEvent extends Event
{
    /**
     * @var User 
     */
    public $user_id;
    
    /**
     * @var Post 
     */
    public $post_id;
    
    /**
     *
     * @var Comment 
     */
    public $comment_id;


    public function getUserId()
    {
        return $this->user_id;
    }
    
    public function getPostId()
    {
        return $this->post_id;
    }
    
    public function getCommentId()
    {
        return $this->comment_id;
    }
}