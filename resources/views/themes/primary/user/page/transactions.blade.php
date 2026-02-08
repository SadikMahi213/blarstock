@extends($activeTheme. 'layouts.auth')
@section('auth')
    <div class="py-120">
        <div class="custom--card border-0">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="title">{{ __($pageTitle) }}</h3>
                <button class="btn btn--sm btn--light transaciton-filter"><i class="ti ti-adjustments-horizontal"></i> @lang('Filter')</button>
            </div>
            <div class="custom--card rounded-0 border-bottom-0 d-none" id="transacitonFilterForm">
                <div class="card-body">
                    <form class="row g-xxl-4 g-3 align-items-end">
                            <div class="col-xl-5 col-lg-4">
                                <label for="limit" class="form--label">@lang('TRX No.')</label>
                                <input type="text" class="form--control form--control--sm" id="limit" name="search" value="{{ request()->search }}" placeholder="@lang('Transaction Number')">
                            </div>
                            <div class="col-xl-2 col-lg-3 col-sm-6 col-xsm-6">
                                <label for="transactionType" class="form--label">@lang('Type')</label>
                                <select class="form--control form--control--sm form--select" id="transactionType" name="trx_type">
                                    <option value="">@lang('All')</option>
                                    <option value="+" @selected(request()->trx_type == '+')>@lang('Plus')</option>
                                    <option value="-" @selected(request()->trx_type == '-')>@lang('Minus')</option>
                                </select>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-sm-6 col-xsm-6">
                                <label for="transactionRemark" class="form--label">@lang('Remark')</label>
                                <select class="form--control form--control--sm form--select" id="transactionRemark">
                                    <option value="">@lang('Any')</option>
                                    @foreach ($remarks as $remark)
                                        <option value="{{ $remark->remark }}" @selected(request()->remark == $remark->remark)>{{ __(keyToTitle($remark->remark)) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-xl-2 col-lg-2">
                                <button class="btn btn--sm btn--base w-100"><i class="ti ti-search"></i> @lang('Search')</button>
                            </div>
                    </form>
                </div>
            </div>
            <table class="table table-borderless table--striped top-rounded-0 table--responsive--lg">
                <thead>
                    <tr>
                        <th>@lang('S.N.')</th>
                        <th>@lang('TRX')</th>
                        <th>@lang('Transacted')</th>
                        <th>@lang('Amount')</th>
                        <th>@lang('Post Balance')</th>
                        <th>@lang('Detail')</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transactions as $transaction)
                        <tr>
                            <td>{{ $transactions->firstItem() + $loop->index }}</td>
                            <td>{{ $transaction->trx }}</td>
                            <td>
                                <span>
                                    <span class="d-block">{{ showDateTime($transaction->created_at, 'M d, Y') }}</span>
                                    <span class="d-block">{{ diffForHumans($transaction->created_at) }}</span>
                                </span>
                            </td>
                            <td><span class="text--success">{{ $transaction->trx_type }} {{ showAmount($transaction->amount) }} {{ __($setting->site_cur) }}</span></td>
                            <td>{{ showAmount($transaction->post_balance) }} {{ __($setting->site_cur) }}</td>
                            <td><span class="text-overflow-1">{{ __($transaction->details) }}</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td class="no-data-table" colspan="100%" rowspan="100%">
                                <div class="no-data-found">
                                    <img src="{{ getImage('assets/universal/images/noData.png') }}" alt="@lang('No transactions found')">
                                    <span>@lang('No transactions found')</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($transactions->hasPages())
            <ul class="pagination">
                @if ($transactions->onFirstPage())
                    <li class="page-item disabled"><span class="page-link"><i class="ti ti-chevrons-left"></i></span></li>
                @else
                    <li class="page-item"><a class="page-link" href="{{ $transactions->previousPageUrl() }}"><i class="ti ti-chevrons-left"></i></a></li>
                @endif

                @foreach ($transactions->links()->elements[0] as $page => $url)
                    <li class="page-item {{ $page == $transactions->currentPage() ? 'active' : '' }}"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                @endforeach

                @if ($transactions->hasMorePages())
                    <li class="page-item"><a class="page-link" href="{{ $transactions->nextPageUrl() }}"><i class="ti ti-chevrons-right"></i></a></li>
                @else
                    <li class="page-item disabled"><span class="page-link"><i class="ti ti-chevrons-right"></i></span></li>                
                @endif
            </ul>
        @endif
    </div>
@endsection