@extends('layouts.app')
@section('title','Static Content')
@section('pagetitle','Static Content')
@section('sort_name',$data['sort_name'])
@section('content')
<div class="content-wrapper">
  <div class="page-header">
    <h3 class="page-title">Static Content</h3>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="{{url('static-content/create')}}" title="Add">
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
          <table class="table table-striped" id="static-content">
            <thead>
              <tr>
                <th> Slug </th>
                <th> Title </th>
                <th> Image </th>
                <th> Action </th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('scripts')
<script type="text/javascript">
  var oTable = $('#static-content').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
      url: "{{url('static-content')}}",
      data: function(d) {
        d.search = "{{$data['filter']}}";
      }
    },
    columns: [
      { data: 'slug', name: 'slug' },
      { data: 'title', name: 'title' },
      { data: 'image', name: 'image', orderable: false, searchable: false },
      { data: 'action', name: 'action', orderable: false, searchable: false }
    ],
    // columnDefs: [
    //     {
    //         "targets":[1],
    //         "render": function(data) {
    //             return data[0].toUpperCase() + data.slice(1);
    //         }
    //     }
    // ],
  });
  $('.dataTables_filter').hide();

  $('#reset').on('click', function(e) {
    oTable.draw();
    e.preventDefault();
  });
</script>
@endsection