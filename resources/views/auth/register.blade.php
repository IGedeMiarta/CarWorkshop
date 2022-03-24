@extends('auth.master')
@section('content')
    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="">
                            {{-- <div class="col-lg-6 d-none d-lg-block "></div> --}}
                            <div class="col-lg-12">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Create an Account!</h1>
                                    </div>
                                    <form class="user" id="form-register">
                                        <div class="form-group row">
                                            <div class="col-sm-6 mb-3 mb-sm-0">
                                                <input type="text" class="form-control form-control-user" id="name"
                                                    placeholder="Name" name="name">
                                                <span class="text-danger d-none name err_text"></span>
                                            </div>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control form-control-user " id="phone"
                                                    placeholder="Phone Number" name="phone">
                                                <span class="text-danger d-none phone err_text"></span>

                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <input type="email" class="form-control form-control-user " id="email"
                                                placeholder="Email Address" name="email">
                                            <span class="text-danger d-none email err_text"></span>

                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-6 mb-3 mb-sm-0">
                                                <input type="password" class="form-control form-control-user " id="password"
                                                    placeholder="Password" name="password">
                                                <span class="text-danger d-none password err_text"></span>
                                            </div>
                                            <div class="col-sm-6">
                                                <input type="password" class="form-control form-control-user "
                                                    id="password_confirmation" placeholder="Repeat Password"
                                                    name="password_confirmation">
                                                <span class="text-danger d-none password_confirmation err_text"></span>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-user btn-block"> Register
                                            Account</button>

                                        <hr>

                                    </form>
                                    <div class="text-center">
                                        <a class="small" href="{{ route('login') }}">Already have an account?
                                            Login!</a>
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
                $('#name').removeClass('is-invalid');
                $('#phone').removeClass('is-invalid');
                $('#email').removeClass('is-invalid');
                $('#password').removeClass('is-invalid');
                $('#password_confirmation').removeClass('is-invalid');
                $('.err_text').addClass('d-none');
            }
            $('#form-register').submit(function(e) {
                e.preventDefault();
                clearForm();
                var formData = $(this).serialize();
                console.log(formData);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: 'POST',
                    url: "{{ route('register.post') }}",
                    data: formData,
                    success: function(rs) {
                        console.log(rs);
                        if (rs.status == 422) {
                            $.each(rs.errors, function(key, value) {
                                $('#' + key).addClass('is-invalid');
                                $('.' + key).removeClass('d-none');
                                $('.' + key).text(value);
                            });
                        }
                        if (rs.status == 201) {
                            swal.fire({
                                title: 'Success!',
                                text: 'Register Success, Please Login',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(function() {
                                window.location.href = "{{ route('login') }}";
                            });
                        }
                        if (rs.status == 501) {

                        }
                    }
                });
            });
        });
    </script>
@endsection
