@extends('layouts.app')
@section('title','Sizes')
@section('pagetitle','Sizes')
@section('sort_name',$data['sort_name'])
@section('content')
<div class="content-wrapper">
    <div class="page-header">
      <h3 class="page-title">Edit Size</h3>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="{{url('size')}}" title="Back">
              <label><- Back</label>
            </a>
          </li>
        </ol>
      </nav>
    </div>
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <form class="forms-sample" method="POST" action="{{route('size.update',$data['size']->id)}}" enctype='multipart/form-data'>
                    @csrf
                    @method('PUT')
                    <h4 align="center">English Language</h4><br/>
                    <div class="form-group col-sm-12">
                        <label for="exampleInputName1">Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Name" value="{{old('name',$data['size']->name)}}">
                        @error('name')
                            <p style="color: red">{{$message}}</p>
                        @enderror
                    </div>
                    <div class="form-group col-sm-12">
                        <label for="exampleInputName1">Code</label>
                        <input type="text" class="form-control" id="code" name="code" placeholder="Code" value="{{old('code',$data['size']->code)}}">
                        @error('code')
                            <p style="color: red">{{$message}}</p>
                        @enderror
                    </div>

                    <h4 align="center">Arabic Language</h4><br/>
                    <div class="form-group col-sm-12">
                        <label for="exampleInputName1">Name</label>
                        <input type="text" class="form-control" id="ar_name" name="ar_name" placeholder="Name" value="{{old('ar_name',$data['size']->ar_name)}}">
                        @error('ar_name')
                            <p style="color: red">{{$message}}</p>
                        @enderror
                    </div>
                    <div class="form-group col-sm-12">
                        <label for="exampleInputName1">Code</label>
                        <input type="text" class="form-control" id="ar_code" name="ar_code" placeholder="Code" value="{{old('ar_code',$data['size']->ar_code)}}">
                        @error('ar_code')
                            <p style="color: red">{{$message}}</p>
                        @enderror
                    </div>

                    <h4 align="center">Persian Language</h4><br/>
                    <div class="form-group col-sm-12">
                        <label for="exampleInputName1">Name</label>
                        <input type="text" class="form-control" id="pe_name" name="pe_name" placeholder="Name" value="{{old('pe_name',$data['size']->pe_name)}}">
                        @error('pe_name')
                            <p style="color: red">{{$message}}</p>
                        @enderror
                    </div>
                    <div class="form-group col-sm-12">
                        <label for="exampleInputEmail3">Code</label>
                        <input type="text" class="form-control" id="pe_code" name="pe_code" placeholder="Code" value="{{old('pe_code',$data['size']->pe_code)}}">
                        @error('pe_code')
                            <p style="color: red">{{$message}}</p>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-gradient-primary me-2">Submit</button>
                    <a href="{{url('size')}}" class="btn btn-light">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection