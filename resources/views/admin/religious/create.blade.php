@extends('layouts.app')
@section('title','Religious Occasion')
@section('pagetitle','Religious Occasion')
@section('sort_name',$data['sort_name'])
@section('content')
<div class="content-wrapper">
    <div class="page-header">
      <h3 class="page-title">Create Religious Occasion</h3>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="{{url('religious-occasion')}}" title="Back">
              <label><- Back</label>
            </a>
          </li>
        </ol>
      </nav>
    </div>
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <form class="forms-sample" method="POST" action="{{route('religious-occasion.store')}}" enctype='multipart/form-data'>
                    @csrf
                    <div class="row">
                        <div class="form-group col-sm-4">
                            <label for="exampleInputName1">Date Type</label>
                            <select name="date_type" id="date_type" class="form-select">
                                <option value="Gregorian">Gregorian Date</option>
                                <option value="Solar">Solar Date</option>
                            </select>
                            @error('date_type')
                                <p style="color: red">{{$message}}</p>
                            @enderror
                        </div>
                        <div class="form-group col-sm-2 Gregorian-div">
                            <label for="exampleInputName1">Year</label>
                            <select name="year" class="form-select">
                                @for($i=1921; $i<=2121; $i++)
                                    <option value="{{$i}}">{{$i}}</option>
                                @endfor
                            </select>
                            @error('year')
                                <p style="color: red">{{$message}}</p>
                            @enderror
                        </div>
                        <div class="form-group col-sm-2 Solar-div" style="display:none;">
                            <label for="exampleInputName1">Year</label>
                            <select name="year" class="form-select">
                                @for($i=1300; $i<=1500; $i++)
                                    <option value="{{$i}}">{{$i}}</option>
                                @endfor
                            </select>
                            @error('year')
                                <p style="color: red">{{$message}}</p>
                            @enderror
                        </div>
                        <div class="form-group col-sm-3">
                            <label for="exampleInputName1">Month</label>
                            <select name="month" class="form-select">
                                @for($j=1; $j<=12; $j++)
                                    <option value="{{$j}}">{{date('F',strtotime('01-'.$j.'-2023'))}}</option>
                                @endfor
                            </select>
                            @error('month')
                                <p style="color: red">{{$message}}</p>
                            @enderror
                        </div>
                        <div class="form-group col-sm-2">
                            <label for="exampleInputName1">Day</label>
                            <select name="day" class="form-select">
                                @for($z=1; $z<=31; $z++)
                                    <option value="{{$z}}">{{$z}}</option>
                                @endfor
                            </select>
                            @error('day')
                                <p style="color: red">{{$message}}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-5">
                            <label for="exampleInputEmail3">Occasion Type</label>
                            <select name="occasion_type" class="form-select">
                                <option value="Religious Holy Days">Religious Holy Days</option>
                                <option value="First day of Mandaic Month">First day of Mandaic Month</option>
                                <option value="Minor Mbattal Day">Minor Mbattal Day</option>
                                <option value="Major Mbattal Day">Major Mbattal Day</option>
                            </select>
                            @error('occasion_type')
                                <p style="color: red">{{$message}}</p>
                            @enderror
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="exampleInputEmail3">Occasion</label>
                            <input type="text" class="form-control" id="occasion" name="occasion" placeholder="Occasion" value="{{old('occasion')}}">
                            @error('occasion')
                                <p style="color: red">{{$message}}</p>
                            @enderror
                        </div>
                    </div>
                    <button type="submit" class="btn btn-gradient-primary me-2">Submit</button>
                    <a href="{{url('religious-occasion')}}" class="btn btn-light">Cancel</a>
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

    $(document).on('change', '#date_type', function(){
        let type = $(this).val();
        if(type=='Solar'){
            $(".Gregorian-div").hide();
            $(".Solar-div").show();
        }else{
            $(".Gregorian-div").show();
            $(".Solar-div").hide();
        }
    });
</script>
@endsection