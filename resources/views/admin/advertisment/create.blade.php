@extends('layouts.app')
@section('title','Advertisment')
@section('pagetitle','Advertisment')
@section('sort_name',$data['sort_name'])
@section('content')
<div class="content-wrapper">
    <div class="page-header">
      <h3 class="page-title">Create Advertisment</h3>
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
                <form class="forms-sample" method="POST" action="{{route('advertisment.store')}}" enctype='multipart/form-data'>
                    @csrf
                    <div class="form-group col-sm-12">
                        <label for="exampleInputName1">Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Name" value="{{old('name')}}">
                        @error('name')
                            <p style="color: red">{{$message}}</p>
                        @enderror
                    </div>
                    <div class="form-group col-sm-12">
                        <label for="exampleInputName1">Email</label>
                        <input type="text" class="form-control" id="email" name="email" placeholder="Email" value="{{old('email')}}">
                        @error('email')
                            <p style="color: red">{{$message}}</p>
                        @enderror
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-1">
                            <label for="exampleInputPassword4">Code</label>
                            <select class="form-select" id="code" name="code" style="width: 80px !important;height: 45px;">
                                <option value="1" {{ old("code") == '1' ? "selected" : "" }}>+1</option>
                                <option value="91" {{ old("code") == '91' ? "selected" : "" }}>+91</option>
                            </select>
                            @error('code')
                                <p style="color: red">{{$message}}</p>
                            @enderror
                        </div>
                        <div class="form-group col-sm-5">
                            <label for="exampleInputPassword4">Mobile Number</label>
                            <input type="text" class="form-control" id="phone" name="phone" placeholder="Mobile Number" value="{{old('phone')}}" style="width:435px;">
                            @error('phone')
                                <p style="color: red">{{$message}}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group col-sm-12">
                        <label for="exampleInputName1">Subject</label>
                        <input type="text" class="form-control" id="subject" name="subject" placeholder="Subject" value="{{old('subject')}}">
                        @error('subject')
                            <p style="color: red">{{$message}}</p>
                        @enderror
                    </div>
                    <div class="form-group col-sm-12">
                        <label for="exampleInputEmail3">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="5" placeholder="Description">{{old('description')}}</textarea>
                        @error('description')
                            <p style="color: red">{{$message}}</p>
                        @enderror
                    </div>
                    <div class="form-group col-sm-12">
                        <label for="exampleSelectGender">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="active" {{ old("status") == 'active' ? "selected" : "" }}>Active</option>
                            <option value="inactive" {{ old("status") == "inactive" ? "selected" : "" }}>Inactive</option>
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