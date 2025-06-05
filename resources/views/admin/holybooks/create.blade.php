@extends('layouts.app')
@section('title','Holy Books')
@section('pagetitle','Holy Books')
@section('sort_name',$data['sort_name'])
@section('content')
<div class="content-wrapper">
    <div class="page-header">
      <h3 class="page-title">Create Holy Book</h3>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="{{url('books')}}" title="Back">
              <label><- Back</label>
            </a>
          </li>
        </ol>
      </nav>
    </div>
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <form class="forms-sample" method="POST" action="{{route('books.store')}}" enctype='multipart/form-data'>
                    @csrf
                    <h4 align="center">English Language</h4><br/>
                    <div class="form-group col-sm-12">
                        <label for="exampleInputEmail3">Books Type</label>
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="form-check-label">
                                        <input type="radio" class="form-check-input" name="type" value="holy" checked> Holy Books
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="form-check-label">
                                        <input type="radio" class="form-check-input" name="type" value="author">Other Authors 
                                    </label>
                                </div>
                            </div>
                        </div>
                        @error('type')
                            <p style="color: red">{{$message}}</p>
                        @enderror
                    </div>
                    <div class="form-group col-sm-12 holy">
                        <label>Book Image</label>
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
                    <div class="form-group col-sm-12 other" style="display:none;">
                        <label>Book Image</label>
                        <input type="file" name="other_image" class="file-upload-default">
                        <div class="input-group col-xs-12">
                            <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                            <span class="input-group-append">
                                <button class="file-upload-browse btn btn-gradient-primary" type="button">Upload</button>
                            </span>
                        </div>
                        @error('other_image')
                            <p style="color: red">{{$message}}</p>
                        @enderror
                    </div>
                    <div class="form-group col-sm-12 holy">
                        <label>Book PDF File</label>
                        <input type="file" name="url" class="file-upload-default">
                        <div class="input-group col-xs-12">
                            <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                            <span class="input-group-append">
                                <button class="file-upload-browse btn btn-gradient-primary" type="button">Upload</button>
                            </span>
                        </div>
                        @error('url')
                            <p style="color: red">{{$message}}</p>
                        @enderror
                    </div>
                    <div class="form-group col-sm-12 other" style="display:none;">
                        <label>Book PDF File</label>
                        <input type="file" name="other_url" class="file-upload-default">
                        <div class="input-group col-xs-12">
                            <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                            <span class="input-group-append">
                                <button class="file-upload-browse btn btn-gradient-primary" type="button">Upload</button>
                            </span>
                        </div>
                        @error('other_url')
                            <p style="color: red">{{$message}}</p>
                        @enderror
                    </div>
                    <div class="form-group col-sm-12">
                        <label for="exampleInputName1">Author Name</label>
                        <input type="text" class="form-control" id="author" name="author" placeholder="Author Name" value="{{old('author')}}">
                        @error('author')
                            <p style="color: red">{{$message}}</p>
                        @enderror
                    </div>
                    <div class="form-group col-sm-12 holy">
                        <label for="exampleInputName1">Title</label>
                        <input type="text" class="form-control" id="title" name="title" placeholder="Title" value="{{old('title')}}">
                        @error('title')
                            <p style="color: red">{{$message}}</p>
                        @enderror
                    </div>
                    <div class="form-group col-sm-12 other" style="display:none;">
                        <label for="exampleInputName1">Title</label>
                        <input type="text" class="form-control" id="other_title" name="other_title" placeholder="Title" value="{{old('other_title')}}">
                        @error('other_title')
                            <p style="color: red">{{$message}}</p>
                        @enderror
                    </div>
                    <div class="form-group col-sm-12 holy">
                        <label for="exampleInputEmail3">Description</label>
                        <textarea class="form-control" id="description" name="description" placeholder="Description" rows="4">{{old('description')}}</textarea>
                        @error('description')
                            <p style="color: red">{{$message}}</p>
                        @enderror
                    </div>
                    <div class="form-group col-sm-12 other" style="display:none;">
                        <label for="exampleInputEmail3">Description</label>
                        <textarea class="form-control" id="other_description" name="other_description" placeholder="Description" rows="4">{{old('other_description')}}</textarea>
                        @error('other_description')
                            <p style="color: red">{{$message}}</p>
                        @enderror
                    </div>
                    <h4 align="center">Arabic Language</h4><br/>
                    <div class="form-group col-sm-12 holy">
                        <label for="exampleInputName1">Title</label>
                        <input type="text" class="form-control" id="ar_title" name="ar_title" placeholder="Title" value="{{old('ar_title')}}">
                        @error('ar_title')
                            <p style="color: red">{{$message}}</p>
                        @enderror
                    </div>
                    <div class="form-group col-sm-12 other" style="display:none;">
                        <label for="exampleInputName1">Title</label>
                        <input type="text" class="form-control" id="other_ar_title" name="other_ar_title" placeholder="Title" value="{{old('other_ar_title')}}">
                        @error('other_ar_title')
                            <p style="color: red">{{$message}}</p>
                        @enderror
                    </div>
                    <div class="form-group col-sm-12 holy">
                        <label for="exampleInputEmail3">Description</label>
                        <textarea class="form-control" id="ar_description" name="ar_description" placeholder="Description" rows="4">{{old('ar_description')}}</textarea>
                        @error('ar_description')
                            <p style="color: red">{{$message}}</p>
                        @enderror
                    </div>
                    <div class="form-group col-sm-12 other" style="display:none;">
                        <label for="exampleInputEmail3">Description</label>
                        <textarea class="form-control" id="other_ar_description" name="other_ar_description" placeholder="Description" rows="4">{{old('other_ar_description')}}</textarea>
                        @error('other_ar_description')
                            <p style="color: red">{{$message}}</p>
                        @enderror
                    </div>
                    <h4 align="center">Persian Language</h4><br/>
                    <div class="form-group col-sm-12 holy">
                        <label for="exampleInputName1">Title</label>
                        <input type="text" class="form-control" id="pe_title" name="pe_title" placeholder="Title" value="{{old('pe_title')}}">
                        @error('pe_title')
                            <p style="color: red">{{$message}}</p>
                        @enderror
                    </div>
                    <div class="form-group col-sm-12 other" style="display:none;">
                        <label for="exampleInputName1">Title</label>
                        <input type="text" class="form-control" id="other_pe_title" name="other_pe_title" placeholder="Title" value="{{old('other_pe_title')}}">
                        @error('other_pe_title')
                            <p style="color: red">{{$message}}</p>
                        @enderror
                    </div>
                    <div class="form-group col-sm-12 holy">
                        <label for="exampleInputEmail3">Description</label>
                        <textarea class="form-control" id="pe_description" name="pe_description" placeholder="Description" rows="4">{{old('pe_description')}}</textarea>
                        @error('pe_description')
                            <p style="color: red">{{$message}}</p>
                        @enderror
                    </div>
                    <div class="form-group col-sm-12 other" style="display:none;">
                        <label for="exampleInputEmail3">Description</label>
                        <textarea class="form-control" id="other_pe_description" name="other_pe_description" placeholder="Description" rows="4">{{old('other_pe_description')}}</textarea>
                        @error('other_pe_description')
                            <p style="color: red">{{$message}}</p>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-gradient-primary me-2">Submit</button>
                    <a href="{{url('books')}}" class="btn btn-light">Cancel</a>
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
    $('#description,#other_description,#ar_description,#other_ar_description,#pe_description,#other_pe_description').summernote({
        height: 300
    });

    $('input[type=radio][name=type]').change(function() {
        if (this.value == 'holy') {
            $(".other").hide();
            $(".holy").show();
        }
        else if (this.value == 'author') {
            $(".holy").hide();
            $(".other").show();
        }
    });
</script>
@endsection