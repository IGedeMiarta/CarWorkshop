@extends('layouts.master')
@section('content')
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Table {{ $title }} </h6>
            <button class="btn btn-success btn-sm float-right" id="addBtn" data-toggle="modal" data-target="#modal"><i
                    class="fas fa-plus"></i> Add Repair</button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Entry</th>
                            <th>Owner</th>
                            <th>Mechanic</th>
                            <th>Service</th>
                            <th>Note</th>
                            <th>Status</th>
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
                            <div class="mb-3" id="form-owner">
                                <label for="exampleFormControlTextarea1" class="form-label">Owner</label>
                                <select name="owner" id="owner" class="select2">
                                    <option value="" disabled selected>-- Select Owner --</option>
                                    @foreach ($owner as $item)
                                        <option value="{{ $item->id_car_owners }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger d-none name err_text"></span>
                            </div>
                            <div class="mb-3" id="form-mechanic">
                                <label for="exampleFormControlTextarea1" class="form-label">Mechanic</label>
                                <select name="mechanic" id="mechanic" class="select2">
                                    <option value="" disabled selected>-- Select Mechanic --</option>
                                    @foreach ($mechanic as $item)
                                        <option value="{{ $item->id_mechanics }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger d-none name err_text"></span>
                            </div>
                            <div class="mb-3" id="form-service">
                                <label for="exampleFormControlTextarea1" class="form-label">Service</label>
                                <select class="select2" name="service[]" id="service" multiple="multiple">
                                    <option value="" disabled>-- Select Service --</option>
                                    @foreach ($service as $item)
                                        <option value="{{ $item->id_services }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger d-none name err_text"></span>
                            </div>
                            <div class="mb-3">
                                <label for="exampleFormControlTextarea1" class="form-label">Note</label>
                                <textarea name="note" id="note" cols="30" rows="5" class="form-control"></textarea>
                                <span class="text-danger d-none name err_text"></span>
                            </div>
                            <div class="mb-3">
                                <label for="exampleFormControlTextarea1" class="form-label">Status</label>
                                <select name="status" id="status" class="select2">
                                    {{-- <option value="">-- Select Status --</option> --}}
                                    @foreach ($status as $item)
                                        <option value="{{ $item->id_status }}">{{ $item->status }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger d-none name err_text"></span>
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
                ajax: "{{ url('car-repair') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'car_entry',
                        name: 'car_entry'
                    },
                    {
                        data: 'owner',
                        name: 'owner'
                    },
                    {
                        data: 'mechanic',
                        name: 'mechanic'
                    },
                    {
                        data: 'service',
                        name: 'service'
                    },
                    {
                        data: 'note',
                        name: 'note'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
                columnDefs: [{
                    targets: [0, 1, 2, 3, 4, 5, 6],
                    className: 'text-center'
                }, {
                    targets: [7],
                    render: function(data, type, row) {
                        var flag = `
                        <form class="d-flex justify-content-center">
                                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                <button type="submit" value =" ${row.id_repairs}" data-status="${row.status_id}" data-id="${row.id_repairs}" class="btn btn-warning editButton btn-sm" data-placement="top" title="Edit" data-toggle="modal"  data-target="#modal"><i class="fa fa-edit"></i></button>
                             </div>
                        </form>
                        `;
                        return flag;
                    },
                }, {
                    targets: 4,
                    render: function(data, type, row) {
                        return data;
                    }
                }],
            });

            function clearForm() {
                $('.err_text').addClass('d-none');
                $('#formInput').trigger('reset');
                $('#createBtn').removeClass('d-none');
                $('#updateBtn').addClass('d-none');
                $('.err_text').html('');



            }

            $('#addBtn').click(function() {
                //clear form
                clearForm();
                //change modal title
                $('#exampleModalLabel').text('Add New Car Repair');
                $('#form-owner').removeClass('d-none');
                $('#form-mechanic').removeClass('d-none');
                $('#form-service').removeClass('d-none');

            });


            $(document).on('click', '#createBtn', function(e) {
                e.preventDefault();
                var formData = $('#formInput').serialize();

                console.log(formData);
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
                            url: "{{ url('car-repair') }}",
                            type: "POST",
                            data: formData,
                            success: function(rs) {
                                console.log(rs);
                                if (rs.status == 200) {
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
                                if (rs.status == 501) {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: rs.errors,
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
                var status = $(this).data('status');
                console.log(status);
                //hidden create button
                $('#createBtn').addClass('d-none');
                //show update button
                $('#updateBtn').removeClass('d-none');
                //change modal title
                $('.modal-title').text('Update Car Repair');

                // hidden input
                $('#form-owner').addClass('d-none');
                $('#form-mechanic').addClass('d-none');
                $('#form-service').addClass('d-none');
                $('#id').val(id);
                $('#status').val(status);



            });
            $(document).on('click', '#updateBtn', function(e) {
                e.preventDefault();
                var id = $('#id').val();
                console.log(id);
                var data = {
                    note: $('#note').val(),
                    status: $('#status').val(),
                }
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
                        url: "{{ url('car-repair') }}" + '/' + id,
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

        });
    </script>
@endsection
