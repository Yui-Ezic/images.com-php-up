<?php

namespace frontend\models\events;

use yii\base\Event;
use frontend\models\User;

/**
 * Description of PostCreatedEvent
 *
 * @author misha
 */
class UserNewFollowedEvent extends Event
{
    /**
     * @var int
     */
    public $user_id;
    
    /*
     * @var int
     */
    public $author_id;
    
    public function getUserId()
    {
        return $this->user_id;
    }
    
    public function getAuthor(): User
    {
        return User::findIdentity($this->author_id);
    }
}
