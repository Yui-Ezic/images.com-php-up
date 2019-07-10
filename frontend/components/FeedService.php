<?php

namespace frontend\components;

use yii\base\Component;
use frontend\models\User;
use frontend\models\Post;
use frontend\models\Feed;

/**
 * Description of FeedService
 *
 * @author misha
 */
class FeedService extends Component
{
    
    public function addToFeeds(\yii\base\Event $event)
    {
        /* @var $user User */
        $user = $event->getUser();
        
        /* @var $post Post */
        $post = $event->getPost();
        
        $followers = $user->getFollowers();
        foreach ($followers as $follower) {
            $feedItem = new Feed();
            $feedItem->user_id = $follower['id'];
            $feedItem->author_id = $user->id;
            $feedItem->author_name = $user->username;
            $feedItem->author_nickname = $user->getNickname();
            $feedItem->author_picture = $user->getPicture();
            $feedItem->post_id = $post->id;
            $feedItem->post_filename = $post->filename;
            $feedItem->post_description = $post->description;
            $feedItem->post_created_at = $post->created_at;
            $feedItem->save();
        }
    }
}
