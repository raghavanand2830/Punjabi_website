<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Topic;
use Illuminate\Support\Facades\Hash;

class  TopicController extends Controller
{
    public function index()
    {
        $topics = Topic::with(['category','subcategory'])->get();
       
        return view('admin.topics.index', compact('topics'));
    }

    public function create()
    {
        $categories = Category::all();
        $subcategories = Subcategory::all();
        return view('admin.topics.create', compact('categories','subcategories'));
    }

    public function store(Request $request)
    {
    
        $request->validate([
            'name' => 'required|string|max:255',
            'subcategory_id' => 'required|exists:subcategories,id',
            'category_id' => 'required|exists:categories,id',
            'question_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'question_audio' => 'nullable|file|mimes:mp3,wav|max:2048',
            'answer_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'answer_audio' => 'nullable|file|mimes:mp3,wav|max:2048',
        ]);
       
        $question_imageName = null;
        $question_audioName = null;

        $answer_imageName = null;
        $answer_audioName = null;

        if ($request->hasFile('question_image')) {
            $question_imageName = time() . '.' . $request->question_image->extension();
            $request->question_image->move(public_path('images'), $question_imageName);
        }

        if ($request->hasFile('answer_image')) {
            $answer_imageName = time() . '.' . $request->answer_image->extension();
            $request->answer_image->move(public_path('images'), $answer_imageName);
        }

        if ($request->hasFile('question_audio')) {
            $question_audioName = time() . '.' . $request->question_audio->extension();
            $request->question_audio->move(public_path('audios'), $question_audioName);
        }

        if ($request->hasFile('answer_audio')) {
            $answer_audioName = time() . '.' . $request->answer_audio->extension();
            $request->answer_audio->move(public_path('audios'), $answer_audioName);
        }

        Topic::create([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'subcategory_id' => $request->subcategory_id,
            'question_image' => $question_imageName,
            'question_audio' => $question_audioName,
            'answer_image' => $answer_imageName,
            'answer_audio' => $answer_audioName,
        ]);
       
        return redirect()->route('topics.index')
            ->with('alert', 'topic created successfully.');
    }

   

    public function edit(Topic $topic)
    {
       
        $categories = Category::all();
        $subcategories = Subcategory::all();
        return view('admin.topics.edit', compact('categories','subcategories','topic'));
    }

    public function update(Request $request, Topic $topic)
    {

        
        $request->validate([
            'name' => 'required|string|max:255',
            'subcategory_id' => 'required|exists:subcategories,id',
            'category_id' => 'required|exists:categories,id',
            'question_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'question_audio' => 'nullable|file|mimes:mp3,wav|max:2048',
            'answer_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'answer_audio' => 'nullable|file|mimes:mp3,wav|max:2048',
        ]);
       
      

        $question_imageName = $topic->question_image;
        $question_audioName = $topic->question_audio;

        $answer_imageName = $topic->answer_image;
        $answer_audioName = $topic->answer_audio;

        if ($request->hasFile('question_image')) {
            $question_imageName = time() . '.' . $request->question_image->extension();
            $request->question_image->move(public_path('images'), $question_imageName);
        }

        if ($request->hasFile('answer_image')) {
            $answer_imageName = time() . '.' . $request->answer_image->extension();
            $request->answer_image->move(public_path('images'), $answer_imageName);
        }

        if ($request->hasFile('question_audio')) {
            $question_audioName = time() . '.' . $request->question_audio->extension();
            $request->question_audio->move(public_path('audios'), $question_audioName);
        }

        if ($request->hasFile('answer_audio')) {
            $answer_audioName = time() . '.' . $request->answer_audio->extension();
            $request->answer_audio->move(public_path('audios'), $answer_audioName);
        }

       $topic->update([
            'name' => $request->name ? $request->name:$topic->name ,
            'subcategory_id' => $request->subcategory_id ? $request->subcategory_id:$topic->subcategory_id ,
            'category_id' => $request->category_id ? $request->category_id:$topic->category_id ,
            'question_image' => $question_imageName,
            'question_audio' => $question_audioName,
            'answer_image' => $answer_imageName,
            'answer_audio' => $answer_audioName,
        ]);

        return redirect()->route('topics.index')
            ->with('success', 'topic updated successfully.');
    }

    public function destroy(Topic $topic)
    {
        if ($topic->question_image) {
            // Delete the image file from the 'images' folder
            $question_imagePath = public_path('images/' . $topic->question_image);
            if (file_exists($question_imagePath)) {
                unlink($question_imagePath); // Delete the image file
            }
        }

        if ($topic->answer_image) {
            // Delete the image file from the 'images' folder
            $answer_imagePath = public_path('images/' . $topic->answer_image);
            if (file_exists($answer_imagePath)) {
                unlink($answer_imagePath); // Delete the image file
            }
        }

        if ($topic->question_audio) {
            // Delete the image file from the 'images' folder
            $question_audioPath = public_path('images/' . $topic->question_audio);
            if (file_exists($question_audioPath)) {
                unlink($question_audioPath); // Delete the image file
            }
        }
        if ($topic->answer_audio) {
            // Delete the image file from the 'images' folder
            $answer_audioPath = public_path('images/' . $topic->answer_audio);
            if (file_exists($answer_audioPath)) {
                unlink($answer_audioPath); // Delete the image file
            }
        }
        $topic->delete();
        return redirect()->route('topics.index')
            ->with('success', 'topic deleted successfully.');
    }
}