@extends($activeTheme . 'layouts.auth')
@section('auth')
    <div class="py-120">
        <div class="custom--card border-0">
            <div class="card-header d-flex justify-content-md-between justify-content-center align-items-center flex-wrap gap-3">
                <h3 class="title">{{ __($pageTitle) }}</h3>
                <form class="d-flex flex-wrap align-items-end justify-content-md-end justify-content-center gap-3 row-gap-2">
                    <div class="input--group">
                            <input type="text" class="form--control form--control--sm" name="search" value="{{ request()->search }}" id="trxNumber" placeholder="@lang('Transaction Number')">
                            <button class="btn btn--sm btn--light px-2"><i class="ti ti-search"></i></button>
                    </div>
                    <a href="{{ route('plan') }}" class="btn btn--sm btn--light"><i class="ti ti-arrow-narrow-right"></i> @lang('Purchase Plan')</a>
                </form>
            </div>
            <table class="table table--striped table-borderless top-rounded-0 table--responsive--lg">
                <thead>
                    <tr>
                        <th>@lang('Gateway') | @lang('TRX No.')</th>
                        <th>@lang('Initiated')</th>
                        <th>@lang('Amount')</th>
                        <th>@lang('Conversion')</th>
                        <th>@lang('Status')</th>
                        <th>@lang('Details')</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($payments as $payment)
                        <tr>
                            <td>
                                <span>
                                    <span class="d-block text--base fw-semibold">{{ __($payment->gateway->name) }}</span>
                                    <span class="d-block">{{ $payment->trx }}</span>
                                </span>
                            </td>
                            <td>
                                <span>
                                    <span class="d-block">{{ showDateTime($payment->created_at, 'M d, Y - h:i A') }}</span>
                                    <span class="d-block">{{ diffForHumans($payment->created_at) }}</span>
                                </span>
                            </td>
                            <td>
                                <span>
                                    <span class="d-block">{{ $setting->site_cur }}{{ showAmount($payment->amount) }} + <span class="text--success">{{ showAmount($payment->charge) }}</span></span>
                                    <span class="d-block fw-bold">{{ showAmount($payment->amount + $payment->charge) }} {{ __($setting->site_cur) }}</span>
                                </span>
                            </td>
                            <td>
                                <span>
                                    <span class="d-block">1 {{ __($setting->site_cur) }} = {{ showAmount($payment->rate) }} {{ __($setting->site_cur) }}</span>
                                    <span class="d-block fw-bold">{{ showAmount($payment->final_amo) }} {{ __($setting->site_cur) }}</span>
                                </span>
                            </td>
                            <td>@php echo $payment->statusBadge; @endphp</td>
                            <td>
                                @php
                                    $details = ($payment->detail != null) ? json_encode($payment->detail) : null;
                                @endphp
                                <button class="btn btn--sm btn-outline--secondary py-1 @if ($payment->method_code >= 1000) detailBtn @else disabled @endif"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#paymentDetailsModal"
                                    @if ($payment->method_code >= 1000)
                                        data-info="{{ $details }}"
                                    @endif
                                    
                                    @if ($payment->status == ManageStatus::PAYMENT_CANCEL)
                                        data-admin_feedback="{{ $payment->admin_feedback }}"
                                    @endif><i class="ti ti-eye"></i> @lang('Details')
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="no-data-table" colspan="100%" rowspan="100%">
                                <div class="no-data-found">
                                    <img src="{{ getImage('assets/universal/images/noData.png') }}" alt="@lang('No payments found, make a new one if needed')">
                                    <span>@lang('No payments found, make a new one if needed')</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            @if ($payments->hasPages())
                <ul class="pagination">
                    @if ($payments->onFirstPage())
                        <li class="page-item disabled"><span class="page-link"><i class="ti ti-chevrons-left"></i></span></li>
                    @else
                        <li class="page-item"><a class="page-link" href="{{ $payments->previousPageUrl() }}"><i class="ti ti-chevrons-left"></i></a></li>
                    @endif
        
                    @foreach ($payments->links()->elements[0] as $page => $url)
                        <li class="page-item {{ $page == $payments->currentPage() ? 'active' : '' }}"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                    @endforeach
        
                    @if ($payments->hasMorePages())
                        <li class="page-item"><a class="page-link" href="{{ $payments->nextPageUrl() }}"><i class="ti ti-chevrons-right"></i></a></li>
                    @else
                        <li class="page-item disabled"><span class="page-link"><i class="ti ti-chevrons-right"></i></span></li>                
                    @endif
                </ul>
            @endif
        </div>
    </div>

    <div class="custom--modal modal fade" id="paymentDetailsModal" tabindex="-1" aria-labelledby="paymentDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
             <div class="modal-content">
                  <div class="modal-header">
                       <h3 class="modal-title" id="paymentDetailsModalLabel">@lang('Details')</h3>
                       <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                       <table class="table table-borderless">
                            <tbody class="userData">

                            </tbody>
                       </table>
                       <div class="feedback mt-3">

                       </div>
                  </div>
             </div>
        </div>
   </div>
@endsection

@push('page-script')
    <script>
        (function($) {
            'use strict';

            $('.detailBtn').on('click', function() {
                let modal    = $('#paymentDetailsModal');
                let userData = $(this).data('info');
                let html     = '';

                if (userData) {
                    let fileDownloadUrl = '{{ route("user.file.download", ["filePath" => "verify"]) }}';

                    userData.forEach(element => {
                        if (!element.value) {
                            return;
                        }

                        if (element.type != 'file') {
                            html += `
                                    <tr>
                                        <td>${element.name}</td>
                                        <td>${element.value}</td>
                                    </tr>`;
                        } else {
                            html += `
                                    <tr>
                                        <td>${element.name}</td>
                                        <td><a href="${fileDownloadUrl}&fileName=${element.value}">@lang('Attachment')</a></td>
                                    </tr>`;
                        }
                    });
                }

                modal.find('.userData').html(html);

                let adminFeedback = ``;
                if ($(this).data('admin_feedback') != undefined) {
                    adminFeedback = `
                            <label class="form--label ps-2">@lang('Admin Feedback')</label>
                            <textarea class="form--control rounded-4 mb-4">${$(this).data('admin_feedback')}</textarea>
                    `;
                } else {
                    adminFeedback = ``; 
                }

                modal.find('.feedback').html(adminFeedback);
            });

        })(jQuery);
    </script>
@endpush