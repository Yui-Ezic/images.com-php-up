<?php

namespace frontend\models\events;

use yii\base\Event;

/**
 * Description of PostCreatedEvent
 *
 * @author misha
 */
class UserUnfollowedEvent extends Event
{
    /**
     * @var int
     */
    public $user_id;
    
    /**
     * @var int 
     */
    public $author_id;
    
    public function getUserId()
    {
        return $this->user_id;
    }
    
    public function getAuthorId()
    {
        return $this->author_id;
    }
}
