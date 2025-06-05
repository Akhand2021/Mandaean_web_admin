@extends('layouts.app')
@section('title','Product')
@section('pagetitle','Product')
@section('sort_name',$data['sort_name'])
@section('content')
<div class="content-wrapper">
    <div class="page-header">
      <h3 class="page-title">Edit Product</h3>
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
                <form class="forms-sample" method="POST" action="{{route('product.update',$data['product']->id)}}" enctype='multipart/form-data'>
                    @csrf
                    @method('PUT')
                    <h4 align="center">English Language</h4><br/>
                    <div class="row">
                        @foreach($data['product']->images as $key => $image)
                            <div class="form-group col-sm-2 img-remove" id="img-div-{{$image->id}}">
                                <a href="javascript:void(0);" title="Remove" class="remove-image" id="remove-img-{{$image->id}}"><i class="fas fa-remove"></i> X</a>
                                <img src="{{url('/')}}/{{$image->image}}" height="110px;" width="170px;">
                            </div>
                        @endforeach
                    </div>
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
                        <input type="text" class="form-control" id="name" name="name" placeholder="Name" value="{{old('name',$data['product']->name)}}">
                        @error('name')
                            <p style="color: red">{{$message}}</p>
                        @enderror
                    </div>
                    <div class="form-group col-sm-12">
                        <label for="exampleInputEmail3">Description</label>
                        <input class="form-control" id="condition" name="condition" placeholder="Description" value="{{old('condition',$data['product']->condition)}}">
                        @error('condition')
                            <p style="color: red">{{$message}}</p>
                        @enderror
                    </div>
                    <div class="form-group col-sm-12">
                        <label for="exampleInputEmail3">Category</label>
                        <input class="form-control" id="category" name="category" placeholder="Category" value="{{old('category',$data['product']->category)}}">
                        @error('category')
                            <p style="color: red">{{$message}}</p>
                        @enderror
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label for="exampleInputEmail3">Inventory</label>
                            <input type="number" class="form-control" id="inventory" name="inventory" placeholder="Inventory" value="{{old('inventory',$data['product']->inventory)}}" min="0">
                            @error('inventory')
                                <p style="color: red">{{$message}}</p>
                            @enderror
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="exampleSelectBrand">Brand</label>
                            <select class="form-select" id="brand" name="brand">
                                @foreach($data['brands'] as $brand)
                                    <option value="{{$brand->id}}">{{$brand->name}}</option>
                                @endforeach
                            </select>
                            @error('brand')
                                <p style="color: red">{{$message}}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group col-sm-12">
                        <label for="exampleInputEmail3">Color</label>
                        <div class="row">
                            @foreach($data['colors'] as $color)
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="form-check-label">
                                            <input type="checkbox" class="form-check-input" name="color[]" value="{{$color->id}}" {{(in_array($color->id,$data['product']->color_ids))?"checked":""}}> {{$color->name}} 
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @error('color')
                            <p style="color: red">{{$message}}</p>
                        @enderror
                    </div>
                    <div class="form-group col-sm-12">
                        <label for="exampleInputEmail3">Size</label>
                        <div class="row">
                            <?php 
                            $k = 0;
                            ?>
                            @foreach($data['sizes'] as $size)
                            <?php
                            if(in_array($size->id,$data['product']->size_ids)){
                                $value = $data['product']->sizeprice[$k];
                                $k++;
                            }else{
                                $value = "";
                            }
                            ?>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="form-check-label">
                                            <input type="checkbox" class="form-check-input size-checkbox" id="size_{{$size->id}}" name="size[]" value="{{$size->id}}" {{(in_array($size->id,$data['product']->size_ids))?"checked":""}}> {{$size->name}} 
                                        </label>
                                        <input type="text" class="form-control" id="price_{{$size->id}}" name="sizeprice[]" placeholder="price" style="{{(in_array($size->id,$data['product']->size_ids))?'display:block':'display:none'}}" value="{{$value}}">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @error('size')
                            <p style="color: red">{{$message}}</p>
                        @enderror
                    </div>
                    <div class="form-group col-sm-12">
                        <label for="exampleInputEmail3">Material</label>
                        <input class="form-control" id="material" name="material" placeholder="Material" value="{{old('material',$data['product']->material)}}">
                        @error('material')
                            <p style="color: red">{{$message}}</p>
                        @enderror
                    </div>
                    <h4 align="center">Arabic Language</h4><br/>
                    <div class="form-group col-sm-12">
                        <label for="exampleInputName1">Name</label>
                        <input type="text" class="form-control" id="ar_name" name="ar_name" placeholder="Name" value="{{old('ar_name',$data['product']->ar_name)}}">
                        @error('ar_name')
                            <p style="color: red">{{$message}}</p>
                        @enderror
                    </div>
                    <div class="form-group col-sm-12">
                        <label for="exampleInputEmail3">Description</label>
                        <input class="form-control" id="ar_condition" name="ar_condition" placeholder="Description" value="{{old('ar_condition',$data['product']->ar_condition)}}">
                        @error('ar_condition')
                            <p style="color: red">{{$message}}</p>
                        @enderror
                    </div>
                    <div class="form-group col-sm-12">
                        <label for="exampleInputEmail3">Category</label>
                        <input class="form-control" id="ar_category" name="ar_category" placeholder="Category" value="{{old('ar_category',$data['product']->ar_category)}}">
                        @error('ar_category')
                            <p style="color: red">{{$message}}</p>
                        @enderror
                    </div>
                    <div class="form-group col-sm-12">
                        <label for="exampleInputEmail3">Material</label>
                        <input class="form-control" id="ar_material" name="ar_material" placeholder="Material" value="{{old('ar_material',$data['product']->ar_material)}}">
                        @error('ar_material')
                            <p style="color: red">{{$message}}</p>
                        @enderror
                    </div>
                    <h4 align="center">Persian Language</h4><br/>
                    <div class="form-group col-sm-12">
                        <label for="exampleInputName1">Name</label>
                        <input type="text" class="form-control" id="pe_name" name="pe_name" placeholder="Name" value="{{old('pe_name',$data['product']->pe_name)}}">
                        @error('pe_name')
                            <p style="color: red">{{$message}}</p>
                        @enderror
                    </div>
                    <div class="form-group col-sm-12">
                        <label for="exampleInputEmail3">Description</label>
                        <input class="form-control" id="pe_condition" name="pe_condition" placeholder="Description" value="{{old('pe_condition',$data['product']->pe_condition)}}">
                        @error('pe_condition')
                            <p style="color: red">{{$message}}</p>
                        @enderror
                    </div>
                    <div class="form-group col-sm-12">
                        <label for="exampleInputEmail3">Category</label>
                        <input class="form-control" id="pe_category" name="pe_category" placeholder="Category" value="{{old('pe_category',$data['product']->pe_category)}}">
                        @error('pe_category')
                            <p style="color: red">{{$message}}</p>
                        @enderror
                    </div>
                    <div class="form-group col-sm-12">
                        <label for="exampleInputEmail3">Material</label>
                        <input class="form-control" id="pe_material" name="pe_material" placeholder="Material" value="{{old('pe_material',$data['product']->pe_material)}}">
                        @error('pe_material')
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
<style>
.img-remove {
    position: relative;
}

.img-remove > a {
    position: absolute;
    color: red;
    top: 0;
    right: 30px;
    text-decoration:none;
}
</style>
@endsection
@section('scripts')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script type="text/javascript">
    $('#description,#ar_description,#pe_description').summernote({
        height: 300
    });

    $(".remove-image").click(function(){
        swal({
            title: "Are you sure?",
            text: "Once deleted, you will not be able to recover this image!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
            })
            .then((willDelete) => {
            if (willDelete) {
                let id = (this.id).replace('remove-img-','');
                var url = '{{url("remove-image")}}';
                var token = $("meta[name='csrf-token']").attr("content");
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        _token: token,
                        id: id
                    },
                    success: function (response){
                        swal("Your image file has been deleted!", {
                            icon: "success",
                        });
                        $("#img-div-"+id).remove();
                    }
                });
            }
        });
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