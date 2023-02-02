<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\Post;
use App\Models\Comment;
use File;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current-password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $posts = Post::where('user_id', $user->id)->get();

        if(count($posts) > 0) {
            foreach($posts as $post) {
                $comments = Comment::where('post_id', $post->id)->get();

                if(count($comments) > 0) {
                    foreach($comments as $comment) {
                        $comment->delete();
                    }
                }

                if($post->image) {
                    $image_path = public_path("images/posts/uploads/") .$post->image;
                    $thumb_path = public_path("images/posts/thumbnails/") .$post->image;

                    if(File::exists($image_path)) {
                        File::delete($image_path);
                    }
                    
                    if(File::exists($thumb_path)) {
                        File::delete($thumb_path);
                    }
                }
                
                $post->delete();
            }
        }

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
