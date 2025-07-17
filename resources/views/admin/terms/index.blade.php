@extends('layouts.app')
@section('title', 'Terms & Conditions')
@section('pagetitle', 'Terms & Conditions')
@section('sort_name', $data['sort_name'])
@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">Terms & Conditions</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ url('terms-and-conditions/create') }}" title="Add">
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
                        <table class="table table-striped" id="terms-conditions">
                            <thead>
                                <tr>
                                    <th> ID </th>
                                    <th> Title </th>
                                    <th> Status </th>
                                    <th> Updated At </th>
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
        var oTable = $('#terms-conditions').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ url('terms-and-conditions') }}",
                data: function(d) {
                    d.search = "{{ $data['filter'] ?? '' }}";
                }
            },
            columns: [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'title',
                    name: 'title'
                },
                {
                    data: 'is_active',
                    name: 'is_active',
                    render: function(data) {
                        if (data == 1) {
                            return '<span class="badge badge-success">Active</span>';
                        } else {
                            return '<span class="badge badge-danger">Inactive</span>';
                        }
                    }
                },
                {
                    data: 'updated_at',
                    name: 'updated_at'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }
            ]
        });

        $('.dataTables_filter').hide();

        $('#reset').on('click', function(e) {
            oTable.draw();
            e.preventDefault();
        });
    </script>
@endsection
