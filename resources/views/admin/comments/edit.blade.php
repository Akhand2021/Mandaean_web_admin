@extends('layouts.app')
@section('title','Post')
@section('pagetitle','Post')

@section('content')
<div class="content-wrapper">
    <div class="page-header">
      <h3 class="page-title">Edit Comments</h3>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="{{url('mandanism')}}" title="Back">
              <label><- Back</label>
            </a>
          </li>
        </ol>
      </nav>
    </div>
    <div class="col-12 grid-margin stretch-card">
          <div class="card">
            <div class="card-body">
                    <form method="POST" action="{{ route('admin.comments.update', $comment->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="form-group mb-3">
                            <label for="content">Content</label>
                            <textarea name="content" id="content" class="form-control" rows="4" required>{{ old('content', $comment->content) }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="{{ route('admin.comments.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
