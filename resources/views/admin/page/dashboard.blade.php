@extends('admin.layouts.master')

@section('master')
    <div class="col-12">
        <div class="row g-lg-4 g-3">
            <div class="col-xl-3 col-sm-6">
                <a href="{{ route('admin.user.index') }}" class="dashboard-widget-1">
                    <div class="dashboard-widget-1__icon">
                        <i class="ti ti-users"></i>
                    </div>
                    <div class="dashboard-widget-1__content">
                        <h3 class="dashboard-widget-1__number">{{ $widget['totalUsers'] }}</h3>
                        <p class="dashboard-widget-1__txt">@lang('Total Users')</p>
                    </div>
                </a>
            </div>
            
            <div class="col-xl-3 col-sm-6">
                <a href="{{ route('admin.user.active') }}" class="dashboard-widget-1 dashboard-widget-1__success">
                    <div class="dashboard-widget-1__icon">
                        <i class="ti ti-user-check"></i>
                    </div>
                    <div class="dashboard-widget-1__content">
                        <h3 class="dashboard-widget-1__number">{{ $widget['activeUsers'] }}</h3>
                        <p class="dashboard-widget-1__txt">@lang('Active Users')</p>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-sm-6">
                <a href="{{ route('admin.user.email.unconfirmed') }}" class="dashboard-widget-1 dashboard-widget-1__warning">
                    <div class="dashboard-widget-1__icon">
                        <i class="ti ti-at"></i>
                    </div>
                    <div class="dashboard-widget-1__content">
                        <h3 class="dashboard-widget-1__number">{{ $widget['emailUnconfirmedUsers'] }}</h3>
                        <p class="dashboard-widget-1__txt">@lang('Email Unconfirmed Users')</p>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-sm-6">
                <a href="{{ route('admin.user.mobile.unconfirmed') }}" class="dashboard-widget-1 dashboard-widget-1__danger">
                    <div class="dashboard-widget-1__icon">
                        <i class="ti ti-message-off"></i>
                    </div>
                    <div class="dashboard-widget-1__content">
                        <h3 class="dashboard-widget-1__number">{{ $widget['mobileUnconfirmedUsers'] }}</h3>
                        <p class="dashboard-widget-1__txt">@lang('Mobile Unconfirmed Users')</p>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="row g-lg-4 g-3">
            <div class="col-xl-3 col-sm-6">
                <a href="{{ route('admin.earning.record') }}" class="dashboard-widget-1 dashboard-widget-1__info">
                    <div class="dashboard-widget-1__icon">
                        <i class="ti ti-cash"></i>
                    </div>
                    <div class="dashboard-widget-1__content">
                        <h3 class="dashboard-widget-1__number">{{ $setting->cur_sym }}{{ showAmount($widget['totalEarning']) }}</h3>
                        <p class="dashboard-widget-1__txt">@lang('Author Earnings')</p>
                    </div>
                </a>
            </div>
            
            <div class="col-xl-3 col-sm-6">
                <a href="{{ route('admin.donation.done') }}" class="dashboard-widget-1 dashboard-widget-1__primary">
                    <div class="dashboard-widget-1__icon">
                        <i class="ti ti-heart-handshake"></i>
                    </div>
                    <div class="dashboard-widget-1__content">
                        <h3 class="dashboard-widget-1__number">{{ $setting->cur_sym }}{{ showAmount($widget['donationDone']) }}</h3>
                        <p class="dashboard-widget-1__txt">@lang('Donated Amount')</p>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-sm-6">
                <a href="{{ route('admin.donation.pending') }}" class="dashboard-widget-1 dashboard-widget-1__warning">
                    <div class="dashboard-widget-1__icon">
                        <i class="ti ti-clock-pause"></i>
                    </div>
                    <div class="dashboard-widget-1__content">
                        <h3 class="dashboard-widget-1__number">{{ $setting->cur_sym }}{{ showAmount($widget['donationPending']) }}</h3>
                        <p class="dashboard-widget-1__txt">@lang('Pending Donation')</p>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-sm-6">
                <a href="{{ route('admin.donation.index') }}" class="dashboard-widget-1 dashboard-widget-1__danger">
                    <div class="dashboard-widget-1__icon">
                        <i class="ti ti-receipt-2"></i>
                    </div>
                    <div class="dashboard-widget-1__content">
                        <h3 class="dashboard-widget-1__number">{{ $setting->cur_sym }}{{ showAmount($widget['donationCharge']) }}</h3>
                        <p class="dashboard-widget-1__txt">@lang('Donation Charge')</p>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="dashboard-widget-row">
            <a href="{{ route('admin.deposit.done') }}" class="dashboard-widget-2 dashboard-widget-2__success">
                <div class="dashboard-widget-2__top">
                    <h3 class="dashboard-widget-2__number">{{ $setting->cur_sym }}{{ showAmount($widget['depositDone']) }}</h3>
                    <div class="dashboard-widget-2__icon">
                        <i class="ti ti-wallet"></i>
                    </div>
                </div>
                <p class="dashboard-widget-2__txt">@lang('Deposited Amount')</p>
            </a>
            <a href="{{ route('admin.deposit.pending') }}" class="dashboard-widget-2 dashboard-widget-2__warning">
                <div class="dashboard-widget-2__top">
                    <h3 class="dashboard-widget-2__number">{{ $setting->cur_sym }}{{ showAmount($widget['depositPending']) }}</h3>
                    <div class="dashboard-widget-2__icon">
                        <i class="ti ti-rotate-clockwise-2"></i>
                    </div>
                </div>
                <p class="dashboard-widget-2__txt">@lang('Pending Deposit')</p>
            </a>
            <a href="{{ route('admin.deposit.canceled') }}" class="dashboard-widget-2 dashboard-widget-2__danger">
                <div class="dashboard-widget-2__top">
                    <h3 class="dashboard-widget-2__number">{{ $setting->cur_sym }}{{ showAmount($widget['depositCanceled']) }}</h3>
                    <div class="dashboard-widget-2__icon">
                        <i class="ti ti-x"></i>
                    </div>
                </div>
                <p class="dashboard-widget-2__txt">@lang('Cancelled Deposit')</p>
            </a>
            <a href="{{ route('admin.deposit.index') }}" class="dashboard-widget-2 dashboard-widget-2__info">
                <div class="dashboard-widget-2__top">
                    <h3 class="dashboard-widget-2__number">{{ $setting->cur_sym }}{{ showAmount($widget['depositCharge']) }}</h3>
                    <div class="dashboard-widget-2__icon">
                        <i class="ti ti-percentage"></i>
                    </div>
                </div>
                <p class="dashboard-widget-2__txt">@lang('Deposit Charge')</p>
            </a>
        </div>
    </div>

    <div class="col-12">
        <div class="dashboard-widget-row">
            <a href="{{ route('admin.withdraw.done') }}" class="dashboard-widget-2">
                <div class="dashboard-widget-2__top">
                    <h3 class="dashboard-widget-2__number">{{ $setting->cur_sym }}{{ showAmount($widget['withdrawDone']) }}</h3>
                    <div class="dashboard-widget-2__icon">
                        <i class="ti ti-cash-banknote"></i>
                    </div>
                </div>
                <p class="dashboard-widget-2__txt">@lang('Withdrawn Amount')</p>
            </a>
            <a href="{{ route('admin.withdraw.index') }}" class="dashboard-widget-2 dashboard-widget-2__info">
                <div class="dashboard-widget-2__top">
                    <h3 class="dashboard-widget-2__number">{{ $setting->cur_sym }}{{ showAmount($widget['withdrawCharge']) }}</h3>
                    <div class="dashboard-widget-2__icon">
                        <i class="ti ti-percentage"></i>
                    </div>
                </div>
                <p class="dashboard-widget-2__txt">@lang('Withdral Charge')</p>
            </a>
            <a href="{{ route('admin.withdraw.pending') }}" class="dashboard-widget-2 dashboard-widget-2__warning">
                <div class="dashboard-widget-2__top">
                    <h3 class="dashboard-widget-2__number">{{ $setting->cur_sym }}{{ showAmount($widget['withdrawPending']) }}</h3>
                    <div class="dashboard-widget-2__icon">
                        <i class="ti ti-rotate-dot"></i>
                    </div>
                </div>
                <p class="dashboard-widget-2__txt">@lang('Pending Withdrawal')</p>
            </a>
            <a href="{{ route('admin.withdraw.canceled') }}" class="dashboard-widget-2 dashboard-widget-2__danger">
                <div class="dashboard-widget-2__top">
                    <h3 class="dashboard-widget-2__number">{{ $setting->cur_sym }}{{ showAmount($widget['withdrawCanceled']) }}</h3>
                    <div class="dashboard-widget-2__icon">
                        <i class="ti ti-ban"></i>
                    </div>
                </div>
                <p class="dashboard-widget-2__txt">@lang('Cancelled Withdrawal')</p>
            </a>
        </div>
    </div>

    <div class="col-12">
        <div class="dashboard-widget-row">
            <a href="{{ route('admin.payment.done') }}" class="dashboard-widget-2 dashboard-widget-2__primary">
                <div class="dashboard-widget-2__top">
                    <h3 class="dashboard-widget-2__number">{{ $setting->cur_sym }}{{ showAmount($widget['paymentDone']) }}</h3>
                    <div class="dashboard-widget-2__icon">
                        <i class="ti ti-credit-card"></i>
                    </div>
                </div>
                <p class="dashboard-widget-2__txt">@lang('Payment Amount')</p>
            </a>
            <a href="{{ route('admin.payment.pending') }}" class="dashboard-widget-2 dashboard-widget-2__warning">
                <div class="dashboard-widget-2__top">
                    <h3 class="dashboard-widget-2__number">{{ $setting->cur_sym }}{{ showAmount($widget['paymentPending']) }}</h3>
                    <div class="dashboard-widget-2__icon">
                        <i class="ti ti-clock-dollar"></i>
                    </div>
                </div>
                <p class="dashboard-widget-2__txt">@lang('Pending Payment')</p>
            </a>
            <a href="{{ route('admin.payment.canceled') }}" class="dashboard-widget-2 dashboard-widget-2__danger">
                <div class="dashboard-widget-2__top">
                    <h3 class="dashboard-widget-2__number">{{ $setting->cur_sym }}{{ showAmount($widget['paymentCanceled']) }}</h3>
                    <div class="dashboard-widget-2__icon">
                        <i class="ti ti-credit-card-off"></i>
                    </div>
                </div>
                <p class="dashboard-widget-2__txt">@lang('Cancelled Payment')</p>
            </a>
            <a href="{{ route('admin.payment.index') }}" class="dashboard-widget-2">
                <div class="dashboard-widget-2__top">
                    <h3 class="dashboard-widget-2__number">{{ $setting->cur_sym }}{{ showAmount($widget['paymentCharge']) }}</h3>
                    <div class="dashboard-widget-2__icon">
                        <i class="ti ti-receipt-tax"></i>
                    </div>
                </div>
                <p class="dashboard-widget-2__txt">@lang('Payment Charge')</p>
            </a>
        </div>
    </div>

    <div class="col-12">
        <div class="row g-4">
            <div class="col-xxl-3">
                <div class="row g-4">
                    <div class="col-12">
                        <div class="dashboard-widget-6">
                            <div class="dashboard-widget-6__content">
                                <h3 class="dashboard-widget-6__number">6</h3>
                                <p class="dashboard-widget-6__txt">@lang('Pending Info')</p>
                            </div>
                            <div class="dashboard-widget-6__icon bg--warning">
                                <i class="ti ti-rotate-clockwise-2 transform-2"></i>
                            </div>
                            <div class="dashboard-widget-6__list">
                                <ul>
                                    <li class="dashboard-widget-6__list__item list-base">
                                        <a href="{{ route('admin.author.pending') }}">
                                            <span>@lang('Pending Authors')</span>
                                        </a> <span>{{ $widget['pendingAuthor'] }}</span>
                                    </li>
                                    <li class="dashboard-widget-6__list__item list-danger">
                                        <a href="{{ route('admin.asset.pending') }}">
                                            <span>@lang('Pending Assets')</span>
                                        </a> <span>{{ $widget['pendingAsset'] }}</span>
                                    </li>
                                    <li class="dashboard-widget-6__list__item list-info">
                                        <a href="{{ route('admin.deposit.pending') }}">
                                            <span>@lang('Deposit Request')</span>
                                        </a> <span>{{ $widget['pendingDepositCount'] }}</span>
                                    </li>
                                    <li class="dashboard-widget-6__list__item list-warning">
                                        <a href="{{ route('admin.withdraw.pending') }}">
                                            <span>@lang('Withdraw Request')</span>
                                        </a> <span>{{ $widget['pendingWithdrawCount'] }}</span>
                                    </li>
                                    <li class="dashboard-widget-6__list__item list-success">
                                        <a href="{{ route('admin.donation.pending') }}">
                                            <span>@lang('Donation Request')</span>
                                        </a> <span>{{ $widget['pendingDonationCount'] }}</span>
                                    </li>
                                    <li class="dashboard-widget-6__list__item list-success">
                                        <a href="{{ route('admin.payment.pending') }}">
                                            <span>@lang('Payment Rquest')</span>
                                        </a> <span>{{ $widget['pendingPaymentCount'] }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="dashboard-widget-6">
                            <div class="dashboard-widget-6__content">
                                <h3 class="dashboard-widget-6__number">5</h3>
                                <p class="dashboard-widget-6__txt">@lang('Rejected Info')</p>
                            </div>
                            <div class="dashboard-widget-6__icon bg-danger">
                                <i class="ti ti-calendar-exclamation transform-2"></i>
                            </div>
                            <div class="dashboard-widget-6__list">
                                <ul>
                                    <li class="dashboard-widget-6__list__item list-base">
                                        <a href="{{ route('admin.author.rejected') }}">
                                            <span>@lang('Rejected Authors')</span>
                                        </a> <span>{{ $widget['rejectedAuthor'] }}</span>
                                    </li>
                                    <li class="dashboard-widget-6__list__item list-info">
                                        <a href="{{ route('admin.asset.rejected') }}">
                                            <span>@lang('Rejected Asset')</span>
                                        </a> <span>{{ $widget['rejectedAuthor'] }}</span>
                                    </li>
                                    <li class="dashboard-widget-6__list__item list-base">
                                        <a href="{{ route('admin.deposit.canceled') }}">
                                            <span>@lang('Rejected Deposit')</span>
                                        </a> <span>{{ $widget['canceledDepositCount'] }}</span>
                                    </li>
                                    <li class="dashboard-widget-6__list__item list-info">
                                        <a href="{{ route('admin.withdraw.canceled') }}">
                                            <span>@lang('Rejected Withdrawal')</span>
                                        </a> <span>{{ $widget['canceledWithdrawCount'] }}</span>
                                    </li>
                                    <li class="dashboard-widget-6__list__item list-base">
                                        <a href="{{ route('admin.payment.canceled') }}">
                                            <span>@lang('Rejected Payment')</span>
                                        </a> <span>{{ $widget['cancelePaymentCount'] }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xxl-3 col-md-6">
                <div class="dashboard-widget-6 dashboard-widget-6-lite">
                    <div class="dashboard-widget-6__list-2">
                        <ul>
                            <li class="dashboard-widget-6__list-2__item">
                                <a href="{{ route('admin.author.index') }}">
                                    <span class="left"><i class="ti ti-user-heart"></i> @lang('Total Authors')</span>
                                </a>
                                <span class="right">{{ $widget['allAuthor'] }}</span>
                            </li>
                            <li class="dashboard-widget-6__list-2__item list-success">
                                <a href="{{ route('admin.author.approved') }}">
                                    <span class="left"><i class="ti ti-user-star"></i> @lang('Approved Authors')</span>
                                </a>
                                <span class="right">{{ $widget['approvedAuthor'] }}</span>
                            </li>
                            <li class="dashboard-widget-6__list-2__item list-warning">
                                <a href="{{ route('admin.author.banned') }}">
                                    <span class="left"><i class="ti ti-user-off"></i> @lang('Banned Authors')</span>
                                </a>
                                <span class="right">{{ $widget['bannedAuthor'] }}</span>
                            </li>
                            <li class="dashboard-widget-6__list-2__item list-info">
                                <a href="{{ route('admin.category.index') }}">
                                    <span class="left"><i class="ti ti-category"></i> @lang('Total Categories')</span>
                                </a>
                                <span class="right">{{ $widget['totalCategories'] }}</span>
                            </li>
                            <li class="dashboard-widget-6__list-2__item list-primary">
                                <a href="{{ route('admin.transaction.index') }}">
                                    <span class="left"><i class="ti ti-cash-register"></i> @lang('Total Transactions')</span>
                                </a>
                                <span class="right">{{ $widget['totalTrx'] }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-xxl-3 col-md-6">
                <div class="dashboard-widget-6 dashboard-widget-6-lite">
                    <div class="dashboard-widget-6__list-2">
                        <ul>
                            <li class="dashboard-widget-6__list-2__item list-info">
                                <a href="{{ route('admin.asset.index') }}">
                                    <span class="left"><i class="ti ti-photo"></i> @lang('Total Assets')</span>
                                </a>
                                <span class="right">{{ $widget['allAsset'] }}</span>
                            </li>
                            <li class="dashboard-widget-6__list-2__item list-success">
                                <a href="{{ route('admin.asset.approved') }}">
                                    <span class="left"><i class="ti ti-photo-check"></i> @lang('Approved Assets')</span>
                                </a>
                                <span class="right">{{ $widget['approvedAsset'] }}</span>
                            </li>
                            <li class="dashboard-widget-6__list-2__item list-primary">
                                <a href="{{ route('admin.reviewer.index') }}">
                                    <span class="left"><i class="ti ti-user-edit"></i> @lang('All Reviewers')</span>
                                </a>
                                <span class="right">{{ $widget['allReviewer'] }}</span>
                            </li>
                            <li class="dashboard-widget-6__list-2__item list-info">
                                <a href="{{ route('admin.reviewer.active') }}">
                                    <span class="left"><i class="ti ti-user-up"></i> @lang('Active Reviewers')</span>
                                </a>
                                <span class="right">{{ $widget['activeReviewer'] }}</span>
                            </li>
                            <li class="dashboard-widget-6__list-2__item list-danger">
                                <a href="{{ route('admin.reviewer.inactive') }}">
                                    <span class="left"><i class="ti ti-user-down"></i> @lang('Inactive Reviewers')</span>
                                </a>
                                <span class="right">{{ $widget['inactiveReviewer'] }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-xxl-3 col-md-6">
                <div class="dashboard-widget-6 dashboard-widget-6-lite">
                    <div class="dashboard-widget-6__list-2">
                        <ul>
                            <li class="dashboard-widget-6__list-2__item">
                                <a href="{{ route('admin.download.record') }}">
                                    <span class="left"><i class="ti ti-file-download"></i> @lang('Total Downloads')</span>
                                </a>
                                <span class="right">{{ $widget['totalDownload'] }}</span>
                            </li>
                            <li class="dashboard-widget-6__list-2__item list-primary">
                                <a href="{{ route('admin.collections') }}">
                                    <span class="left"><i class="ti ti-folder-plus"></i> @lang('Total Collections')</span>
                                </a>
                                <span class="right">{{ $widget['totalCollection'] }}</span>
                            </li>
                            <li class="dashboard-widget-6__list-2__item list-info">
                                <a href="{{ route('admin.filetype.index') }}">
                                    <span class="left"><i class="ti ti-photo-video"></i> @lang('Total File Types')</span>
                                </a>
                                <span class="right">{{ $widget['totalFileType'] }}</span>
                            </li>
                            <li class="dashboard-widget-6__list-2__item list-base">
                                <a href="{{ route('admin.plan.index') }}">
                                    <span class="left"><i class="ti ti-box-multiple"></i> @lang('Total Plans')</span>
                                </a>
                                <span class="right">{{ $widget['totalPlan'] }}</span>
                            </li>
                            <li class="dashboard-widget-6__list-2__item list-success">
                                <a href="{{ route('admin.advertisement.index') }}">
                                    <span class="left"><i class="ti ti-badge-ad"></i> @lang('Total Advertisements')</span>
                                </a>
                                <span class="right">{{ $widget['totalAds'] }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-6">
        <div class="mb-3">
            <h6 class="mb-0">@lang('Deposit') & @lang('Withdraw')</h6>
        </div>
        <div class="custom--card h-auto">
             <div class="card-header">
                  <small>@lang('Progress report for this year')</small>
             </div>
             <div class="card-body px-0 pb-0">
                  <div id="chart"></div>
             </div>
        </div>
   </div>
   
   <div class="col-xl-6">
        <div class="mb-3">
            <h6 class="mb-0">@lang('Latest Transactions')</h6>
        </div>
        <div class="custom--card border-0 h-auto table-responsive">
            <table class="table table-borderless table--striped table--responsive--md">
                <thead>
                    <tr>
                        <th>@lang('User')</th>
                        <th>@lang('TRX')</th>
                        <th>@lang('Initiated')</th>
                        <th>@lang('Amount')</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($latestTrx as $trx)
                        <tr>
                            <td>
                                <a href="{{ route('admin.user.details', $trx->user->id) }}">
                                    {{ $trx->user->fullname }}
                                </a>
                            </td>
                            <td>
                                <span class="fw-semibold">{{ $trx->trx }}</span>
                            </td>
                            <td>
                                <p>{{ showDateTime($trx->created_at) }}</p>
                            </td>
                            <td>
                                <span class="@if($trx->trx_type == '+') text--success @else text--danger @endif">
                                    {{ $trx->trx_type . ' ' . showAmount($trx->amount) . ' ' . __($setting->site_cur) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        @include('partials.noData')
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="col-12">
        <div class="change-password-modal">
            <div class="change-password-modal__body">
                <button class="btn btn--sm btn--icon btn-outline--secondary change-password-modal__close modal-close"><i class="ti ti-x"></i></button>
                <div class="change-password-modal__img">
                    <img src="{{ asset('assets/admin/images/light.png') }}" alt="Image">
                </div>
                <h3 class="change-password-modal__title">@lang('Security Advisory')</h3>
                <p class="change-password-modal__desc">@lang('Immediate Default Password and Username Change Required')</p>
                <div class="change-password-modal__btn">
                    <a href="{{ route('admin.profile') }}" class="btn btn--sm btn--base">@lang('Go For Change')</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('page-script-lib')
    <script src="{{ asset('assets/admin/js/page/apexcharts.js') }}"></script>
@endpush

@push('page-script')
    <script>
        "use strict";
        
        @if ($passwordAlert)
            (function($) {
                $('.change-password-modal').addClass('active');
            })(jQuery);
        @endif

        let months = [
            @foreach($months as $month)
                '{{ $month }}',
            @endforeach
        ];

        let totalDeposits = [
            @foreach($months as $month)
                {{ getAmount($depositsMonth->where('months', $month)->first()?->depositAmount) ?? 0 }},
            @endforeach
        ];

        let totalWithdrawals = [
            @foreach($months as $month)
                {{ getAmount($withdrawalMonth->where('months', $month)->first()?->withdrawAmount ?? 0) }},
            @endforeach
        ];

        var options = {
            series: [{
                name: 'Total Deposit',
                data: totalDeposits
            }, {
                name: 'Total Withdraw',
                data: totalWithdrawals
            }],
            chart: {
                type: 'bar',
                height: 392,
                toolbar: {
                    show: false
                }
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '50%',
                    endingShape: 'rounded'
                },
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                show: true,
                width: 2,
                colors: ['transparent']
            },
            xaxis: {
                categories: months,
            },
            yaxis: {
                title: {
                    text: "{{__($setting->cur_sym)}}",
                    style: {
                        color: '#7c97bb'
                    }
                }
            },
            grid: {
                xaxis: {
                    lines: {
                        show: false
                    }
                },
                yaxis: {
                    lines: {
                        show: false
                    }
                },
            },
            fill: {
                opacity: 1
            },
            tooltip: {
                y: {
                    formatter: function (val) {
                        return "{{__($setting->cur_sym)}}" + val + " "
                    }
                }
            }
        };

        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();
    </script>
@endpush