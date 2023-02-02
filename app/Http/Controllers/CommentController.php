<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Post;
use Auth, Validator, Session;

class CommentController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $post_id)
    {
        $post = Post::find($post_id);

        if(!$post) {
            Session::flash('error', 'Sorry but this post doesn\'t exist.');

            return redirect()->back();
        }

        if(Auth::check()) {
            $values = ['comment' =>'required|max:255'];
        }
        else {
            $values = [
                'comment'   =>'required|max:255',
                'name'      =>'required|max:255',
                'email'     =>'required|email',
            ];
        }

        $validator = Validator::make($request->all(), $values);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $comment            = new Comment;
        $comment->comment   = $request->input('comment');

        if(Auth::check() && $post->user_id == Auth::user()->id) {
            $comment->approved  = 1;
        }
        else {
            $comment->approved  = 0;
        }
        

        if(Auth::check()) {
            $comment->user_id  = Auth::user()->id;
        }
        else {
            $comment->name  = $request->input('name');
            $comment->email = $request->input('email');
        }

        $comment->post_id = $post_id;

        $comment->save();

        Session::flash('success', 'Comment was successfully sent and will appear in post comments once the post author approve it.');

        return redirect()->back();
    }

    public function approve($comment_id)
    {
        $comment = Comment::find($comment_id);

        if(!$comment) {
            Session::flash('error', 'Sorry but this comment doesn\'t exist.');

            return redirect()->back();
        }

        $comment->approved = 1;
        $comment->save();

        Session::flash('success', 'Comment was successfully approved.');

        return redirect()->back();
    }
}