@extends('layouts.master')
@section('content')
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Table {{ $title }} </h6>
            <button class="btn btn-success btn-sm float-right" id="addBtn" data-toggle="modal" data-target="#modal"><i
                    class="fas fa-plus"></i> Add Mechanic</button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Email</th>
                            <th>Name</th>
                            <th>Phone</th>
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
                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="id">
                        <form action="" id="formInput">
                            <div class="mb-3">
                                <label for="exampleFormControlTextarea1" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" placeholder="" name="name">
                                <span class="text-danger d-none name err_text"></span>
                            </div>
                            <div class="mb-3">
                                <label for="exampleFormControlTextarea1" class="form-label">Phone</label>
                                <input type="text" class="form-control" id="phone" placeholder="" name="phone">
                                <span class="text-danger d-none phone err_text"></span>
                            </div>
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Email address</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="">
                                <span class="text-danger d-none email err_text"></span>
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
                ajax: "{{ url('mechanic') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'phone',
                        name: 'phone'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
                columnDefs: [{
                    targets: 4,
                    render: function(data, type, row) {
                        var flag = `
                        <form class="d-flex justify-content-center">
                                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                <button type="submit" value =" ${row.id_mechanics}" data-id="${row.id_mechanics}" class="btn btn-warning editButton btn-sm" data-placement="top" title="Edit" data-toggle="modal"  data-target="#modal"><i class="fa fa-edit"></i></button>
                                <button type="submit" value =" ${row.id_mechanics} " data-id="${row.id_mechanics}" class="btn btn-danger deleteButton btn-sm" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash"></i></button>
                            </div>
                        </form>
                        `;
                        return flag;
                    }
                }]
            });

            function clearForm() {
                $('.err_text').addClass('d-none');
                $('#name').val('');
                $('#phone').val('');
                $('#email').val('');
                $('#name').removeClass('is-invalid');
                $('#phone').removeClass('is-invalid');
                $('#email').removeClass('is-invalid');

            }

            $('#addBtn').click(function() {
                //clear form
                clearForm();
                //change modal title
                $('#exampleModalLabel').text('Add Mechanic');
            });


            $(document).on('click', '#createBtn', function(e) {
                e.preventDefault();
                var formData = $('#formInput').serialize();
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
                            url: "{{ url('mechanic') }}",
                            type: "POST",
                            data: formData,
                            success: function(rs) {
                                console.log(rs);
                                if (rs.status == 201) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Success',
                                        text: rs.message,
                                    });
                                    table.ajax.reload();
                                    clearForm();
                                    $('#modal').modal('hide');
                                }
                                if (rs.status == 422) {
                                    $('.err_text').removeClass('d-none');
                                    $.each(rs.errors, function(key, value) {
                                        $('.' + key).text(value);
                                        $('#' + key).addClass('is-invalid');
                                    });
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
            })

            $(document).on('click', '.editButton', function(e) {
                e.preventDefault();
                var id = $(this).val();
                //hidden create button
                $('#createBtn').addClass('d-none');
                //show update button
                $('#updateBtn').removeClass('d-none');
                //change modal title
                $('.modal-title').text('Edit Data');
                $.ajax({
                    url: "{{ url('mechanic') }}" + '/' + id,
                    type: "GET",
                    dataType: "JSON",
                    success: function(rs) {
                        console.log(rs);
                        $('#modal').modal('show');
                        $('#email').val(rs.data.user.email);
                        $('#name').val(rs.data.name);
                        $('#phone').val(rs.data.phone);
                        $('#id').val(rs.data.id_mechanics);
                    },
                    error: function(data) {
                        console.log('Error:', data);
                    }
                });
            });
            $(document).on('click', '#updateBtn', function(e) {
                e.preventDefault();
                var id = $('#id').val();
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
                        url: "{{ url('mechanic') }}" + '/' + id,
                        type: "PUT",
                        data: data,
                        dataType: "JSON",
                        success: function(rs) {
                            if (rs.status == 200) {
                                //close modal
                                $('#modal').modal('hide');
                                clearForm();
                                Swal.fire(
                                    'Updated!',
                                    rs.message,
                                    'success'
                                )
                                table.ajax.reload();
                            }
                            if (rs.status == 422) {
                                $('.err_text').removeClass('d-none');
                                $.each(rs.errors, function(key, value) {
                                    $('#' + key).addClass('is-invalid');
                                    $('.' + key).text(value);

                                });
                            }
                            if (rs.status == 501) {
                                Swal.fire(
                                    'Oops...',
                                    rs.errors,
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
                            url: "{{ url('mechanic') }}" + '/' + id,
                            type: "DELETE",
                            dataType: "JSON",
                            success: function(rs) {
                                console.log(rs);
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
                                        'Oops...',
                                        rs.errors,
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
