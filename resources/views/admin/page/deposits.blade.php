@extends('admin.layouts.master')

@section('master')
    @if(request()->routeIs('admin.deposit.index'))
        <div class="col-12">
            <div class="row g-lg-4 g-3">
                <div class="col-xl-3 col-sm-6">
                    <a href="{{ route('admin.deposit.done') }}" class="dashboard-widget-3 dashboard-widget-3__success">
                        <div class="dashboard-widget-3__top">
                            <h3 class="dashboard-widget-3__number">{{ $setting->cur_sym . showAmount($done) }}</h3>
                            <div class="dashboard-widget-3__icon">
                                <i class="ti ti-circle-check"></i>
                            </div>
                        </div>
                        <p class="dashboard-widget-3__txt">@lang('Done Deposit Amount')</p>
                    </a>
                </div>
                <div class="col-xl-3 col-sm-6">
                    <a href="{{ route('admin.deposit.index') }}" class="dashboard-widget-3 dashboard-widget-3__info">
                        <div class="dashboard-widget-3__top">
                            <h3 class="dashboard-widget-3__number">{{ $setting->cur_sym . showAmount($charge) }}</h3>
                            <div class="dashboard-widget-3__icon">
                                <i class="ti ti-coins"></i>
                            </div>
                        </div>
                        <p class="dashboard-widget-3__txt">@lang('Total Charge for Deposit')</p>
                    </a>
                </div>
                <div class="col-xl-3 col-sm-6">
                    <a href="{{ route('admin.deposit.pending') }}" class="dashboard-widget-3 dashboard-widget-3__warning">
                        <div class="dashboard-widget-3__top">
                            <h3 class="dashboard-widget-3__number">{{ $setting->cur_sym . showAmount($pending) }}</h3>
                            <div class="dashboard-widget-3__icon">
                                <i class="ti ti-rotate-clockwise-2"></i>
                            </div>
                        </div>
                        <p class="dashboard-widget-3__txt">@lang('Pending Deposit Amount')</p>
                    </a>
                </div>
                <div class="col-xl-3 col-sm-6">
                    <a href="{{ route('admin.deposit.canceled') }}" class="dashboard-widget-3 dashboard-widget-3__danger">
                        <div class="dashboard-widget-3__top">
                            <h3 class="dashboard-widget-3__number">{{ $setting->cur_sym . showAmount($canceled) }}</h3>
                            <div class="dashboard-widget-3__icon">
                                <i class="ti ti-circle-x"></i>
                            </div>
                        </div>
                        <p class="dashboard-widget-3__txt">@lang('Cancelled Deposit Amount')</p>
                    </a>
                </div>
            </div>
        </div>
    @endif

    <div class="col-12">
        <div class="table-responsive scroll">
            <table class="table table--striped table-borderless table--responsive--sm">
                <thead>
                    <tr>
                        <th>@lang('User')</th>
                        <th>@lang('Gateway') | @lang('Transaction')</th>
                        <th>@lang('Launched')</th>
                        <th>@lang('Amount')</th>
                        <th>@lang('Conversion')</th>
                        <th>@lang('Status')</th>
                        <th>@lang('Action')</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($deposits as $deposit)
                        <tr>
                            <td>
                                <div class="table-card-with-image">
                                    <div class="table-card-with-image__img">
                                        <img src="{{ getImage(getFilePath('userProfile').'/'.$deposit?->image, getFileSize('userProfile'), true) }}" alt="Image">
                                    </div>
                                    <div class="table-card-with-image__content">
                                        <p class="fw-semibold">{{ __($deposit->user->fullname) }}</p>
                                        <p class="fw-semibold">
                                            <a href="{{ appendQuery('search', $deposit->user?->username) }}"> <small>@</small>{{ $deposit->user->username }}</a>
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <a href="{{ appendQuery('method', $deposit->gateway?->alias) }}" class="fw-semibold text--base">{{ __($deposit->gateway?->name) }}</a>
                                    <p>{{ $deposit->trx }}</p>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <p>{{ showDateTime($deposit->created_at) }}</p>
                                    <p>{{ diffForHumans($deposit->created_at) }}</p>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <p>{{ __($setting->cur_sym) }}{{ showAmount($deposit->amount ) }}  + <span class="text--danger" title="@lang('Charge')">{{ __($setting->cur_sym) }}{{ showAmount($deposit->charge)}}</span></p>
                                    <p class="fw-semibold" title="@lang('Amount with charge')">{{ showAmount($deposit->final_amo) }} {{ __($setting->site_cur) }}</p>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <p>1 {{ __($setting->site_cur) }} = {{ showAmount($deposit->rate) }} {{__($deposit->method_currency)}}</p>
                                    <p class="fw-semibold">{{ showAmount($deposit->final_amo) }} {{ __($deposit->method_currency) }}</p>
                                </div>
                            </td>
                            <td>
                                <div>
                                    @php echo $deposit->statusBadge; @endphp
                                </div>
                            </td>
                            <td>
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="#depositDetails" class="btn btn--sm btn-outline--base detailBtn"
                                        data-bs-toggle      = "offcanvas"
                                        data-date           = "{{ showDateTime($deposit->created_at) }}"
                                        data-trx            = "{{ $deposit->trx }}"
                                        data-username       = "{{ $deposit->user ? $deposit->user?->username ?? '' : $deposit->donation?->user?->author_name ?? '' }}"
                                        data-method         = "{{ __($deposit->gateway?->name) }}"
                                        data-amount         = "{{ showAmount($deposit->amount) }} {{ __($setting->site_cur) }}"
                                        data-charge         = "{{ showAmount($deposit->charge ) }} {{ __($setting->site_cur) }}"
                                        data-after_charge   = "{{ showAmount($deposit->amount + $deposit->charge ) }} {{ __($setting->site_cur) }}"
                                        data-rate           = "1 {{ __($setting->site_cur) }} = {{ showAmount($deposit->rate) }} {{__($deposit->baseCurrency()) }}"
                                        data-payable        = "{{ showAmount($deposit->final_amo) }} {{ __($deposit->method_currency) }}"
                                        data-status         = "{{ $deposit->status }}"
                                        data-user_data      = "{{ json_encode($deposit->details) }}"
                                        data-admin_feedback = "{{ $deposit->admin_feedback }}">

                                        <i class="ti ti-info-square-rounded"></i> @lang('Details')
                                    </a>

                                    @if ($deposit->status == ManageStatus::PAYMENT_PENDING)
                                        <div class="custom--dropdown">
                                            <button class="btn btn--icon btn--sm btn--base" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="ti ti-dots-vertical"></i></button>

                                            <ul class="dropdown-menu">
                                                <li>
                                                    <button class="dropdown-item decisionBtn" data-question="@lang('Do you confirm the approval of this transaction')?" data-action="{{ route('admin.deposit.approve', $deposit->id) }}"><span class="dropdown-icon">
                                                        <i class="ti ti-circle-check text--success"></i></span> @lang('Approve')
                                                    </button>
                                                </li>
                                                <li> 
                                                    <button class="dropdown-item cancelBtn" data-id="{{ $deposit->id }}"><span class="dropdown-icon"><i class="ti ti-circle-x text--danger"></i></span> @lang('Cancel')</button>
                                                </li>
                                            </ul>
                                        </div>
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

        @if ($deposits->hasPages())
            {{ paginateLinks($deposits) }}
        @endif
    </div>

    <div class="col-12">
        <div class="custom--offcanvas offcanvas offcanvas-end" tabindex="-1" id="depositDetails" aria-labelledby="depositDetailsLabel">
            <div class="offcanvas-header">
                 <h5 class="offcanvas-title" id="depositDetailsLabel">@lang('Deposit Details')</h5>
                 <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                 <h6 class="mb-2">@lang('Basic Information')</h6>
                 <table class="table table-borderless mb-3">
                      <tbody class="basic-details"></tbody>
                 </table>

                 <div class="user-data"></div>
            </div>
       </div>
    </div>

    <div class="col-12">
        <div class="custom--modal modal fade" id="cancelDepositModal" tabindex="-1" aria-labelledby="cancelDepositModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                 <div class="modal-content">
                      <button type="button" class="btn btn--sm btn--icon btn-outline--secondary modal-close" data-bs-dismiss="modal" aria-label="Close"><i class="ti ti-x"></i></button>
                      <div class="modal-body modal-alert">
                           <div class="text-center">
                                <div class="modal-thumb">
                                     <img src="{{ asset('assets/admin/images/light.png') }}" alt="Image">
                                </div>
                                <h2 class="modal-title" id="cancelDepositModalLabel">@lang('Cancel Deposit Confirmation')</h2>
                                <form action="{{ route('admin.deposit.cancel') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="id">
                                    
                                    <label class="form--label">@lang('Reason') :</label>
                                    <textarea class="form--control form--control--sm" name="admin_feedback" required></textarea>

                                    <div class="d-flex justify-content-center gap-2 mt-3">
                                        <button class="btn btn--sm btn--base" type="submit">@lang('Yes')</button>
                                        <button type="button" class="btn btn--sm btn-outline--base" data-bs-dismiss="modal">@lang('No')</button>
                                    </div>
                                </form>
                           </div>
                      </div>
                 </div>
            </div>
       </div>
    </div>

    <x-decisionModal />
@endsection

@push('breadcrumb')
    <x-searchForm placeholder="TRX / Username" dateSearch="yes" />
@endpush

@push('page-script')
    <script>
        (function ($) {
            "use strict";

            $('.detailBtn').on('click', function () {
                let userData   = $(this).data('user_data');
                let statusHtml = ``;

                if ($(this).data('status') == 1) {
                    statusHtml += `<span class="badge badge--success">@lang('Done')</span>`;
                } else if ($(this).data('status') == 2) {
                    statusHtml += `<span class="badge badge--warning">@lang('Pending')</span>`;
                } else {
                    statusHtml += `<span class="badge badge--danger">@lang('Canceled')</span>`
                }

                let basicHtml  = `<tr>
                                    <td class="fw-bold">@lang('Date')</td>
                                    <td>${$(this).data('date')}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">@lang('Trx Number')</td>
                                    <td>${$(this).data('trx')}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">@lang('Username')</td>
                                    <td>${$(this).data('username')}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">@lang('Method')</td>
                                    <td>${$(this).data('method')}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">@lang('Amount')</td>
                                    <td>${$(this).data('amount')}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">@lang('Charge')</td>
                                    <td>${$(this).data('charge')}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">@lang('After Charge')</td>
                                    <td>${$(this).data('after_charge')}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">@lang('Rate')</td>
                                    <td>${$(this).data('rate')}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">@lang('Payable')</td>
                                    <td>${$(this).data('payable')}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">@lang('Status')</td>
                                    <td>${statusHtml}</td>
                                </tr>`;

                if ($(this).data('admin_feedback')) {
                    basicHtml += `<tr>
                                    <td class="fw-bold">@lang('Admin Feedback')</td>
                                    <td>${$(this).data('admin_feedback')}</td>
                                </tr>`;
                }

                $('.basic-details').html(basicHtml);

                if (userData) {                    
                    let fileDownloadUrl = '{{ route("admin.file.download",["filePath" => "verify"]) }}';
                    let infoHtml        = `<h6 class="mb-2">@lang('Deposit User Data')</h6>
                                            <table class="table table-borderless mb-3">
                                                <tbody>`;
    
                    userData.forEach(element => {
                        if (!element.value) { return; }

                        if(element.type != 'file') {
                            infoHtml += `<tr>
                                            <td class="fw-bold">${element.name}</td>
                                            <td>${element.value}</td>
                                        </tr>`;
                        } else {
                            infoHtml += `<tr>
                                            <td class="fw-bold">${element.name}</td>
                                            <td>
                                                <a href="${fileDownloadUrl}&fileName=${element.value}" class="btn btn--sm btn-outline--secondary">
                                                    <i class="ti ti-download"></i> @lang('Attachment')
                                                </a>
                                            </td>
                                        </tr>`;
                        }
                    });

                    infoHtml += `</table>
                            </tbody>`;

                    $('.user-data').html(infoHtml);
                } else {
                    $('.user-data').empty();
                }
            });

            $('.cancelBtn').on('click', function () {
                let modal = $('#cancelDepositModal');
                modal.find('[name=id]').val($(this).data('id'));
                modal.modal('show');
            });
        })(jQuery);
    </script>
@endpush