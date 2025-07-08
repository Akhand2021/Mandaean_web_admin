@extends('layouts.app')
@section('title', 'Audio Upload')
@section('pagetitle', 'Audio Upload')
@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">Audio List</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ url('audio/create') }}" title="Add">
                            <label class="badge badge-info">+ Add</label>
                        </a>
                    </li>
                </ol>
            </nav>
        </div>
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>File</th>
                                <th>User</th>
                                <th>Post</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($audios as $audio)
                                <tr>
                                    <td>{{ $audio->id }}</td>
                                    <td>{{ $audio->title }}</td>
                                    <td>{{ $audio->description }}</td>
                                    <td>
                                        <audio controls>
                                            <source src="{{ asset($audio->file_path) }}" type="audio/mpeg">
                                            Your browser does not support the audio element.
                                        </audio>
                                    </td>
                                    <td>{{ $audio->user ? $audio->user->name : '-' }}</td>
                                    <td>{{ $audio->post ? $audio->post->content : '-' }}</td>
                                    <td>
                                        <a href="{{ route('admin.audio.edit', $audio->id) }}"
                                            class="btn btn-sm btn-primary">Edit</a>
                                        <form action="{{ route('admin.audio.destroy', $audio->id) }}" method="POST"
                                            style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Delete this audio?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection
