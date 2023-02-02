<?php

use App\Models\Post;

function PostOwner($post_id, $message = true) {
    $published  = Post::where('user_id', Auth::user()->id)->where('id',$post_id)->first();
    $trashed    = Post::where('user_id', Auth::user()->id)->where('id',$post_id)->withTrashed()->first();

    if($published || $trashed) {
        return true;
    }

    if($message == true) {
        Session::flash('error', 'You are trying to take action with a post doesn\'t belong to you, please try again.');
    }

    return false;
    
}