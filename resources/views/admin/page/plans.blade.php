@extends('admin.layouts.master')

    @section('master')
        <div class="col-12">
            <div class="table-responsive scroll">
                <table class="table table--striped table-borderless table--responsive--sm">
                    <thead>
                        <tr>
                            <th>@lang('Name')</th>
                            <th>@lang('Title')</th>
                            <th>@lang('Duration')</th>
                            <th>@lang('Price')</th>
                            <th>@lang('Download Limit')</th>
                            <th>@lang('Status')</th>
                            <th>@lang('Action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($plans as $plan)
                            <tr>
                                <td>
                                    <div class="table-card-with-image">
                                        <div class="table-card-with-image__img bg--secondary">
                                            <img src="{{ getImage(getFilePath('plans').'/'.$plan->image, getFileSize('plans')) }}" alt="{{ __($plan->name) }}">
                                        </div>
                                        <div class="table-card-with-image__content">
                                            <p class="fw-semibold">{{ __($plan->name) }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td title="{{ __($plan->title) }}">{{ __(strLimit($plan->title, 25)) }}</td>
                                <td>@php echo $plan->durationBadge; @endphp</td>
                                <td>{{ showAmount($plan->price) }} {{ __($setting->site_cur) }}</td>
                                <td>
                                    <p>
                                        @if ($plan->daily_limit == ManageStatus::UNLIMITED_DOWNLOAD)
                                            @lang('Unlimited Every Day') 
                                        @else
                                            {{ $plan->daily_limit }} @lang('Every Day')
                                        @endif
                                    </p>
                                    @if ($plan->monthly_limit != 0)
                                        <p>
                                            @if ($plan->monthly_limit == ManageStatus::UNLIMITED_DOWNLOAD)
                                                @lang('Unlimited Every Month') 
                                            @else
                                                {{ $plan->monthly_limit }} @lang('Every Month')
                                            @endif
                                        </p>
                                    @endif
                                </td>
                                <td>@php echo $plan->statusBadge; @endphp</td>
                                <td>
                                    <div>
                                        <button class="btn btn--sm btn-outline--base editBtn" 
                                            data-resource="{{ $plan }}"
                                            data-action="{{ route('admin.plan.store', $plan->id) }}"
                                            data-image="{{ getImage(getFilePath('plans'). '/' . $plan->image, getFileSize('plans')) }}">
                                            <i class="ti ti-edit"></i> @lang('Edit')
                                        </button>
        
                                        @if ($plan->status)
                                            <button type="button" class="btn btn--sm btn--warning decisionBtn" 
                                                data-question="@lang('Are you confirming the inactivation of this plan')?" 
                                                data-action="{{ route('admin.plan.status', $plan->id) }}">
                                                <i class="ti ti-ban"></i> @lang('Inactive')
                                            </button>
                                        @else
                                            <button type="button" class="btn btn--sm btn--success decisionBtn" 
                                                data-question="@lang('Are you confirming the activation of this plan')?" 
                                                data-action="{{ route('admin.plan.status', $plan->id) }}">
                                                <i class="ti ti-circle-check"></i> @lang('Active')
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            @include('partials.noData')
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if ($plans->hasPages())
                {{ paginateLinks($plans) }}
            @endif
        </div>

        {{-- Add Modal --}}
        <div class="modal custom--modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title" id="addModalLabel">@lang('New Plan')</h2>
                        <button type="button" class="btn btn--sm btn--icon btn-outline--secondary modal-close" data-bs-dismiss="modal" aria-label="Close"><i class="ti ti-x"></i></button>
                    </div>
                    <form action="{{ route('admin.plan.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
        
                        <div class="modal-body text-center">
                            <div class="row g-3 align-items-center">

                                <div class="col-12">
                                    <div class="row gy-2">
                                        <div class="col-sm-4">
                                            <label class="col-form--label required">@lang('Image')</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="upload__img mb-2">
                                                <label for="addimage" class="upload__img__btn" title="@lang('Image')"><i class="ti ti-camera"></i></label>
        
                                                <input type="file" id="addimage" class="image-upload" name="image" accept=".jpeg, .jpg, .png" required>
        
                                                <label for="addimage" class="upload__img-preview image-preview">
                                                    <img src="{{ getImage(getFilePath('plans'), getFileSize('plans')) }}" alt="image">
                                                </label>
        
                                                <button type="button" class="btn btn--sm btn--icon btn--danger custom-file-input-clear d-none"><i class="ti ti-circle-x"></i></button>
                                            </div>
                                            <label class="text-center small">@lang('Supported files'):
                                                <span class="fw-semibold text--base">@lang('jpeg'), @lang('jpg'), @lang('png').</span>
                                                @lang('Image size') <span class="fw-semibold text--base">{{ getFileSize('plans') }}@lang('px').</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>

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
                                            <label class="col-form--label required">@lang('Title')</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" class="form--control" name="title" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="row gy-2">
                                        <div class="col-sm-4">
                                            <label class="col-form--label required">@lang('Duration')</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <select class="form--control form-select" name="plan_duration" required>
                                                <option value="" selected disabled>@lang('Select One')</option>
                                                <option value="{{ ManageStatus::DAILY_PLAN }}">@lang('Day')</option>
                                                <option value="{{ ManageStatus::WEEKLY_PLAN }}">@lang('Week')</option>
                                                <option value="{{ ManageStatus::MONTHLY_PLAN }}">@lang('Month')</option>
                                                <option value="{{ ManageStatus::QUARTERLY_PLAN }}">@lang('Quarter Year') (@lang('3 Month'))</option>
                                                <option value="{{ ManageStatus::SEMI_ANNUAL_PLAN }}">@lang('Semi Annual') (@lang('6 Month'))</option>
                                                <option value="{{ ManageStatus::ANNUAL_PLAN }}">@lang('Annual')</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="row gy-2">
                                        <div class="col-sm-4">
                                            <label class="col-form--label required">@lang('Price')</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="input--group">
                                                <input type="number" step="any" min="0" class="form--control" name="price" required>
                                                <span class="input-group-text">{{ __($setting->site_cur) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="row gy-2">
                                        <div class="col-sm-4">
                                            <label class="col-form--label required">@lang('Daily Download Limit')</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="number" min="-1" class="form--control" name="daily_limit" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 d-none" id="monthlyDownloadDiv">
                                    <div class="row gy-2">
                                        <div class="col-sm-4">
                                            <label class="col-form--label required">@lang('Monthly Download Limit')</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="number" min="-1" class="form--control" name="monthly_limit">
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
                        <h2 class="modal-title" id="updatedModalLabel">@lang('Update Plan')</h2>
                        <button type="button" class="btn btn--sm btn--icon btn-outline--secondary modal-close" data-bs-dismiss="modal" aria-label="Close"><i class="ti ti-x"></i></button>
                    </div>
                    <form method="POST" enctype="multipart/form-data">
                        @csrf
        
                        <div class="modal-body text-center">
                            <div class="row g-3 align-items-center">

                                <div class="col-12">
                                    <div class="row gy-2">
                                        <div class="col-sm-4">
                                            <label class="col-form--label required">@lang('Image')</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="upload__img mb-2">
                                                <label for="updateimage" class="upload__img__btn" title="@lang('Image')"><i class="ti ti-camera"></i></label>
        
                                                <input type="file" id="updateimage" class="image-upload" name="image" accept=".jpeg, .jpg, .png">
        
                                                <label for="updateimage" class="upload__img-preview image-preview">
                                                    <img src="{{ getImage(getFilePath('plans'), getFileSize('plans')) }}" alt="image">
                                                </label>
        
                                                <button type="button" class="btn btn--sm btn--icon btn--danger custom-file-input-clear d-none"><i class="ti ti-circle-x"></i></button>
                                            </div>
                                            <label class="text-center small">@lang('Supported files'):
                                                <span class="fw-semibold text--base">@lang('jpeg'), @lang('jpg'), @lang('png').</span>
                                                @lang('Image size') <span class="fw-semibold text--base">{{ getFileSize('plans') }}@lang('px').</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>

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
                                            <label class="col-form--label required">@lang('Title')</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" class="form--control" name="title" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="row gy-2">
                                        <div class="col-sm-4">
                                            <label class="col-form--label required">@lang('Duration')</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <select class="form--control form-select" name="plan_duration" required>
                                                <option value="" selected disabled>@lang('Select One')</option>
                                                <option value="{{ ManageStatus::DAILY_PLAN }}">@lang('Day')</option>
                                                <option value="{{ ManageStatus::WEEKLY_PLAN }}">@lang('Week')</option>
                                                <option value="{{ ManageStatus::MONTHLY_PLAN }}">@lang('Month')</option>
                                                <option value="{{ ManageStatus::QUARTERLY_PLAN }}">@lang('Quarter Year') (@lang('3 Month'))</option>
                                                <option value="{{ ManageStatus::SEMI_ANNUAL_PLAN }}">@lang('Semi Annual') (@lang('6 Month'))</option>
                                                <option value="{{ ManageStatus::ANNUAL_PLAN }}">@lang('Annual')</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="row gy-2">
                                        <div class="col-sm-4">
                                            <label class="col-form--label required">@lang('Price')</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="input--group">
                                                <input type="number" step="any" min="0" class="form--control" name="price" required>
                                                <span class="input-group-text">{{ __($setting->site_cur) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="row gy-2">
                                        <div class="col-sm-4">
                                            <label class="col-form--label required">@lang('Daily Download Limit')</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="number" min="-1" class="form--control" name="daily_limit" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 d-none" id="monthlyDownloadDiv">
                                    <div class="row gy-2">
                                        <div class="col-sm-4">
                                            <label class="col-form--label required">@lang('Monthly Download Limit')</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="number" min="-1" class="form--control" name="monthly_limit">
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
        <x-searchForm placeholder="Name or Title" />
        <button type="button" class="btn btn--sm btn--base addBtn"><i class="ti ti-circle-plus"></i> @lang('Add New')</button>
    @endpush

    @push('page-script')
        <script>
            (function($) {
                'use strict';

                $('.addBtn').on('click', function() {
                    let modal = $('#addModal');

                    setDownloadLimit(modal);

                    modal.modal('show');
                });

                $('.editBtn').on('click', function() {
                    let modal         = $('#updateModal');
                    let actionRoute   = $(this).data('action');
                    let resource      = $(this).data('resource');
                    let fractionDigit = '{{ $setting->fraction_digit }}';
                    let image         = $(this).data('image');

                    modal.find('[name=name]').val(resource.name);
                    modal.find('[name=title]').val(resource.title);
                    modal.find('[name=plan_duration]').val(resource.plan_duration);
                    modal.find('[name=price]').val(Number(resource.price).toFixed(fractionDigit));
                    modal.find('[name=daily_limit]').val(resource.daily_limit);
                
                    if (resource.monthly_limit) {
                        modal.find('#monthlyDownloadDiv').removeClass('d-none')
                        modal.find('[name=monthly_limit]').val(resource.monthly_limit).attr('required', true);
                    } else {
                        modal.find('[name=monthly_limit]').attr('required', false).prop('disabled', true);
                        modal.find('#monthlyDownloadDiv').addClass('d-none')
                    }
                
                    modal.find('img').attr('src', image);
                    modal.find('form').attr('action', actionRoute);
                    
                    setDownloadLimit(modal);

                    modal.modal('show');
                });

                function setDownloadLimit(modal) {
                    let durationSelect = modal.find('select[name="plan_duration"]');

                    durationSelect.on('change', function() {
                        let duration       = $(this).val();
                        let dailyPLan      = '{{ ManageStatus::DAILY_PLAN }}';
                        let weaklyPlan     = '{{ ManageStatus::WEEKLY_PLAN }}';
                        let monthlyPlan    = '{{ ManageStatus::MONTHLY_PLAN }}';
                        let quarterlyPlan  = '{{ ManageStatus::QUARTERLY_PLAN }}';
                        let semiAnnualPlan = '{{ ManageStatus::SEMI_ANNUAL_PLAN }}';
                        let annualPlan     = '{{ ManageStatus::ANNUAL_PLAN }}';

                        if (duration == monthlyPlan || duration == quarterlyPlan || duration == semiAnnualPlan || duration == annualPlan) {
                            modal.find('#monthlyDownloadDiv').removeClass('d-none');
                            modal.find('[name=monthly_limit]').attr('required', true).prop('disabled', false);
                        } else {
                            modal.find('#monthlyDownloadDiv').addClass('d-none');
                            modal.find('[name=monthly_limit]').val('').attr('required', false).prop('disabled', true);
                        }
                    });
                }
            })(jQuery);
        </script>
    @endpush