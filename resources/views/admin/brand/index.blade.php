@extends('layouts.app')
@section('title','Brands')
@section('pagetitle','Brands')
@section('sort_name',$data['sort_name'])
@section('content')
<div class="content-wrapper">
    <div class="page-header">
      <h3 class="page-title">Brands</h3>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="{{url('brand/create')}}" title="Add">
              <label class="badge badge-info">+ Add</label>
            </a>
          </li>
        </ol>
      </nav>
    </div>
    <div class="row">
      <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <table class="table table-striped" id="brand-table">
              <thead>
                <tr>
                  <th> Name </th>
                  <th> Status </th>
                  <th> Action </th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
      </div>
    </div>
</div>
<div class="warning-modal reward-modal">
    <div class="modal fade" id="staticBackdrop3" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <input type="hidden" name="id" id="id">
                <input type="hidden" name="url" id="url">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center p-0">
                    <div class="warning-content">
                        Alert!
                    </div>

                    <h2 class="my-5">Are you sure want to delete?</h2>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-cancle" data-bs-dismiss="modal">Cancel</button>
                    <a href="javascript:void(0)" type="button" class="btn btn-proceed" onclick="DeleteRecord()" >Proceed</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script type="text/javascript">
    var oTable = $('#brand-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{url('brand')}}",
            data: function (d) {
                d.search = "{{$data['filter']}}";
            }
        },
        columns: [
            {data: 'name', name: 'name'},
            {data: 'status', name: 'status'},
            {data: 'action', name: 'action', orderable:false, searchable:false}
        ],
    });
    $('.dataTables_filter').hide();
    
    $('#reset').on('click', function(e) {
        oTable.draw();
        e.preventDefault();
    });

    function setData(id, url){
        $("#id").val(id);
        $("#url").val(url);
    }

    function DeleteRecord(){
        var id = $("#id").val();
        var url = $("#url").val();
        var token = $("meta[name='csrf-token']").attr("content");
        $.ajax({
            url: url,
            type: 'DELETE',
            data: {
                _token: token,
                id: id
            },
            success: function (response){
                $("#staticBackdrop3").hide();
                location.reload();
            }
        });
    }
</script>
@endsection