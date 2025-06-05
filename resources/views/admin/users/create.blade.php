@extends('layouts.app')
@section('title','Users')
@section('pagetitle','Users')
@section('sort_name',$data['sort_name'])
@section('content')
<div class="content-wrapper">
    <div class="page-header">
      <h3 class="page-title">Create User</h3>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="{{url('users')}}" title="Back">
              <label><- Back</label>
            </a>
          </li>
        </ol>
      </nav>
    </div>
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <form class="forms-sample" method="POST" action="{{route('users.store')}}" enctype='multipart/form-data'>
                    @csrf
                    <div class="form-group col-sm-6">
                        <label for="exampleInputName1">Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Name" value="{{old('name')}}">
                        @error('name')
                            <p style="color: red">{{$message}}</p>
                        @enderror
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="exampleInputEmail3">Email address</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Email" value="{{old('name')}}">
                        @error('email')
                            <p style="color: red">{{$message}}</p>
                        @enderror
                    </div>
                    <!-- <div class="row">
                        <div class="form-group col-sm-1">
                            <label for="exampleInputPassword4">Code</label>
                            <select class="form-select" id="country_code" name="country_code" style="width: 80px !important;height: 45px;">
                                <option value="1">+1</option>
                                <option value="91">+91</option>
                            </select>
                            @error('country_code')
                                <p style="color: red">{{$message}}</p>
                            @enderror
                        </div>
                        <div class="form-group col-sm-5">
                            <label for="exampleInputPassword4">Mobile Number</label>
                            <input type="text" class="form-control" id="mobile_no" name="mobile_no" placeholder="Mobile Number" value="{{old('mobile_no')}}" style="width:435px;">
                            @error('mobile_no')
                                <p style="color: red">{{$message}}</p>
                            @enderror
                        </div>
                    </div> -->
                    <div class="form-group col-sm-6">
                        <label for="exampleSelectGender">Gender</label>
                        <select class="form-select" id="gender" name="gender">
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                        @error('gender')
                            <p style="color: red">{{$message}}</p>
                        @enderror
                    </div>
                    <div class="form-group col-sm-6">
                        <label>File upload</label>
                        <input type="file" name="photo" class="file-upload-default">
                        <div class="input-group col-xs-12">
                            <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                            <span class="input-group-append">
                                <button class="file-upload-browse btn btn-gradient-primary" type="button">Upload</button>
                            </span>
                        </div>
                        @error('photo')
                            <p style="color: red">{{$message}}</p>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-gradient-primary me-2">Submit</button>
                    <a href="{{url('users')}}" class="btn btn-light">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection