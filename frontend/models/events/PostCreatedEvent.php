<?php

namespace frontend\models\events;

use yii\base\Event;
use frontend\models\User;
use frontend\models\Post;

/**
 * Description of PostCreatedEvent
 *
 * @author misha
 */
class PostCreatedEvent extends Event
{
    /**
     * @var User 
     */
    public $user;
    
    /**
     * @var Post 
     */
    public $post;
    
    public function getUser(): User
    {
        return $this->user;
    }
    
    public function getPost(): Post
    {
        return $this->post;
    }
}
