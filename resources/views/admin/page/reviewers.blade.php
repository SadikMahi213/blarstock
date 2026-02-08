@extends('admin.layouts.master')

    @section('master')
        <div class="col-12">
            <div class="table-responsive scroll">
                <table class="table table--striped table-borderless table--responsive--sm">
                    <thead>
                        <tr>
                            <th>@lang('Name')</th>
                            <th>@lang('Username')</th>
                            <th>@lang('Email')</th>
                            <th>@lang('Total Approved')</th>
                            <th>@lang('Total Rejected')</th>
                            <th>@lang('Status')</th>
                            <th>@lang('Action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($reviewers as $reviewer)
                            <tr>
                                <td>
                                    <div class="table-card-with-image">
                                        <div class="table-card-with-image__img">
                                            <img src="{{ getImage(getFilePath('reviewerProfile').'/'.$reviewer->image, getFileSize('reviewerProfile'), true) }}" alt="Image">
                                        </div>
                                        <div class="table-card-with-image__content">
                                            <p class="fw-semibold" title="{{ __($reviewer->name) }}">{{ __(strLimit($reviewer->name, 20)) }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $reviewer->username }}</td>
                                <td title="{{ $reviewer->email }}">{{ strLimit($reviewer->email, 30) }}</td>
                                <td><span class="badge badge--success">{{ formatNumber($reviewer->approved_images_count) }}</span></td>
                                <td><span class="badge badge--danger">{{ formatNumber($reviewer->rejected_images_count) }}</span></td>
                                <td>@php echo $reviewer->statusBadge; @endphp</td>
                                <td>
                                    @if ($reviewer->status == ManageStatus::ACTIVE)
                                        <div class="d-flex justify-content-end gap-2">
                                            <button class="btn btn--sm btn-outline--base editBtn" 
                                                data-resource="{{ $reviewer }}" 
                                                data-action="{{ route('admin.reviewer.store', $reviewer->id) }}">
                                                <i class="ti ti-edit"></i> @lang('Edit')
                                            </button>

                                            <div class="custom--dropdown">
                                                <button class="btn btn--icon btn--sm btn--base" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="ti ti-dots-vertical"></i></button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a href="{{ route('admin.reviewer.login', $reviewer->id) }}" class="dropdown-item text--info" target="_blank"><span class="dropdown-icon"><i class="ti ti-login-2"></i></span> @lang('Login as Reviewer')</a>
                                                    </li>
                                                    @if ($reviewer->status)
                                                        <li>
                                                            <button type="button" class="dropdown-item text--warning decisionBtn" 
                                                                data-question="@lang('Are you confirming the inactivation of this reviewer')?" 
                                                                data-action="{{ route('admin.reviewer.status', $reviewer->id) }}">
                                                                <span class="dropdown-icon"><i class="ti ti-ban"></i></span> @lang('Inactive')
                                                            </button>
                                                        </li>
                                                    @else
                                                        <li>
                                                            <button type="button" class="dropdown-item text--success decisionBtn" 
                                                                data-question="@lang('Are you confirming the activation of this reviewer')?" 
                                                                data-action="{{ route('admin.reviewer.status', $reviewer->id) }}">
                                                                <span class="dropdown-icon"><i class="ti ti-circle-check"></i></span> @lang('Active')
                                                            </button>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </div>
                                    @else
                                        <div>
                                            <button class="btn btn--sm btn-outline--base editBtn" 
                                                data-resource="{{ $reviewer }}" 
                                                data-action="{{ route('admin.reviewer.store', $reviewer->id) }}">
                                                <i class="ti ti-edit"></i> @lang('Edit')
                                            </button>
            
                                            @if ($reviewer->status)
                                                <button type="button" class="btn btn--sm btn--warning decisionBtn" 
                                                    data-question="@lang('Are you confirming the inactivation of this reviewer')?" 
                                                    data-action="{{ route('admin.reviewer.status', $reviewer->id) }}">
                                                    <i class="ti ti-ban"></i> @lang('Inactive')
                                                </button>
                                            @else
                                                <button type="button" class="btn btn--sm btn--success decisionBtn" 
                                                    data-question="@lang('Are you confirming the activation of this reviewer')?" 
                                                    data-action="{{ route('admin.reviewer.status', $reviewer->id) }}">
                                                    <i class="ti ti-circle-check"></i> @lang('Active')
                                                </button>
                                            @endif
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            @include('partials.noData')
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if ($reviewers->hasPages())
                {{ paginateLinks($reviewers) }}
            @endif
        </div>

        {{-- Add Modal --}}
        <div class="modal custom--modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title" id="addModalLabel">@lang('New Reviewer')</h2>
                        <button type="button" class="btn btn--sm btn--icon btn-outline--secondary modal-close" data-bs-dismiss="modal" aria-label="Close"><i class="ti ti-x"></i></button>
                    </div>
                    <form action="{{ route('admin.reviewer.store') }}" method="POST">
                        @csrf
        
                        <div class="modal-body text-center">
                            <div class="row g-3 align-items-center">
                                <div class="col-12">
                                    <div class="row gy-2">
                                        <div class="col-sm-4">
                                            <label class="col-form--label required">@lang('Name')</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" class="form--control" name="name" required>
                                        </div>
                                    </div>
                                </div>
        
                                <div class="col-12">
                                    <div class="row gy-2">
                                        <div class="col-sm-4">
                                            <label class="col-form--label required">@lang('Username')</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" class="form--control" name="username" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="row gy-2">
                                        <div class="col-sm-4">
                                            <label class="col-form--label required">@lang('Email')</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="email" class="form--control" name="email" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="row gy-2">
                                        <div class="col-sm-4">
                                            <label class="col-form--label required">@lang('Password')</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" class="form--control" name="password" required>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
        
                        <div class="modal-footer d-flex justify-content-end gap-2">
                            <button type="button" data-bs-dismiss="modal" class="btn btn--sm btn--secondary">@lang('Close')</button>
                            <button class="btn btn--sm btn--base" type="submit">@lang('Add')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Add Modal --}}
        <div class="modal custom--modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title" id="updatedModalLabel">@lang('Update Reviewer')</h2>
                        <button type="button" class="btn btn--sm btn--icon btn-outline--secondary modal-close" data-bs-dismiss="modal" aria-label="Close"><i class="ti ti-x"></i></button>
                    </div>
                    <form method="POST">
                        @csrf
        
                        <div class="modal-body text-center">
                            <div class="row g-3 align-items-center">
        
                                <div class="col-12">
                                    <div class="row gy-2">
                                        <div class="col-sm-4">
                                            <label class="col-form--label required">@lang('Name')</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" class="form--control" name="name" required>
                                        </div>
                                    </div>
                                </div>
        
                                <div class="col-12">
                                    <div class="row gy-2">
                                        <div class="col-sm-4">
                                            <label class="col-form--label required">@lang('Username')</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" class="form--control" name="username" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="row gy-2">
                                        <div class="col-sm-4">
                                            <label class="col-form--label required">@lang('Email')</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="email" class="form--control" name="email" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="row gy-2">
                                        <div class="col-sm-4">
                                            <label class="col-form--label required">@lang('Password')</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" minlength="6" class="form--control" name="password">
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
        
                        <div class="modal-footer d-flex justify-content-end gap-2">
                            <button type="button" data-bs-dismiss="modal" class="btn btn--sm btn--secondary">@lang('Close')</button>
                            <button class="btn btn--sm btn--base" type="submit">@lang('Update')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <x-decisionModal />
    @endsection

    @push('breadcrumb')
        <x-searchForm placeholder="Name or Username" />
        <button type="button" class="btn btn--sm btn--base addBtn"><i class="ti ti-circle-plus"></i> @lang('Add New')</button>
    @endpush

    @push('page-script')
        <script>
            (function($) {
                'use strict';

                $('.addBtn').on('click', function() {
                    let modal = $('#addModal');

                    modal.modal('show');
                });

                $('.editBtn').on('click', function() {
                    let modal       = $('#updateModal');
                    let actionRoute = $(this).data('action');
                    let resource    = $(this).data('resource');

                    modal.find('[name=name]').val(resource.name);
                    modal.find('[name=username]').val(resource.username);
                    modal.find('[name=email]').val(resource.email);
                    modal.find('form').attr('action', actionRoute);

                    modal.modal('show');
                });
            })(jQuery);
        </script>
    @endpush