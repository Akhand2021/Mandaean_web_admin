@extends('layouts.app')
@section('title','Static Content')
@section('pagetitle','Static Content')
@section('sort_name',$data['sort_name'])
@section('content')
<div class="content-wrapper">
    <div class="page-header">
      <h3 class="page-title">Edit Static Content</h3>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="{{url('static-content')}}" title="Back">
              <label><- Back</label>
            </a>
          </li>
        </ol>
      </nav>
    </div>
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <form class="forms-sample" method="POST" action="{{route('static-content.update',$data['static']->id)}}" enctype='multipart/form-data'>
                    @csrf
                    @method('PUT')
                    <h4 align="center">English Language</h4><br/>
                    <div class="form-group col-sm-12">
                        <label for="exampleInputName1">Title</label>
                        <input type="text" class="form-control" id="title" name="title" placeholder="Title" value="{{old('title',$data['static']->title)}}">
                        @error('title')
                            <p style="color: red">{{$message}}</p>
                        @enderror
                    </div>
                    <div class="form-group col-sm-12">
                        <label for="exampleInputEmail3">Content</label>
                        <textarea class="form-control" id="content" name="content" placeholder="Content">{{old('content',$data['static']->content)}}</textarea>
                        @error('content')
                            <p style="color: red">{{$message}}</p>
                        @enderror
                    </div>
                    
                    <h4 align="center">Arabic Language</h4><br/>
                    <div class="form-group col-sm-12">
                        <label for="exampleInputName1">Title</label>
                        <input type="text" class="form-control" id="ar_title" name="ar_title" placeholder="Title" value="{{old('ar_title',$data['static']->ar_title)}}">
                        @error('ar_title')
                            <p style="color: red">{{$message}}</p>
                        @enderror
                    </div>
                    <div class="form-group col-sm-12">
                        <label for="exampleInputEmail3">Content</label>
                        <textarea class="form-control" id="ar_content" name="ar_content" placeholder="Content">{{old('ar_content',$data['static']->ar_content)}}</textarea>
                        @error('ar_content')
                            <p style="color: red">{{$message}}</p>
                        @enderror
                    </div>

                    <h4 align="center">Persian Language</h4><br/>
                    <div class="form-group col-sm-12">
                        <label for="exampleInputName1">Title</label>
                        <input type="text" class="form-control" id="pe_title" name="pe_title" placeholder="Title" value="{{old('pe_title',$data['static']->pe_title)}}">
                        @error('pe_title')
                            <p style="color: red">{{$message}}</p>
                        @enderror
                    </div>
                    <div class="form-group col-sm-12">
                        <label for="exampleInputEmail3">Content</label>
                        <textarea class="form-control" id="pe_content" name="pe_content" placeholder="Content">{{old('pe_content',$data['static']->pe_content)}}</textarea>
                        @error('pe_content')
                            <p style="color: red">{{$message}}</p>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-gradient-primary me-2">Submit</button>
                    <a href="{{url('static-content')}}" class="btn btn-light">Cancel</a>
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
    $('#content,#ar_content,#pe_content').summernote({
        height: 300
    });
</script>
@endsection