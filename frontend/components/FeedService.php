<?php

namespace frontend\components;

use yii\base\Component;
use frontend\models\User;
use frontend\models\Post;
use frontend\models\Feed;
use yii\base\Event;

class FeedService extends Component
{
    
    public function addToFeeds(Event $event)
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

    public function unfollowUser(Event $event)
    {
        $user_id = $event->getUserId();
        $author_id = $event->getAuthorId();
        
        Feed::deleteAll(['user_id' => $user_id, 'author_id' => $author_id]);    
    }
    
    public function followUser(Event $event)
    {
        $user_id = $event->getUserId();
        /* @var $author User */
        $author = $event->getAuthor();
        $posts = $author->getPosts();
        
        foreach($posts as $post){
            /* @var $post Post */
            $feedItem = new Feed();
            $feedItem->user_id = $user_id;
            $feedItem->author_id = $author->getId();
            $feedItem->author_name = $author->username;
            $feedItem->author_nickname = $author->getNickname();
            $feedItem->author_picture = $author->getPicture();
            $feedItem->post_id = $post->id;
            $feedItem->post_filename = $post->filename;
            $feedItem->post_description = $post->description;
            $feedItem->post_created_at = $post->created_at;
            $feedItem->save();
        }
    }
}
