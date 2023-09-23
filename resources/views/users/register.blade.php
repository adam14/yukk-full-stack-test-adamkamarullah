@extends('layouts.login')

@section('content')
<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
        </div>
        <div class="content-body">
            <div class="auth-wrapper auth-basic px-2">
                <div class="auth-inner my-2">
                    <!-- Register basic -->
                    <div class="card mb-0">
                        <div class="card-body">
                            <a href="{{ route('index') }}" class="brand-logo">
                                <h2 class="brand-text text-primary ms-1">Yukk Test Program</h2>
                            </a>

                            @if(session()->has('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <div class="alert-body">
                                        {{ session('success') }}
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @elseif(session()->has('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <div class="alert-body">
                                        {{ session('error') }}
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            <h4 class="card-title mb-1">Adventure starts here 🚀</h4>
                            <p class="card-text mb-2">Make your app management easy and fun!</p>

                            <form class="auth-register-form mt-2" action="{{ route('registerSave') }}" method="POST" enctype="multipart/form-data"> @csrf
                                <div class="mb-1">
                                    <label for="register-name" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="register-name" name="name" placeholder="John Doe" aria-describedby="register-name" tabindex="1" autofocus />
                                </div>
                                <div class="mb-1">
                                    <label for="register-username" class="form-label">Username</label>
                                    <input type="text" class="form-control" id="register-username" name="username" placeholder="johndoe" aria-describedby="register-username" tabindex="2" />
                                </div>
                                <div class="mb-1">
                                    <label for="register-email" class="form-label">Email</label>
                                    <input type="text" class="form-control" id="register-email" name="email" placeholder="john@example.com" aria-describedby="register-email" tabindex="3" />
                                </div>

                                <div class="mb-1">
                                    <label for="register-password" class="form-label">Password</label>

                                    <div class="input-group input-group-merge form-password-toggle">
                                        <input type="password" class="form-control form-control-merge" id="register-password" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="register-password" tabindex="4" />
                                        <span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span>
                                    </div>
                                </div>
                                <div class="mb-1">
                                    <label for="register-password" class="form-label">Confirm Password</label>

                                    <div class="input-group input-group-merge form-password-toggle">
                                        <input type="password" class="form-control form-control-merge" id="register-password" name="confirm_password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="register-password" tabindex="5" />
                                        <span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span>
                                    </div>
                                </div>
                                <button class="btn btn-primary w-100" tabindex="5">Sign up</button>
                            </form>

                            <p class="text-center mt-2">
                                <span>Already have an account?</span>
                                <a href="{{ route('index') }}">
                                    <span>Sign in instead</span>
                                </a>
                            </p>
                        </div>
                    </div>
                    <!-- /Register basic -->
                </div>
            </div>

        </div>
    </div>
</div>
@endsection