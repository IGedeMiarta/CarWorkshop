@extends('auth.master')
@section('content')
    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            {{-- <div class="col-lg-6 d-none d-lg-block "></div> --}}
                            <div class="col-md-12">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Login </h1>
                                    </div>
                                    <div class="alert alert-danger err_info d-none" role="alert">
                                    </div>
                                    <form class="user" id="form-login">
                                        <div class="form-group">
                                            <input type="email" class="form-control form-control-user err_email"
                                                id="exampleInputEmail" aria-describedby="emailHelp"
                                                placeholder="Enter Email Address..." name="email" id="email">
                                            <span class="text-danger d-none email err_text"></span>
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user err_password"
                                                id="exampleInputPassword" placeholder="Password" name="password"
                                                id="password">
                                            <span class="text-danger d-none password err_text"></span>
                                        </div>

                                        <button type="submit" class="btn btn-primary btn-user btn-block"
                                            id="login">Login</button>
                                        <hr>
                                    </form>

                                    <div class="text-center">
                                        <a class="small" href="{{ route('register') }}">Create an Account!</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            function clearForm() {
                $('#email').removeClass('is-invalid');
                $('#password').removeClass('is-invalid');
                $('.err_text').addClass('d-none');
                $('.err_info').addClass('d-none');
            }

            $('#form-login').submit(function(e) {
                e.preventDefault();
                clearForm();
                var formData = $(this).serialize();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "{{ route('login.post') }}",
                    type: 'POST',
                    data: formData,
                    success: function(rs) {
                        console.log(rs);
                        if (rs.status == 422) {
                            $.each(rs.errors, function(key, value) {
                                $('.err_' + key).addClass('is-invalid');
                                $('.' + key).removeClass('d-none');
                                $('.' + key).text(value);
                            });
                        }
                        if (rs.status == 406) {
                            $('.err_info').removeClass('d-none');
                            $('.err_info').text(rs.errors);
                        }
                        if (rs.status == 200) {
                            window.location.href = "{{ route('dashboard') }}";
                        }
                    }
                })
            });

        })
    </script>
@endsection
