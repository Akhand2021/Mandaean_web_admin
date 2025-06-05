@extends('layouts.app')
@section('title','Reply')
@section('pagetitle','Reply')
@section('sort_name',$data['sort_name'])
@section('content')
<div class="content-wrapper">
    <div class="page-header">
      <h3 class="page-title">Reply</h3>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="{{url('inquiry')}}" title="Back">
              <label><- Back</label>
            </a>
          </li>
        </ol>
      </nav>
    </div>
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <form class="forms-sample" method="POST" action="{{route('inquiry.reply.post',$data['inquiry']->id)}}">
                    @csrf
                    <div class="form-group col-sm-12">
                        <label for="exampleInputName1">Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Name" value="{{old('name',$data['inquiry']->name)}}">
                        @error('name')
                            <p style="color: red">{{$message}}</p>
                        @enderror
                    </div>
                    <div class="form-group col-sm-12">
                        <label for="exampleInputEmail3">Email</label>
                        <input class="form-control" id="email" name="email" placeholder="Email" value="{{old('email',$data['inquiry']->email)}}">
                        @error('email')
                            <p style="color: red">{{$message}}</p>
                        @enderror
                    </div>
                    <div class="form-group col-sm-12">
                        <label for="exampleInputEmail3">Query</label>
                        <textarea class="form-control" id="query" name="query" placeholder="Query">{{old('query',$data['inquiry']->query)}}</textarea>
                        @error('query')
                            <p style="color: red">{{$message}}</p>
                        @enderror
                    </div>
                    <div class="form-group col-sm-12">
                        <label for="exampleInputEmail3">Reply Message</label>
                        <textarea class="form-control" id="reply_message" name="reply_message" placeholder="Reply Message">{{old('reply_message',$data['inquiry']->reply_message)}}</textarea>
                        @error('reply_message')
                            <p style="color: red">{{$message}}</p>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-gradient-primary me-2">Submit</button>
                    <a href="{{url('inquiry')}}" class="btn btn-light">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script type="text/javascript">
    $('#reply_message').summernote({
        height: 300
    });
</script>
@endsection