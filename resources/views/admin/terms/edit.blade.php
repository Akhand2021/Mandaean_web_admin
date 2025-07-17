@extends('layouts.app')
@section('title', 'Terms & Conditions')
@section('pagetitle', 'Terms & Conditions')
@section('sort_name', $data['sort_name'])
@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">Edit Terms & Conditions</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ url('terms-and-conditions') }}" title="Back">
                            <label><- Back</label>
                        </a>
                    </li>
                </ol>
            </nav>
        </div>
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <form class="forms-sample" method="POST"
                        action="{{ route('terms-and-conditions.update', $data['term']->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="form-group col-sm-12">
                            <label for="title">Heading</label>
                            <input type="text" class="form-control" id="title" name="title"
                                placeholder="Enter section heading (optional)"
                                value="{{ old('title', $data['term']->title) }}">
                            @error('title')
                                <p style="color: red">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group col-sm-12">
                            <label for="content">T&C Content</label>
                            <textarea class="form-control" id="content" name="content" placeholder="Write your terms and conditions here...">{{ old('content', $data['term']->content) }}</textarea>
                            @error('content')
                                <p style="color: red">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group col-sm-12">
                            <label for="is_active">Status</label>
                            <select class="form-control" id="is_active" name="is_active">
                                <option value="1"
                                    {{ old('is_active', $data['term']->is_active) == '1' ? 'selected' : '' }}>Active
                                </option>
                                <option value="0"
                                    {{ old('is_active', $data['term']->is_active) == '0' ? 'selected' : '' }}>Inactive
                                </option>
                            </select>
                            @error('is_active')
                                <p style="color: red">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-gradient-primary me-2">Update</button>
                        <a href="{{ url('terms-and-conditions') }}" class="btn btn-light">Cancel</a>
                    </form>

                    <form action="{{ route('terms-and-conditions.destroy', $data['term']->id) }}" method="POST"
                        style="margin-top: 1rem;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger"
                            onclick="return confirm('Are you sure you want to delete this entry?')">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>

    <script type="text/javascript">
        $('#content').summernote({
            height: 300
        });
    </script>
@endsection
