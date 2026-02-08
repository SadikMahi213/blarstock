@extends('admin.layouts.master')
@section('master')
    <div class="col-12">
        <div class="table-responsive scroll">
            <table class="table table--striped table-borderless table--responsive--sm">
                <thead>
                    <tr>
                        <th>@lang('Receiver')</th>
                        <th>@lang('Sender')</th>
                        <th>@lang('Gateway') | @lang('Transaction')</th>
                        <th>@lang('Launched')</th>
                        <th>@lang('Amount')</th>
                        <th>@lang('Conversion')</th>
                        <th>@lang('Status')</th>
                        <th>@lang('Action')</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($donations as $donation)
                        <tr>
                            <td>
                                <div>
                                    <p class="fw-semibold">{{ __($donation->donationReceiver->author_name ?? '') }}</p>
                                    <p class="fw-semibold">
                                        <a href="{{ appendQuery('search', $donation->donationReceiver->username) }}"> <small>@</small>{{ $donation->donationReceiver->username }}</a>
                                    </p>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <p class="fw-semibold">{{ __($donation->donation_sender?->name ?? '') }}</p>
                                    <p class="fw-semibold text--base">{{ $donation->donation_sender?->email ?? ''}}</p>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <a href="{{ appendQuery('method', $donation->gateway?->alias ?? '') }}" class="fw-semibold text--base">{{ $donation->gateway ? __($donation->gateway?->name ?? '') : trans('From Wallet') }}</a>
                                    <p>{{ $donation->trx }}</p>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <p>{{ showDateTime($donation->created_at) }}</p>
                                    <p>{{ diffForHumans($donation->created_at) }}</p>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <p>{{ __($setting->cur_sym) }}{{ showAmount($donation->amount ) }}  + <span class="text--danger" title="@lang('Charge')">{{ __($setting->cur_sym) }}{{ showAmount($donation->charge)}}</span></p>
                                    <p class="fw-semibold" title="@lang('Amount with charge')">{{ showAmount($donation->final_amo) }} {{ __($setting->site_cur) }}</p>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <p>1 {{ __($setting->site_cur) }} = {{ showAmount($donation->rate != 0 ? $donation->rate : 1) }} {{__($donation->method_currency ?? $setting->site_cur)}}</p>
                                    <p class="fw-semibold">{{ showAmount($donation->final_amo) }} {{ __($donation->method_currency) }}</p>
                                </div>
                            </td>
                            <td>
                                <div>
                                    @php echo $donation->statusBadge; @endphp
                                </div>
                            </td>
                            <td>
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="#donationDetails" class="btn btn--sm btn-outline--base detailBtn"
                                        data-bs-toggle      = "offcanvas"
                                        data-date           = "{{ showDateTime($donation->created_at) }}"
                                        data-trx            = "{{ $donation->trx }}"
                                        data-username       = "{{ $donation->user ? $donation->user?->username ?? '' : $donation->donation_sender?->name ??'' }}"
                                        data-method         = "{{ __($donation->gateway?->name ?? 'From Wallet') }}"
                                        data-amount         = "{{ showAmount($donation->amount) }} {{ __($setting->site_cur) }}"
                                        data-charge         = "{{ showAmount($donation->charge ) }} {{ __($setting->site_cur) }}"
                                        data-after_charge   = "{{ showAmount($donation->amount + $donation->charge ) }} {{ __($setting->site_cur) }}"
                                        @if ($donation->rate)
                                            data-rate           = "1 {{ __($setting->site_cur) }} = {{ showAmount($donation->rate != 0 ? $donation->rate : 1) }} {{ __($donation->baseCurrency()) }}"
                                        @endif
                                        data-payable        = "{{ showAmount($donation->final_amo) }} {{ __($donation->method_currency) }}"
                                        data-status         = "{{ $donation->status }}"
                                        data-user_data      = "{{ json_encode($donation->detail) }}"
                                        data-admin_feedback = "{{ $donation->admin_feedback }}">

                                        <i class="ti ti-info-square-rounded"></i> @lang('Details')
                                    </a>

                                    @if ($donation->status == ManageStatus::PAYMENT_PENDING)
                                        <div class="custom--dropdown">
                                            <button class="btn btn--icon btn--sm btn--base" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="ti ti-dots-vertical"></i></button>

                                            <ul class="dropdown-menu">
                                                <li>
                                                    <button class="dropdown-item decisionBtn" data-question="@lang('Do you confirm the approval of this donation')?" data-action="{{ route('admin.deposit.approve', $donation->id) }}"><span class="dropdown-icon">
                                                        <i class="ti ti-circle-check text--success"></i></span> @lang('Approve')
                                                    </button>
                                                </li>
                                                <li> 
                                                    <button class="dropdown-item cancelBtn" data-id="{{ $donation->id }}"><span class="dropdown-icon"><i class="ti ti-circle-x text--danger"></i></span> @lang('Cancel')</button>
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

        @if ($donations->hasPages())
            {{ paginateLinks($donations) }}
        @endif
    </div>

    <div class="col-12">
        <div class="custom--offcanvas offcanvas offcanvas-end" tabindex="-1" id="donationDetails" aria-labelledby="donationDetailsLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="donationDetailsLabel">@lang('Donation Details')</h5>
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
        <div class="custom--modal modal fade" id="cancelDonationModal" tabindex="-1" aria-labelledby="cancelDonationModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <button type="button" class="btn btn--sm btn--icon btn-outline--secondary modal-close" data-bs-dismiss="modal" aria-label="Close"><i class="ti ti-x"></i></button>
                    <div class="modal-body modal-alert">
                        <div class="text-center">
                            <div class="modal-thumb">
                                <img src="{{ asset('assets/admin/images/light.png') }}" alt="Image">
                            </div>
                            <h2 class="modal-title" id="cancelDonationModalLabel">@lang('Cancel Donation Confirmation')</h2>
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
                let infoHtml        = `<h6 class="mb-2">@lang('Donation User Data')</h6>
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
            let modal = $('#cancelDonationModal');
            modal.find('[name=id]').val($(this).data('id'));
            modal.modal('show');
        });
    })(jQuery);
</script>
@endpush