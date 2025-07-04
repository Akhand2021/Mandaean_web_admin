@extends('layouts.app')
@section('title', 'Post')
@section('pagetitle', 'Post')
{{-- @section('sort_name', $data['sort_name']) --}}
@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">Post Management</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                </ol>
            </nav>
        </div>
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-striped" id="posts-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>User</th>
                                    <th>Content</th>
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
    </div>
    </div>
    </div>


    <script>
        $(function() {
            $('#posts-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin.posts.index') }}',
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'userName',
                        name: 'userName'
                    },
                    {
                        data: 'content',
                        name: 'content'
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

            // Delete post
            $(document).on('click', '.delete-post', function(e) {
                e.preventDefault();
                if (confirm('Are you sure you want to delete this post?')) {
                    var id = $(this).data('id');
                    $.ajax({
                        url: '/admin/posts/' + id,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            $('#posts-table').DataTable().ajax.reload();
                        }
                    });
                }
            });
        });
    </script>



@endsection
