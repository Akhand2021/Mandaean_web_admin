@extends('layouts.app')
@section('title','Advertisment')
@section('pagetitle','Advertisment')
@section('sort_name',$data['sort_name'])
@section('content')
<div class="content-wrapper">
    <div class="page-header">
      <h3 class="page-title">Edit Advertisment</h3>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="{{url('advertisment')}}" title="Back">
              <label><- Back</label>
            </a>
          </li>
        </ol>
      </nav>
    </div>
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <form class="forms-sample" method="POST" action="{{route('advertisment.update',$data['advertisment']->id)}}" enctype='multipart/form-data'>
                    @csrf
                    @method('PUT')
                    <div class="form-group col-sm-12">
                        <label for="exampleInputName1">Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Name" value="{{old('name',$data['advertisment']->name)}}">
                        @error('name')
                            <p style="color: red">{{$message}}</p>
                        @enderror
                    </div>
                    <div class="form-group col-sm-12">
                        <label for="exampleInputName1">Email</label>
                        <input type="text" class="form-control" id="email" name="email" placeholder="Email" value="{{old('email',$data['advertisment']->email)}}">
                        @error('email')
                            <p style="color: red">{{$message}}</p>
                        @enderror
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-1">
                            <label for="exampleInputPassword4">Code</label>
                            <select class="form-select" id="code" name="code" style="width: 80px !important;height: 45px;">
                                <option value="1" {{ old("code",$data['advertisment']->code) == '1' ? "selected" : "" }}>+1</option>
                                <option value="61" {{ old("code",$data['advertisment']->code) == '61' ? "selected" : "" }}>+61</option>
                                <option value="91" {{ old("code",$data['advertisment']->code) == '91' ? "selected" : "" }}>+91</option>
                            </select>
                            @error('code')
                                <p style="color: red">{{$message}}</p>
                            @enderror
                        </div>
                        <div class="form-group col-sm-5">
                            <label for="exampleInputPassword4">Mobile Number</label>
                            <input type="text" class="form-control" id="phone" name="phone" placeholder="Mobile Number" value="{{old('phone',$data['advertisment']->phone)}}" style="width:435px;">
                            @error('phone')
                                <p style="color: red">{{$message}}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group col-sm-12">
                        <label for="exampleInputName1">Subject</label>
                        <input type="text" class="form-control" id="subject" name="subject" placeholder="Subject" value="{{old('subject',$data['advertisment']->subject)}}">
                        @error('subject')
                            <p style="color: red">{{$message}}</p>
                        @enderror
                    </div>
                    <div class="form-group col-sm-12">
                        <label for="exampleInputEmail3">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="5" placeholder="Description">{{old('description',$data['advertisment']->description)}}</textarea>
                        @error('description')
                            <p style="color: red">{{$message}}</p>
                        @enderror
                    </div>
                    <div class="form-group col-sm-12">
                        <label for="exampleSelectGender">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="active" {{ old("status",$data['advertisment']->status) == 'active' ? "selected" : "" }}>Active</option>
                            <option value="inactive" {{ old("status",$data['advertisment']->status) == "inactive" ? "selected" : "" }}>Inactive</option>
                        </select>
                        @error('status')
                            <p style="color: red">{{$message}}</p>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-gradient-primary me-2">Submit</button>
                    <a href="{{url('advertisment')}}" class="btn btn-light">Cancel</a>
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