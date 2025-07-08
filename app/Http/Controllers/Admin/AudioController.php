<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Audio;
use App\Models\User;
use App\Models\Post;

class AudioController extends Controller
{
    public function index()
    {
        $audios = Audio::with(['user', 'post'])->latest()->get();
        return view('admin.audio.index', compact('audios'));
    }

    public function create()
    {
        $users = User::all();
        $posts = Post::all();
        return view('admin.audio.create', compact('users', 'posts'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'audio_file' => 'required|mimes:mp3,wav,ogg|max:10240',
            'user_id' => 'nullable|exists:users,id',
            'post_id' => 'nullable|exists:posts,id',
        ]);
        if ($request->hasFile('audio_file')) {
            $file = $request->file('audio_file');
            $filePath = upload_file_common($file, 'uploads/audio/');
            $data['file_path'] = $filePath;
        }
        Audio::create($data);
        return redirect()->route('admin.audio.index')->with('success', 'Audio uploaded successfully.');
    }

    public function edit($id)
    {
        $audio = Audio::findOrFail($id);
        $users = User::all();
        $posts = Post::all();
        return view('admin.audio.edit', compact('audio', 'users', 'posts'));
    }

    public function update(Request $request, $id)
    {
        $audio = Audio::findOrFail($id);
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'audio_file' => 'nullable|mimes:mp3,wav,ogg|max:10240',
            'user_id' => 'nullable|exists:users,id',
            'post_id' => 'nullable|exists:posts,id',
        ]);
        if ($request->hasFile('audio_file')) {
            $file = $request->file('audio_file');
            $filePath = upload_file_common($file, 'uploads/audio/');
            $data['file_path'] = $filePath;
        }
        $audio->update($data);
        return redirect()->route('admin.audio.index')->with('success', 'Audio updated successfully.');
    }

    public function destroy($id)
    {
        $audio = Audio::findOrFail($id);
        $audio->delete();
        return response()->json(['success' => true]);
    }
}
