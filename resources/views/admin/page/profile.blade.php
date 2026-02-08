@extends('admin.layouts.master')

@section('master')
    <div class="col-12">
        <div class="row g-4">
            <div class="col-lg-4 col-md-5">
                <div class="custom--card h-auto">
                    <div class="card-body">
                        <div class="profile-card">
                            <div class="profile-card__img">
                                <img src="{{ getImage(getFilePath('adminProfile') . '/' . $admin->image, getFileSize('adminProfile'), true) }}" alt="image">
                            </div>
                            <div class="profile-card__txt">
                                <h3 class="profile-card__title">{{ $admin->name }}</h3>
                                <span class="badge badge--secondary">@lang('Supper Admin')</span>
                            </div>
                            <table class="table table-borderless table--striped">
                                <tbody>
                                    <tr>
                                        <td class="fw-bold">@lang('Username'):</td>
                                        <td>{{ $admin->username }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">@lang('Email'):</td>
                                        <td>{{ $admin->email }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">@lang('Contact'):</td>
                                        <td>{{ $admin->contact }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">@lang('Address'):</td>
                                        <td>{{ __($admin->address) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">@lang('Status'):</td>
                                        <td><span class="badge badge--success">@lang('Active')</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 col-md-7">
                <nav>
                    <div class="custom--tab nav nav-tabs mb-3" role="tablist">
                        <button class="nav-link active" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="true"><i class="ti ti-user-circle"></i> @lang('Profile')</button>
                        <button class="nav-link" id="nav-password-tab" data-bs-toggle="tab" data-bs-target="#nav-password" type="button" role="tab" aria-controls="nav-password" aria-selected="false"><i class="ti ti-password-user"></i> @lang('Password')</button>
                    </div>
                </nav>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab" tabindex="0">
                        <div class="custom--card">
                            <form action="" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="card-header">
                                    <h3 class="title">@lang('Update Profile')</h3>
                                </div>
                                <div class="card-body">
                                    <div class="row g-lg-4 g-3">
                                        <div class="col-lg-6">
                                            <label class="form--label required">@lang('Name')</label>
                                            <input type="text" class="form--control" name="name" value="{{ $admin->name }}" required>
                                        </div>
                                        <div class="col-lg-6">
                                            <label class="form--label required">@lang('Username')</label>
                                            <input type="text" class="form--control" name="username" value="{{ $admin->username }}" required>
                                        </div>
                                        <div class="col-lg-6">
                                            <label class="form--label required">@lang('Email')</label>
                                            <input type="email" class="form--control" name="email" value="{{ $admin->email }}" required>
                                        </div>
                                        <div class="col-lg-6">
                                            <label class="form--label required">@lang('Contact')</label>
                                            <input type="text" class="form--control" name="contact" value="{{ $admin->contact }}" required>
                                        </div>
                                        <div class="col-12">
                                            <label class="form--label required">@lang('Address')</label>
                                            <textarea class="form--control" name="address" required>{{ $admin->address }}</textarea>
                                        </div>
                                        <div class="col-12">
                                            <label class="form--label">@lang('Image')</label>
                                            <input type="file" class="form--control" name="image">
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="d-flex justify-content-center">
                                        <button type="submit" class="btn btn--base px-4">@lang('Submit')</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="nav-password" role="tabpanel" aria-labelledby="nav-password-tab" tabindex="0">
                        <div class="custom--card">
                            <form action="{{ route('admin.password.update') }}" method="POST">
                                @csrf
                                <div class="card-header">
                                    <h3 class="title">@lang('Change Password')</h3>
                                </div>
                                <div class="card-body">
                                    <div class="row g-lg-4 g-3">
                                        <div class="col-12">
                                            <label for="currentPassword" class="form--label required">@lang('Current Password')</label>
                                            <div class="input--group">
                                                <input type="password" id="currentPassword" class="form--control" name="current_password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" required autofocus>
                                                <button type="button" class="btn btn--icon btn--secondary show-password-btn" data-input="currentPassword"><i class="ti ti-eye"></i></button>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <label for="newPassword" class="form--label required">@lang('New Password')</label>
                                            <div class="input--group">
                                                <input type="password" id="newPassword" class="form--control @if($setting->strong_pass) secure-password @endif" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" required>
                                                <button type="button" class="btn btn--icon btn--secondary show-password-btn" data-input="newPassword"><i class="ti ti-eye"></i></button>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <label for="confirmPassword" class="form--label required">@lang('Confirm Password')</label>
                                            <div class="input--group">
                                                <input type="password" id="confirmPassword" class="form--control" name="password_confirmation" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" required>
                                                <button type="button" class="btn btn--icon btn--secondary show-password-btn" data-input="confirmPassword"><i class="ti ti-eye"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="d-flex justify-content-center">
                                        <button type="submit" class="btn btn--base px-4">@lang('Submit')</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@if ($setting->strong_pass)
    @push('page-style-lib')
        <link rel="stylesheet" href="{{ asset('assets/universal/css/strongPassword.css') }}">
    @endpush

    @push('page-script-lib')
        <script src="{{ asset('assets/universal/js/strongPassword.js') }}"></script>
    @endpush
@endif
