@extends('layouts.app')
@section('title', 'Comment')
@section('pagetitle', 'COmment')
@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">Comment Management</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                </ol>
            </nav>
        </div>
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-bordered" id="comments-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>User</th>
                                    <th>Post</th>
                                    <th>Comment</th>
                                    <th>Created At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(function() {
            $('#comments-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.comments.index') }}",
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'user',
                        name: 'user'
                    },
                    {
                        data: 'post',
                        name: 'post'
                    },
                    {
                        data: 'comment',
                        name: 'comment'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });

            // Delete comment
            $(document).on('click', '.delete-comment', function(e) {
                e.preventDefault();
                if (confirm('Are you sure you want to delete this comment?')) {
                    var id = $(this).data('id');
                    $.ajax({
                        url: '/admin/comments/' + id,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            $('#comments-table').DataTable().ajax.reload();
                        }
                    });
                }
            });
        });
    </script>
@endsection
