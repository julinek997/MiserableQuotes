<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Services\WeatherService;


class PostController extends Controller
{

    protected $weatherService;

    public function __construct(WeatherService $weatherService)
    {
        $this->weatherService = $weatherService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $apiKey = config('services.openweathermap.api_key');
        $city = 'Swansea';
        $weather = $this->weatherService->getCurrentWeather($city);
        $posts = Post::paginate(10);
    
        return view('posts.index', compact('posts', 'weather'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tags = Tag::all();
        return view('posts.create', compact('tags'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'post_body' => 'required|max:255',
            'tags' => 'array', 
            'new_tags' => 'nullable|string', 
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
    
        $post = new Post;
        $post->post_body = $validatedData['post_body'];
        $post->user_id = auth()->id();

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
            $post->image = $imagePath;
        }
        
        $post->save();
    
        if (!empty($validatedData['tags'])) {
            $post->tags()->attach($validatedData['tags']);
        }
    
        if (!empty($validatedData['new_tags'])) {
            $newTags = explode(',', $validatedData['new_tags']);
    
            foreach ($newTags as $tagName) {
                $tagName = Str::slug(trim($tagName)); 
                $tag = Tag::firstOrCreate(['tag_body' => $tagName]);
                $post->tags()->attach($tag->id);
            }
        }
    
        session()->flash('message', 'Post was created.');
        return redirect()->route('posts.index');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $post = Post::findOrFail($id);
        return view('posts.show', ['post'=> $post]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $post = Post::findOrFail($id);
        return view('posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'post_body' => 'required|max:255',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
    
        $post = Post::findOrFail($id);
    
        if (auth()->id() !== $post->user_id) {
            return response()->json(['error' => 'You do not have permission to edit this post.'], 403);
        }
    
        $post->update([
            'post_body' => $validatedData['post_body'],
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }
    
            $imagePath = $request->file('image')->store('images', 'public');
            $post->image = $imagePath;
        }
    
        $post->save();
    
        session()->flash('message', 'Post was updated.');
        return redirect()->route('posts.show', $post->id);
    }
    /**
 * Remove the specified resource from storage.
 */
    public function destroy(string $id)
    {
        $post = Post::findOrFail($id);

        if (auth()->id() !== $post->user_id) {
            return response()->json(['error' => 'You do not have permission to delete this post.'], 403);
        }

        $post->delete();

        return redirect()->route('posts.index')->with('message', 'Post was deleted.');
    }

    public function indexByTag(Tag $tag)
    {
        $posts = $tag->posts()->paginate(10);

        return view('posts.index', ['posts' => $posts]);
    }

}