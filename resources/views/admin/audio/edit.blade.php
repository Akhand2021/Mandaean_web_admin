@extends('layouts.app')
@section('title', 'Edit Upload')
@section('pagetitle', 'Edit Upload')
@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">Edit Audio</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ url('audio') }}" title="Back">
                            <label><- Back</label>
                        </a>
                    </li>
                </ol>
            </nav>
        </div>
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.audio.update', $audio->id) }}"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="form-group mb-3">
                            <label for="title">Title</label>
                            <input type="text" name="title" id="title" class="form-control"
                                value="{{ $audio->title }}" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="description">Description</label>
                            <textarea name="description" id="description" class="form-control">{{ $audio->description }}</textarea>
                        </div>
                        <div class="form-group mb-3">
                            <label for="audio_file">Audio File (leave blank to keep current)</label>
                            <input type="file" name="audio_file" id="audio_file" class="form-control" accept="audio/*">
                            @if ($audio->file_path)
                                <audio controls class="mt-2">
                                    <source src="{{ asset($audio->file_path) }}" type="audio/mpeg">
                                    Your browser does not support the audio element.
                                </audio>
                            @endif
                        </div>
                        <div class="form-group mb-3">
                            <label for="user_id">Assign to User (optional)</label>
                            <select name="user_id" id="user_id" class="form-control">
                                <option value="">-- None --</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" @if ($audio->user_id == $user->id) selected @endif>
                                        {{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="post_id">Assign to Post (optional)</label>
                            <select name="post_id" id="post_id" class="form-control">
                                <option value="">-- None --</option>
                                @foreach ($posts as $post)
                                    <option value="{{ $post->id }}" @if ($audio->post_id == $post->id) selected @endif>
                                        {{ $post->content }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="{{ route('admin.audio.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
