<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Comment;
use Yajra\DataTables\Facades\DataTables;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $dataList = Post::with('user')->orderBy('id', 'desc');
        $search = $request->input('search', '');
        if (!empty($search) && is_string($search)) {
            $dataList->where('content', 'LIKE', '%' . $search . '%');
        }
        if ($request->ajax()) {
            $dataList = $dataList->get();
            return DataTables::of($dataList)
                ->addColumn('userName', function ($row) {
                    return $row->user ? $row->user->name : 'N/A';
                })
                ->addColumn('content', function ($row) {
                    return strlen($row->content) > 30 ? substr($row->content, 0, 30) . '...' : $row->content;
                })
                ->addColumn('created_at', function ($row) {
                    return $row->created_at ? $row->created_at->format('d-M-Y H:i:s') : 'N/A';
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="' . route('admin.posts.edit', $row->id) . '" class="btn btn-sm btn-primary">Edit</a> ';
                    $btn .= '<a href="#" data-id="' . $row->id . '" class="btn btn-sm btn-danger delete-post">Delete</a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.posts.index');
    }

    public function edit($id)
    {
        $post = Post::findOrFail($id);
        return view('admin.posts.edit', compact('post'));
    }

    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);
        $data = $request->validate([
            'content' => 'required|string',
        ]);
        $post->update($data);
        return redirect()->route('admin.posts.index')->with('success', 'Post updated successfully.');
    }

    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();
        return response()->json(['success' => true]);
    }

    public function comments($postId, Request $request)
    {
        $post = Post::findOrFail($postId);
        $dataList = Comment::with(['user'])
            ->where('post_id', $postId)
            ->orderBy('id', 'desc');
        $search = $request->search;
        if ($search) {
            $dataList->where('content', 'LIKE', '%' . $search . '%');
        }
        $dataList = $dataList->get();
        if ($request->ajax()) {
            return DataTables::of($dataList)
                ->addColumn('user', function ($row) {
                    return $row->user ? $row->user->name : 'N/A';
                })
                ->addColumn('action', function ($row) use ($postId) {
                    $btn = '<a href="' . route('admin.comments.edit', $row->id) . '" class="btn btn-sm btn-primary">Edit</a> ';
                    $btn .= '<a href="#" data-id="' . $row->id . '" class="btn btn-sm btn-danger delete-comment">Delete</a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.comments.index', compact('dataList', 'post'));
    }
}
