<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use App\Models\Post;
use App\Models\Comment;
use Session, Image, Auth;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'     =>'required|unique:posts|max:255',
            'excerpt'   => 'required',
            'content'   => 'required',
            'slug'      => 'required|alpha_dash|min:5|max:255|unique:posts',
            'image'     => 'image|mimes:jpg,png,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->route('posts.create')->withErrors($validator)->withInput();
        }

        $post           = new Post;
        $post->title    = $request->input('title');
        $post->excerpt  = $request->input('excerpt');
        $post->slug     = empty($request->input('slug')) ? Str::slug($request->input('slug'), '-') : $request->input('slug');
		$post->content  = $request->input('content');
        $post->user_id  = Auth::user()->id;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $input['image'] = time().'.'.$image->getClientOriginalExtension();
            
            $destinationPath = public_path('/uploads/images/posts/thumbnails');

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 666, true);
            }

            $imgFile = Image::make($image->getRealPath());
            $imgFile->resize(300, 300, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$input['image']);

            $destinationPath = public_path('/uploads/images/posts/uploads');

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 666, true);
            }

            $image->move($destinationPath, $input['image']);

            $post->image = $input['image'];
        }

        $post->save();

        Session::flash('success', 'Post was successfully saved.');

        return redirect()->route('posts.view', $post->slug);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug, $type = null)
    {
        if($type == 'trash') {
            $post = Post::with(['comments' => function ($q) {
                                                $q->where('approved', 1);
                                            }])->withTrashed()->where('slug', $slug)->first();
        }
        else {
            $post = Post::with(['comments' => function ($q) {
                                                $q->where('approved', 1);
                                            }])->where('slug', $slug)->first();
        }

        $pending_approve = Comment::where('post_id', $post->id)->where('approved', 0)->get();

        return view('posts.show')->with('post', $post)->with('type', $type)->with('pending_approve', $pending_approve);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('posts.edit', ['post' => Post::find($id)]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title'     => [
                        'required',
                        'max:255',
                        Rule::unique('posts')->ignore($id),
            ],
            'excerpt'   =>'required',
            'content'   =>'required',
            'slug'      => [
                        'required',
                        'alpha_dash',
                        'min:5',
                        'max:255',
                        Rule::unique('posts')->ignore($id),
            ],
            'image'     => 'image|mimes:jpg,png,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->route('posts.create')->withErrors($validator)->withInput();
        }

        $post           = Post::find($id);
        $post->title    = $request->input('title');
        $post->excerpt  = $request->input('excerpt');
        $post->slug     = empty($request->input('slug')) ? Str::slug($request->input('slug'), '-') : $request->input('slug');
		$post->content  = $request->input('content');

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $input['image'] = time().'.'.$image->getClientOriginalExtension();
            
            $destinationPath = public_path('/uploads/images/posts/thumbnails');

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 666, true);
            }

            $imgFile = Image::make($image->getRealPath());
            $imgFile->resize(300, 300, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$input['image']);

            $destinationPath = public_path('/uploads/images/posts/uploads');

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 666, true);
            }

            $image->move($destinationPath, $input['image']);

            $post->image = $input['image'];
        }

        $post->save();

        Session::flash('success', 'Post was successfully updated.');

        return redirect()->route('posts.view', $post->slug);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(!PostOwner($id, false)) {
            return response()->json(['status'=> 'danger', 'message' => 'You are trying to take action with a post doesn\'t belong to you, please try again.']);
        }

        Post::where('user_id', Auth::user()->id)->where('id',$id)->delete();
    
        Session::flash('success', 'Post was successfully moved to trask, it can be restored later.');

        return response()->json(['status'=> 'success', 'message' => 'Post was successfully moved to trask, it can be restored later.']);
    }

    public function remove($id)
    {
        $post = Post::findOrFail($id);

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
    
        return response()->json(['success'=>'Post was permanently removed from trash!']);
    }

    public function trash()
    {
        return view('posts.trash', ['posts' => Post::onlyTrashed()->paginate(6)]);
    }

    /**
     *  Restore post data
     * 
     * @param Post $post
     * 
     * @return \Illuminate\Http\Response
     */
    public function restore($id) 
    {
        if(!PostOwner($id)) {
            return redirect()->back();
        }

        Post::where('id', $id)->withTrashed()->restore();

        return redirect()->route('dashboard')->withSuccess(__('Post successfully restored.'));
    }

    /**
     * Restore all archived post
     * 
     * @param Post $post
     * 
     * @return \Illuminate\Http\Response
     */
    public function restoreAll() 
    {
        Post::onlyTrashed()->restore();

        //Session::flash('success', 'Post was successfully moved to trask, it can be restored later.');

        return redirect()->route('dashboard')->withSuccess(__('All posts successfully restored.'));
    }
}