@extends('layouts.app')
@section('title','Product')
@section('pagetitle','Product')
@section('sort_name',$data['sort_name'])
@section('content')
<div class="content-wrapper">
    <div class="page-header">
      <h3 class="page-title">Create Product</h3>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="{{url('product')}}" title="Back">
              <label><- Back</label>
            </a>
          </li>
        </ol>
      </nav>
    </div>
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <form class="forms-sample" method="POST" action="{{route('product.store')}}" enctype='multipart/form-data'>
                    @csrf
                    <h4 align="center">English Language</h4><br/>
                    <div class="form-group col-sm-12">
                        <label>File upload</label>
                        <input type="file" name="photo[]" class="file-upload-default" multiple>
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
                    <div class="form-group col-sm-12">
                        <label for="exampleInputName1">Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Name" value="{{old('name')}}">
                        @error('name')
                            <p style="color: red">{{$message}}</p>
                        @enderror
                    </div>
                    <div class="form-group col-sm-12">
                        <label for="exampleInputEmail3">Description</label>
                        <input class="form-control" id="condition" name="condition" placeholder="Description" value="{{old('condition')}}">
                        @error('condition')
                            <p style="color: red">{{$message}}</p>
                        @enderror
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label for="exampleInputEmail3">Inventory</label>
                            <input type="number" class="form-control" id="inventory" name="inventory" placeholder="Inventory" value="{{old('inventory')}}">
                            @error('inventory')
                                <p style="color: red">{{$message}}</p>
                            @enderror
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="exampleInputEmail3">Price</label>
                            <input type="number" class="form-control" id="price" name="price" placeholder="Price" value="{{old('price')}}">
                            @error('price')
                                <p style="color: red">{{$message}}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group col-sm-12">
                        <label for="exampleInputEmail3">Size</label>
                        <div class="row">
                            @foreach($data['sizes'] as $size)
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="form-check-label">
                                            <input type="checkbox" class="form-check-input size-checkbox" id="size_{{$size->id}}" name="size[]" value="{{$size->id}}"> {{$size->name}} 
                                        </label>
                                        <input type="text" class="form-control" id="price_{{$size->id}}" name="sizeprice[]" placeholder="price" style="display:none">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @error('size')
                            <p style="color: red">{{$message}}</p>
                        @enderror
                    </div>
                    <h4 align="center">Arabic Language</h4><br/>
                    <div class="form-group col-sm-12">
                        <label for="exampleInputName1">Name</label>
                        <input type="text" class="form-control" id="ar_name" name="ar_name" placeholder="Name" value="{{old('ar_name')}}">
                        @error('ar_name')
                            <p style="color: red">{{$message}}</p>
                        @enderror
                    </div>
                    <div class="form-group col-sm-12">
                        <label for="exampleInputEmail3">Description</label>
                        <input class="form-control" id="ar_condition" name="ar_condition" placeholder="Description" value="{{old('ar_condition')}}">
                        @error('ar_condition')
                            <p style="color: red">{{$message}}</p>
                        @enderror
                    </div>
                    <div class="form-group col-sm-12">
                        <label for="exampleInputEmail3">Category</label>
                        <input class="form-control" id="ar_category" name="ar_category" placeholder="Category" value="{{old('ar_category')}}">
                        @error('ar_category')
                            <p style="color: red">{{$message}}</p>
                        @enderror
                    </div>
                    <h4 align="center">Persian Language</h4><br/>
                    <div class="form-group col-sm-12">
                        <label for="exampleInputName1">Name</label>
                        <input type="text" class="form-control" id="pe_name" name="pe_name" placeholder="Name" value="{{old('pe_name')}}">
                        @error('pe_name')
                            <p style="color: red">{{$message}}</p>
                        @enderror
                    </div>
                    <div class="form-group col-sm-12">
                        <label for="exampleInputEmail3">Description</label>
                        <input class="form-control" id="pe_condition" name="pe_condition" placeholder="Description" value="{{old('pe_condition')}}">
                        @error('pe_condition')
                            <p style="color: red">{{$message}}</p>
                        @enderror
                    </div>
                    <div class="form-group col-sm-12">
                        <label for="exampleInputEmail3">Category</label>
                        <input class="form-control" id="pe_category" name="pe_category" placeholder="Category" value="{{old('pe_category')}}">
                        @error('pe_category')
                            <p style="color: red">{{$message}}</p>
                        @enderror
                    </div>
                    <div class="form-group col-sm-12">
                        <label for="status">Status</label>
                        <select class="form-control" id="status" name="status">
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')
                            <p style="color: red">{{$message}}</p>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-gradient-primary me-2">Submit</button>
                    <a href="{{url('product')}}" class="btn btn-light">Cancel</a>
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

    $(".size-checkbox").click(function(){
        var id = (this.id).replace("size_","");
        if ($(this).prop('checked')==true){
            $("#price_"+id).css("display", "block");
            $("#price_"+id).val("0");
        }else{
            $("#price_"+id).css("display", "none");
            $("#price_"+id).val("");

        }
    });
</script>
@endsection