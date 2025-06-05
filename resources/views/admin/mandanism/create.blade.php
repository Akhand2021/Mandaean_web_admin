@extends('layouts.app')
@section('title','Mandanism')
@section('pagetitle','Mandanism')
@section('sort_name',$data['sort_name'])
@section('content')
<div class="content-wrapper">
    <div class="page-header">
      <h3 class="page-title">Create Mandanism</h3>
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
                <form class="forms-sample" method="POST" action="{{route('mandanism.store')}}" enctype='multipart/form-data'>
                    @csrf
                    <h4 align="center">English Language</h4><br/>
                    <div class="form-group col-sm-12">
                        <label>Banner Image</label>
                        <input type="file" name="image" class="file-upload-default">
                        <div class="input-group col-xs-12">
                            <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                            <span class="input-group-append">
                                <button class="file-upload-browse btn btn-gradient-primary" type="button">Upload</button>
                            </span>
                        </div>
                        @error('image')
                            <p style="color: red">{{$message}}</p>
                        @enderror
                    </div>
                    <div class="form-group col-sm-12">
                        <label>PDF/Video</label>
                        <input type="file" name="docs" class="file-upload-default">
                        <div class="input-group col-xs-12">
                            <input type="text" class="form-control file-upload-info" disabled placeholder="Upload PDF/Video">
                            <span class="input-group-append">
                                <button class="file-upload-browse btn btn-gradient-primary" type="button">Upload</button>
                            </span>
                        </div>
                        @error('docs')
                            <p style="color: red">{{$message}}</p>
                        @enderror
                    </div>
                    <div class="form-group col-sm-12">
                        <label for="exampleInputName1">Category</label>
                        <select class="form-select" name="category" id="category">
                            <option value="our_history" {{(old('category')=='our_history')?'selected':''}}>Our History</option>
                            <option value="our_culture" {{(old('category')=='our_culture')?'selected':''}}>Our Culture</option>
                            <option value="online_articles" {{(old('category')=='online_articles')?'selected':''}}>Online Articles</option>
                            <option value="online_videos" {{(old('category')=='online_videos')?'selected':''}}>Online Videos</option>
                        </select>
                        @error('category')
                            <p style="color: red">{{$message}}</p>
                        @enderror
                    </div>
                    <div class="form-group col-sm-12">
                        <label for="exampleInputName1">Online Link (URL)</label>
                        <input type="text" class="form-control" id="online_link" name="online_link" placeholder="Online Link" value="{{old('online_link')}}">
                        @error('online_link')
                            <p style="color: red">{{$message}}</p>
                        @enderror
                    </div>
                    <div class="form-group col-sm-12">
                        <label for="exampleInputName1">Title</label>
                        <input type="text" class="form-control" id="title" name="title" placeholder="Title" value="{{old('title')}}">
                        @error('title')
                            <p style="color: red">{{$message}}</p>
                        @enderror
                    </div>
                    <div class="form-group col-sm-12">
                        <label for="exampleInputName1">Group</label>
                        <input type="text" class="form-control" id="group" name="group" placeholder="Group" value="{{old('group')}}">
                        @error('group')
                            <p style="color: red">{{$message}}</p>
                        @enderror
                    </div>
                    <div class="form-group col-sm-12">
                        <label for="exampleInputName1">Date</label>
                        <input type="date" class="form-control" id="date" name="date" placeholder="Date" value="{{old('date')}}">
                        @error('date')
                            <p style="color: red">{{$message}}</p>
                        @enderror
                    </div>
                    <div class="form-group col-sm-12">
                        <label for="exampleInputEmail3">Description</label>
                        <textarea class="form-control" id="description" name="description" placeholder="Description" rows="4">{{old('description')}}</textarea>
                        @error('description')
                            <p style="color: red">{{$message}}</p>
                        @enderror
                    </div>
                    <h4 align="center">Arabic Language</h4><br/>
                    <div class="form-group col-sm-12">
                        <label for="exampleInputName1">Title</label>
                        <input type="text" class="form-control" id="ar_title" name="ar_title" placeholder="Title" value="{{old('ar_title')}}">
                        @error('ar_title')
                            <p style="color: red">{{$message}}</p>
                        @enderror
                    </div>
                    <div class="form-group col-sm-12">
                        <label for="exampleInputName1">Group</label>
                        <input type="text" class="form-control" id="ar_group" name="ar_group" placeholder="Group" value="{{old('ar_group')}}">
                        @error('ar_group')
                            <p style="color: red">{{$message}}</p>
                        @enderror
                    </div>
                    <div class="form-group col-sm-12">
                        <label for="exampleInputEmail3">Description</label>
                        <textarea class="form-control" id="ar_description" name="ar_description" placeholder="Description" rows="4">{{old('ar_description')}}</textarea>
                        @error('ar_description')
                            <p style="color: red">{{$message}}</p>
                        @enderror
                    </div>
                    <h4 align="center">Persian Language</h4><br/>
                    <div class="form-group col-sm-12">
                        <label for="exampleInputName1">Title</label>
                        <input type="text" class="form-control" id="pe_title" name="pe_title" placeholder="Title" value="{{old('pe_title')}}">
                        @error('pe_title')
                            <p style="color: red">{{$message}}</p>
                        @enderror
                    </div>
                    <div class="form-group col-sm-12">
                        <label for="exampleInputName1">Group</label>
                        <input type="text" class="form-control" id="pe_group" name="pe_group" placeholder="Group" value="{{old('pe_group')}}">
                        @error('pe_group')
                            <p style="color: red">{{$message}}</p>
                        @enderror
                    </div>
                    <div class="form-group col-sm-12">
                        <label for="exampleInputEmail3">Description</label>
                        <textarea class="form-control" id="pe_description" name="pe_description" placeholder="Description" rows="4">{{old('pe_description')}}</textarea>
                        @error('pe_description')
                            <p style="color: red">{{$message}}</p>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-gradient-primary me-2">Submit</button>
                    <a href="{{url('mandanism')}}" class="btn btn-light">Cancel</a>
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
    $('#description,#ar_description,#pe_description').summernote({
        height: 300
    });
</script>
@endsection