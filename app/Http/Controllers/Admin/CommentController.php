<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Comment;
use Yajra\DataTables\Facades\DataTables;

class CommentController extends Controller
{
    public function index(Request $request)
    {
        $dataList = Comment::with(['user', 'post'])->orderBy('id', 'desc');
        $search = $request->search;
        if (!empty($search) && is_string($search)) {
            $dataList->where('content', 'LIKE', '%' . $search . '%');
        }
        $dataList = $dataList->get();

        if ($request->ajax()) {
            return DataTables::of($dataList)
                ->addColumn('user', function ($row) {
                    return $row->user ? $row->user->name : 'N/A';
                })
                ->addColumn('post', function ($row) {
                    return $row->post ? (strlen($row->post->content) > 30 ? substr($row->post->content, 0, 30) . '...' : $row->post->content) : 'N/A';
                })
                ->addColumn('comment', function ($row) {
                    return $row->content ? (strlen($row->content) > 30 ? substr($row->content, 0, 30) . '...'  : $row->content)  : 'N/A';
                })
                ->addColumn('created_at', function ($row) {
                    return $row->created_at ? $row->created_at->format('Y-m-d H:i:s') : 'N/A';
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="' . route('admin.comments.edit', $row->id) . '" class="btn btn-sm btn-primary">Edit</a> ';
                    $btn .= '<a href="#" data-id="' . $row->id . '" class="btn btn-sm btn-danger delete-comment">Delete</a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.comments.index', compact('dataList'));
    }

    public function edit($id)
    {
        $comment = Comment::findOrFail($id);
        return view('admin.comments.edit', compact('comment'));
    }

    public function update(Request $request, $id)
    {
        $comment = Comment::findOrFail($id);
        $data = $request->validate([
            'content' => 'required|string',
        ]);
        $comment->update($data);
        return redirect()->route('admin.comments.index')->with('success', 'Comment updated successfully.');
    }

    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->delete();
        return response()->json(['success' => true]);
    }
}
