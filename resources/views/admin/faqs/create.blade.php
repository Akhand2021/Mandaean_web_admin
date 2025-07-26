@extends('layouts.app')
@section('title', 'Color')
@section('pagetitle', 'Color')
@section('sort_name', $data['sort_name'])
@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">Create Faq</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ url('faqs') }}" title="Back">
                            <label><- Back</label>
                        </a>
                    </li>
                </ol>
            </nav>
        </div>
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <form class="forms-sample" method="POST" action="{{ route('faqs.store') }}"
                        enctype='multipart/form-data'>
                        @csrf
                        <h4 align="center">English Language</h4><br />
                        <div class="form-group col-sm-12">
                            <label for="exampleInputName1">Faq Question</label>
                            <input type="text" class="form-control" id="question" name="question"
                                placeholder="Faq Question" value="{{ old('question') }}">
                            @error('question')
                                <p style="color: red">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="form-group col-sm-12">
                            <label for="exampleInputEmail3">Faq Answer</label>
                            <textarea class="form-control" id="answer" name="answer" placeholder="Faq Answer" rows="3">{{ old('answer') }}</textarea>
                            @error('answer')
                                <p style="color: red">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="form-group col-sm-12">
                            <label for="exampleInputEmail3">Is Active</label>
                            <select class="form-control" id="is_active" name="is_active">
                                <option value="1" {{ old('is_active') == 1 ? 'selected' : '' }}>Yes</option>
                                <option value="0" {{ old('is_active') == 0 ? 'selected' : '' }}>No</option>
                            </select>
                            @error('is_active')
                                <p style="color: red">{{ $message }}</p>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-gradient-primary me-2">Submit</button>
                        <a href="{{ url('faqs') }}" class="btn btn-light">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
