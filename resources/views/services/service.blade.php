@extends('layouts.master')
@section('content')
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Table {{ $title }} </h6>
            <button class="btn btn-success btn-sm float-right" id="addBtn" data-toggle="modal" data-target="#modal"><i
                    class="fas fa-plus"></i> Add Service</button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Option</th>
                        </tr>
                    </thead>

                </table>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Service</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="id">
                        <form action="" id="formInput">
                            <div class="mb-3">
                                <label for="exampleFormControlTextarea1" class="form-label">Service Name</label>
                                <input type="text" class="form-control" id="name" placeholder="" name="name">
                                <span class="text-danger d-none name err_text"></span>
                            </div>
                            <div class="mb-3">
                                <label for="exampleFormControlTextarea1" class="form-label">Price</label>
                                <input type="number" class="form-control" id="price" placeholder="" name="price">
                                <span class="text-danger d-none price err_text"></span>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" id="createBtn" class="btn btn-primary">Save</button>
                    <button type="button" id="updateBtn" class="btn btn-primary d-none">Save changes</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            const table = $('#dataTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ url('service') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'price',
                        name: 'price'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
                columnDefs: [{
                    targets: 3,
                    render: function(data, type, row) {
                        var flag = `
                        <form class="d-flex justify-content-center">
                                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                <button type="submit" value =" ${row.id_services}" data-id="${row.id_services}" class="btn btn-warning editButton btn-sm" data-placement="top" title="Edit" data-toggle="modal"  data-target="#modal"><i class="fa fa-edit"></i></button>
                                <button type="submit" value =" ${row.id_services} " data-id="${row.id_services}" class="btn btn-danger deleteButton btn-sm" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash"></i></button>
                            </div>
                        </form>
                        `;
                        return flag;
                    }
                }]
            });
            $('#addBtn').on('click', function() {
                clearForm();
                $('#modal').modal('show');
                //change modal title
                $('#exampleModalLabel').text('Add Service');
            })

            function clearForm() {
                $('#name').val('');
                $('#price').val('');
                $('.err_text').addClass('d-none');
                $('#name').removeClass('is-invalid');
                $('#price').removeClass('is-invalid');
            }

            $(document).on('click', '#createBtn', function(e) {
                e.preventDefault();
                var data = $('#formInput').serialize();
                Swal.fire({
                    title: 'Save Data?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, save it!'
                }).then((result) => {
                    if (result.value) {
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        $.ajax({
                            url: "{{ url('service') }}",
                            type: "POST",
                            data: data,
                            success: function(rs) {
                                if (rs.status == 200) {
                                    table.ajax.reload();
                                    Swal.fire(
                                        'Success!',
                                        rs.message,
                                        'success'
                                    );
                                    $('#modal').modal('hide');
                                    clearForm();

                                }
                                if (rs.status == 406) {
                                    $.each(rs.errors, function(key, value) {
                                        $('.' + key).removeClass('d-none');
                                        $('.' + key).text(value);
                                        $('#' + key).addClass('is-invalid');
                                    });
                                }
                                if (rs.status == 501) {
                                    Swal.fire(
                                        'Failed!',
                                        rs.message,
                                        'error'
                                    )
                                }
                            },
                            error: function(data) {
                                Swal.fire(
                                    'Oops...',
                                    'Something went wrong!',
                                    'error'
                                )
                            }
                        });
                    }
                })
            });

            $(document).on('click', '.editButton', function(e) {
                e.preventDefault();
                var id = $(this).val();
                $('#createBtn').addClass('d-none');
                $('#updateBtn').removeClass('d-none');
                $('.modal-title').text('Edit Data');
                $.ajax({
                    url: "{{ url('service') }}" + '/' + id,
                    type: "GET",
                    dataType: "JSON",
                    success: function(rs) {
                        $('#modal').modal('show');
                        $('#id').val(rs.data.id_services);
                        $('#name').val(rs.data.name);
                        $('#price').val(rs.data.price);
                    },
                    error: function(data) {
                        console.log('Error:', data);
                    }
                });
            });
            $(document).on('click', '#updateBtn', function(e) {
                e.preventDefault();
                var id = $('#id').val();
                console.log(id);
                var data = $('#formInput').serialize();
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, update it!'
                }).then((result) => {

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "{{ url('service') }}" + '/' + id,
                        type: "PUT",
                        data: data,
                        dataType: "JSON",
                        success: function(rs) {
                            if (rs.status == 200) {
                                //close modal
                                $('#modal').modal('hide');
                                Swal.fire(
                                    'Updated!',
                                    rs.message,
                                    'success'
                                )
                                table.ajax.reload();
                            }
                            if (rs.status == 406) {
                                $('.err_text').removeClass('d-none');
                                $.each(rs.errors, function(key, value) {
                                    $('#' + key).addClass('is-invalid');
                                    $('.' + key).text(value);

                                });
                            }
                            if (rs.status == 501) {
                                Swal.fire(
                                    'Failed!',
                                    rs.message,
                                    'error'
                                )
                            }


                        },
                        error: function(data) {
                            console.log('Error:', data);
                        }
                    });

                })
            });

            $(document).on('click', '.deleteButton', function(e) {
                e.preventDefault();
                var id = $(this).val();
                console.log(id);
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.value) {
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        $.ajax({
                            url: "{{ url('service') }}" + '/' + id,
                            type: "DELETE",
                            dataType: "JSON",
                            success: function(rs) {
                                if (rs.status == 200) {
                                    Swal.fire(
                                        'Deleted!',
                                        rs.message,
                                        'success'
                                    )
                                    table.ajax.reload();
                                }
                                if (rs.status == 501) {
                                    Swal.fire(
                                        'Failed!',
                                        rs.message,
                                        'error'
                                    )
                                }
                                if (rs.status == 404) {
                                    Swal.fire(
                                        'Failed!',
                                        rs.message,
                                        'error'
                                    )
                                }


                            },
                            error: function(data) {
                                console.log('Error:', data);
                            }
                        });
                    }
                })
            })
        });
    </script>
@endsection
