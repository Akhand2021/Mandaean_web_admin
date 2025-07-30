@extends('layouts.app')
@section('title','Product')
@section('pagetitle','Product')
@section('sort_name',$data['sort_name'])
@section('content')
<div class="content-wrapper">
    <div class="page-header">
      <h3 class="page-title">Product Management</h3>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="javascript:void(0)" id="delete-selected" title="Delete Selected">
              <label class="badge badge-danger">Delete Selected</label>
            </a>
          </li>
          <li class="breadcrumb-item">
            <a href="{{url('product/create')}}" title="Add">
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
            <table class="table table-striped" id="product-table">
              <thead>
                <tr>
                  <th> <input type="checkbox" id="select-all"> </th>
                  <th> Image  </th>
                  <th> Name </th>
                  <th> Category </th>
                  <th> SKU </th>
                  <th> Price </th>
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
    var oTable = $('#product-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{url('product')}}",
            data: function (d) {
                d.search = "{{$data['filter']}}";
            }
        },
        columns: [
            {data: 'checkbox', name: 'checkbox', orderable:false, searchable:false},
            {data: 'image', name: 'image'},
            {data: 'name', name: 'name'},
            {data: 'category', name: 'category'},
            {data: 'sku', name: 'sku'},
            {data: 'price', name: 'price'},
            {data: 'action', name: 'action', orderable:false, searchable:false}
        ],
    });
    $('.dataTables_filter').hide();
    
    $('#reset').on('click', function(e) {
        oTable.draw();
        e.preventDefault();
    });

    $('#select-all').on('click', function(){
        var rows = oTable.rows({ 'search': 'applied' }).nodes();
        $('input[type="checkbox"]', rows).prop('checked', this.checked);
    });

    $('#product-table tbody').on('change', 'input[type="checkbox"]', function(){
        if(!this.checked){
            var el = $('#select-all').get(0);
            if(el && el.checked && ('indeterminate' in el)){
                el.indeterminate = true;
            }
        }
    });

    $('#delete-selected').on('click', function(e) {
        var ids = [];
        $('.product-checkbox:checked').each(function() {
            ids.push($(this).val());
        });

        if (ids.length > 0) {
            if (confirm('Are you sure you want to delete the selected products?')) {
                var token = $("meta[name='csrf-token']").attr("content");
                $.ajax({
                    url: "{{ route('product.massdestroy') }}",
                    type: 'POST',
                    data: {
                        _token: token,
                        ids: ids
                    },
                    success: function (response){
                        oTable.draw();
                    },
                    error: function (xhr) {
                        alert('An error occurred. Please try again.');
                    }
                });
            }
        } else {
            alert('Please select at least one product to delete.');
        }
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