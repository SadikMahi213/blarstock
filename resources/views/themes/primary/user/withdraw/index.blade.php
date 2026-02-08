@extends($activeTheme . 'layouts.auth')
@section('auth')
    <div class="py-120">
        <div class="custom--card border-0">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                <h3 class="title">{{ __($pageTitle) }}</h3>
                <form class="d-flex flex-wrap align-items-end justify-content-end gap-3">
                    <div class="input--group">
                            <input type="text" class="form--control form--control--sm" id="trxNumber" name="search" value="{{ request()->search }}" placeholder="@lang('Transaciton Number')">
                            <button class="btn btn--sm btn--light px-2"><i class="ti ti-search"></i></button>
                    </div>
                    <a href="{{ route('user.withdraw.methods') }}" class="btn btn--sm btn--light"><i class="ti ti-user-dollar"></i> @lang('Withdraw Money')</a>
                </form>
            </div>
        </div>
        <table class="table table--striped table-borderless table--responsive--xl top-rounded-0">
            <thead>
                <tr>
                    <th>@lang('Gateway') | @lang('TRX No.')</th>
                    <th>@lang('Time')</th>
                    <th>@lang('Amount')</th>
                    <th>@lang('Conversion')</th>
                    <th>@lang('Status')</th>
                    <th>@lang('Details')</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($withdraws as $withdraw)
                    <tr>
                        <td>
                            <span>
                                <span class="d-block text--base">{{ __($withdraw->method?->name ?? '') }}</span>
                                <span class="d-block">{{ $withdraw->trx }}</span>
                            </span>
                        </td>
                        <td>
                            <span>
                                <span class="d-block">{{ showDateTime($withdraw->created_at, 'M d, Y - h:i A') }}</span>
                                <span class="d-block">{{ diffForHumans($withdraw->created_at) }}</span>
                            </span>
                        </td>
                        <td>
                            <span>
                                <span class="d-block">{{ $setting->cur_sym }}{{ showAmount($withdraw->amount) }} - <span class="text--danger">{{ showAmount($withdraw->charge) }}</span></span>
                                <span class="d-block fw-bold">{{ showAmount($withdraw->amount - $withdraw->charge) }} {{ __($setting->site_cur) }}</span>
                            </span>
                        </td>
                        <td>
                            <span>
                                <span class="d-block">1 {{ __($setting->site_cur) }} = {{ showAmount($withdraw->rate) }} {{ __($withdraw->currency) }}</span>
                                <span class="d-block fw-bold">{{ showAmount($withdraw->final_amount) }} {{ __($withdraw->currency) }}</span>
                            </span>
                        </td>
                        <td>@php echo $withdraw->statusBadge @endphp</td>
                        <td>
                            <button class="btn btn--sm btn-outline--secondary py-1 detailBtn" 
                                data-user_data="{{ json_encode($withdraw->withdraw_information) }}"
                                @if ($withdraw->status == ManageStatus::PAYMENT_CANCEL) data-admin_feedback="{{ $withdraw->admin_feedback }}" @endif>
                                <i class="far fa-eye"></i> @lang('Details')
                            </button>
                        </td>
                    </tr>  
                @empty
                    <tr>
                        <td class="no-data-table" colspan="100%" rowspan="100%">
                            <div class="no-data-found">
                                <img src="{{ getImage('assets/universal/images/noData.png') }}" alt="@lang('No withdraws found, make a new one if needed')">
                                <span>@lang('No withdraws found, make a new one if needed')</span>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if ($withdraws->hasPages())
            <ul class="pagination">
                @if ($withdraws->onFirstPage())
                    <li class="page-item disabled"><span class="page-link"><i class="ti ti-chevrons-left"></i></span></li>
                @else
                    <li class="page-item"><a class="page-link" href="{{ $withdraws->previousPageUrl() }}"><i class="ti ti-chevrons-left"></i></a></li>
                @endif
    
                @foreach ($withdraws->links()->elements[0] as $page => $url)
                    <li class="page-item {{ $page == $withdraws->currentPage() ? 'active' : '' }}"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                @endforeach
    
                @if ($withdraws->hasMorePages())
                    <li class="page-item"><a class="page-link" href="{{ $withdraws->nextPageUrl() }}"><i class="ti ti-chevrons-right"></i></a></li>
                @else
                    <li class="page-item disabled"><span class="page-link"><i class="ti ti-chevrons-right"></i></span></li>                
                @endif
           </ul>
        @endif

    </div>

    <div class="custom--modal modal fade" id="withdrawDetailsModal" tabindex="-1" aria-labelledby="withdrawDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="withdrawDetailsModalLabel">@lang('Details')</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-borderless no-shadow">
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
                let modal    = $('#withdrawDetailsModal');
                let userData = $(this).data('user_data');
                let html     = ``;

                userData.forEach(element => {
                    let fileDownloadUrl = '{{ route("user.file.download", ["filePath" => "verify"]) }}';

                    if (element.type != 'file') {
                        if (!element.type) {
                            return;
                        }

                        html += `
                            <tr>
                                <td>${element.name}</td>
                                <td>${element.value}</td>
                            </tr>
                        `;
                    } else {
                        html += `
                            <tr>
                                <td>${element.name}</td>
                                <td><a href="${fileDownloadUrl}&fileName=${element.value}">@lang('Attachment')</a></td>
                            </tr>
                        `;
                    }
                });

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

                modal.modal('show');
            });
        })(jQuery);
    </script>
@endpush