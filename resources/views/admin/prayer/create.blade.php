@extends('layouts.app')
@section('title','Prayers')
@section('pagetitle','Prayers')
@section('sort_name',$data['sort_name'])
@section('content')
<div class="content-wrapper">
    <div class="page-header">
      <h3 class="page-title">Create Prayer</h3>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="{{url('prayer')}}" title="Back">
              <label><- Back</label>
            </a>
          </li>
        </ol>
      </nav>
    </div>
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <form class="forms-sample" method="POST" action="{{route('prayer.store')}}" enctype='multipart/form-data'>
                    @csrf
                    <h4 align="center">English Language</h4><br/>
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
                        <label for="exampleInputName1">Title</label>
                        <input type="text" class="form-control" id="title" name="title" placeholder="Title" value="{{old('title')}}">
                        @error('title')
                            <p style="color: red">{{$message}}</p>
                        @enderror
                    </div>
                    <div class="form-group col-sm-12">
                        <label for="exampleInputEmail3">Subtitle</label>
                        <textarea class="form-control" id="subtitle" name="subtitle" placeholder="Subtitle" rows="3">{{old('subtitle')}}</textarea>
                        @error('subtitle')
                            <p style="color: red">{{$message}}</p>
                        @enderror
                    </div>
                    <div class="form-group col-sm-12">
                        <label for="exampleInputEmail3">Description</label>
                        <textarea class="form-control" id="description" name="description" placeholder="Description" rows="5">{{old('description')}}</textarea>
                        @error('description')
                            <p style="color: red">{{$message}}</p>
                        @enderror
                    </div>
                    <!-- <div class="form-group col-sm-12">
                        <label for="exampleInputEmail3">Other Info</label>
                        <textarea class="form-control" id="other_info" name="other_info" placeholder="Description" rows="5">{{old('other_info')}}</textarea>
                        @error('other_info')
                            <p style="color: red">{{$message}}</p>
                        @enderror
                    </div> -->

                    <h4 align="center">Arabic Language</h4><br/>
                    <div class="form-group col-sm-12">
                        <label for="exampleInputName1">Title</label>
                        <input type="text" class="form-control" id="ar_title" name="ar_title" placeholder="Title" value="{{old('ar_title')}}">
                        @error('ar_title')
                            <p style="color: red">{{$message}}</p>
                        @enderror
                    </div>
                    <div class="form-group col-sm-12">
                        <label for="exampleInputEmail3">Subtitle</label>
                        <textarea class="form-control" id="ar_subtitle" name="ar_subtitle" placeholder="Subtitle" rows="3">{{old('ar_subtitle')}}</textarea>
                        @error('ar_subtitle')
                            <p style="color: red">{{$message}}</p>
                        @enderror
                    </div>
                    <div class="form-group col-sm-12">
                        <label for="exampleInputEmail3">Description</label>
                        <textarea class="form-control" id="ar_description" name="ar_description" placeholder="Description" rows="5">{{old('ar_description')}}</textarea>
                        @error('ar_description')
                            <p style="color: red">{{$message}}</p>
                        @enderror
                    </div>
                    <!-- <div class="form-group col-sm-12">
                        <label for="exampleInputEmail3">Other Info</label>
                        <textarea class="form-control" id="ar_other_info" name="ar_other_info" placeholder="Description" rows="5">{{old('ar_other_info')}}</textarea>
                        @error('ar_other_info')
                            <p style="color: red">{{$message}}</p>
                        @enderror
                    </div> -->

                    <h4 align="center">Persian Language</h4><br/>
                    <div class="form-group col-sm-12">
                        <label for="exampleInputName1">Title</label>
                        <input type="text" class="form-control" id="pe_title" name="pe_title" placeholder="Title" value="{{old('pe_title')}}">
                        @error('pe_title')
                            <p style="color: red">{{$message}}</p>
                        @enderror
                    </div>
                    <div class="form-group col-sm-12">
                        <label for="exampleInputEmail3">Subtitle</label>
                        <textarea class="form-control" id="pe_subtitle" name="pe_subtitle" placeholder="Subtitle" rows="3">{{old('pe_subtitle')}}</textarea>
                        @error('pe_subtitle')
                            <p style="color: red">{{$message}}</p>
                        @enderror
                    </div>
                    <div class="form-group col-sm-12">
                        <label for="exampleInputEmail3">Description</label>
                        <textarea class="form-control" id="pe_description" name="pe_description" placeholder="Description" rows="5">{{old('pe_description')}}</textarea>
                        @error('pe_description')
                            <p style="color: red">{{$message}}</p>
                        @enderror
                    </div>
                    <!-- <div class="form-group col-sm-12">
                        <label for="exampleInputEmail3">Other Info</label>
                        <textarea class="form-control" id="pe_other_info" name="pe_other_info" placeholder="Description" rows="5">{{old('pe_other_info')}}</textarea>
                        @error('pe_other_info')
                            <p style="color: red">{{$message}}</p>
                        @enderror
                    </div> -->
                    <button type="submit" class="btn btn-gradient-primary me-2">Submit</button>
                    <a href="{{url('prayer')}}" class="btn btn-light">Cancel</a>
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
    $('#description,#other_info,#ar_description,#ar_other_info,#pe_description,#pe_other_info').summernote({
        height: 300
    });
</script>
@endsection