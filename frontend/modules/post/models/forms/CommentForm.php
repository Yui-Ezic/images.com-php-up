<?php
namespace frontend\modules\post\models\forms;

use yii\base\Model;
use frontend\models\User;
use frontend\models\Comment;
use frontend\models\events\CommentCreatedEvent;

class CommentForm extends Model
{
    const MIN_COMMENT_LENGTH = 6;
    const MAX_COMMENT_LENGHT = 250;
    
    public $text;
    private $user;
    private $post_id;
    
    public function rules() {
        return [
            [['text'], 'string',
                'min' => self::MIN_COMMENT_LENGTH,
                'max' => self::MAX_COMMENT_LENGHT],
        ];
    }
    
    public function __construct(User $user = null, $post_id = null)
    {
        $this->user = $user;
        $this->post_id = $post_id;
    }
    
    public function save()
    {
        if ($this->validate()) {      
            $comment = new Comment();
            $comment->author_id = $this->user->getId();
            $comment->post_id = $this->post_id;
            $comment->text = $this->text;
            if ($comment->save()) {
                $event = new CommentCreatedEvent();
                $event->user_id = $this->user->getId();
                $event->post_id = $this->post_id;
                $event->comment_id = $comment->getId();
                
                $comment->trigger($comment::EVENT_COMMENT_CREATED, $event);
                return true;
            }
        }
        return false;
    }
    
}
