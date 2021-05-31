<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Post;
use App\Tag;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostController extends Controller
{
    protected $validation = [
        'date' => 'required|date',
        'content' => 'required|string',
        'image' => 'nullable|url'
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // prendo tutti i post
        $posts = Post::all();
        
        return view('admin.posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tags = Tag::all();

        return view('admin.posts.create', compact('tags'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validation = $this->validation;
        $validation['title'] = 'required|string|max:255|unique:posts';
        
        // validation
        $request->validate($validation);

        $data = $request->all();
        
        // Controllo se la checkbox e in caso la setto a 0 o 1
        $data['published'] = !isset($data['published']) ? 0 : 1;

           // Impostazione slug partendo dal titolo
        $data['slug'] = Str::slug($data['title'], '-');

        // Insert
        $newPost = Post::create($data);    
        
         // Aggiorno i tag controllando se sono stati passati
        if( isset($data['tags']) ) {
            $newPost->tags()->attach($data['tags']);
        }

        // Upload dell'immagine 
        if ( isset($data['image']) ) {
            $data['image'] = Storage::disk('public')->put('images', $data['image']);
        }
        return redirect()->route('admin.posts.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        return view('admin.posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        $tags = Tag::all();

        return view('admin.posts.edit', compact('post', 'tags'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        $validation = $this->validation;
        $validation['title'] = 'required|string|max:255|unique:posts,title,' . $post->id;

        // validation
        $request->validate($validation);

        $data = $request->all();
        
        // Controllo se la checkbox e in caso la setto a 0 o 1
        $data['published'] = !isset($data['published']) ? 0 : 1;

        // Impostazione slug partendo dal titolo
        $data['slug'] = Str::slug($data['title'], '-');

        
        // Upload dell'immagine 
        if ( isset($data['image']) ) {
            $data['image'] = Storage::disk('public')->put('images', $data['image']);
        }


        $post->update($data);

        // Aggiorno i tag controllando se sono stati passati
        if( !isset($data['tags']) ) {
            $data['tags'] = [];
        }
        $post->tags()->sync($data['tags']);

        
        return redirect()->route('admin.posts.show', $post);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $post->delete();

        return redirect()->route('admin.posts.index')->with('message', 'Il post è stato eliminato!');
    }
}
