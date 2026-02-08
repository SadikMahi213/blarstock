@extends($activeTheme . 'layouts.auth')
@section('auth')
    <div class="py-120">
        <div class="row gy-4">
            @if(auth()->user()->kc == ManageStatus::UNVERIFIED)
                <div class="col-12">
                    <div class="alert alert--info" role="alert">
                        <h4 class="alert__title">{{ __($kycContent?->data_info?->Verification_required_heading ?? '') }}</h4>
                        <hr>
                        <p class="mb-0">{{ __($kycContent?->data_info?->Verification_required_details ?? '') }}  <a href="{{ route('user.kyc.form') }}">@lang('Click Here to Verify')</a></p>
                    </div>
                </div>
            @elseif(auth()->user()->kc == ManageStatus::PENDING)
                <div class="col-12">
                    <div class="alert alert--warning" role="alert">
                        <h4 class="alert__title">{{ __($kycContent?->data_info?->Verification_pending_heading ?? '') }}</h4>
                        <hr>
                        <p class="mb-0">{{ __($kycContent?->data_info?->Verification_pending_details ?? '') }}  <a href="{{ route('user.kyc.data') }}">@lang('See KYC Data')</a></p>
                    </div>
                </div>
            @endif

            <div class="col-12">
                <div class="custom--card border-0">
                    <div class="card-header rounded-2">
                        <div class="row g-3 align-items-center">
                            @if ($user->plan_id && $user->plan->status == ManageStatus::ACTIVE)
                                <div class="col-sm-6">
                                    <div class="d-flex align-items-center gap-2 justify-content-sm-start justify-content-center">
                                        <p class="lh-1 fw-semibold">@lang('Activated Plan'):</p>
                                        <p class="lh-1">{{ __($user->plan->name) }} - {{ __($user->plan->title) }}</p>
                                    </div>
                                </div>
                            
                                <div class="col-sm-6">
                                    @if ($user->plan_expired_date < now())
                                        <div class="d-flex align-items-center gap-2 justify-content-sm-end justify-content-center lh-1 fw-semibold countdown">
                                            <span class="badge badge--warning">@lang('Expired')</span>
                                        </div>    
                                    @else
                                        <div class="d-flex align-items-center gap-2 justify-content-sm-end justify-content-center lh-1 fw-semibold countdown"
                                            data-expiry="{{ \Carbon\Carbon::parse($user->plan_expired_date)->format('Y-m-d H:i:s') }}">
                                            <span class="days">--d</span>:<span class="hours">--h</span>:<span class="minutes">--m</span>:<span class="seconds">--s</span>
                                        </div>
                                    @endif
                                </div>    
                            @else
                                <div class="col-sm-6">
                                    <div class="d-flex align-items-center gap-2 justify-content-sm-start justify-content-center">
                                        <p class="lh-1 fw-semibold">@lang('Activated Plan'):</p>
                                        <p class="lh-1">@lang('No Plan Activated')</p>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="d-flex align-items-center gap-2 justify-content-sm-end justify-content-center lh-1 fw-semibold">
                                        <span>--</span>:<span>--</span>:<span>--</span>:<span>--</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>


            @if ($user->author_status == ManageStatus::AUTHOR_APPROVED)
                <div class="col-12">
                    <div class="row g-4 justify-content-center">
                        <div class="col-xxxl-2 col-xl-3 col-md-4 col-sm-6 col-xsm-8">
                            <span class="dashboard-card">
                                <div class="dashboard-card__icon">
                                    <img src="{{ getImage($activeThemeTrue . 'images/site/user_dashboard/' . $dashboardContent?->data_info?->balance, '120x115') }}" alt="Balance">
                                </div>
                                <div class="dashboard-card__txt">
                                    <span class="dashboard-card__title">@lang('Balance')</span>
                                    <span class="dashboard-card__number">{{ $setting->cur_sym }}{{ showAmount($user->balance) }}</span>
                                </div>
                            </span>
                        </div>
                        <div class="col-xxxl-2 col-xl-3 col-md-4 col-sm-6 col-xsm-8">
                            <span class="dashboard-card">
                                <div class="dashboard-card__icon">
                                    <img src="{{ getImage($activeThemeTrue . 'images/site/user_dashboard/' . $dashboardContent?->data_info?->deposit, '120x105') }}" alt="Deposits">
                                </div>
                                <div class="dashboard-card__txt">
                                    <span class="dashboard-card__title">@lang('Deposits')</span>
                                    <span class="dashboard-card__number">{{ $setting->cur_sym }}{{ showAmount($user->deposits->sum('amount')) }}</span>
                                </div>
                            </span>
                        </div>
                        <div class="col-xxxl-2 col-xl-3 col-md-4 col-sm-6 col-xsm-8">
                            <span class="dashboard-card">
                                <div class="dashboard-card__icon">
                                    <img src="{{ getImage($activeThemeTrue . 'images/site/user_dashboard/' . $dashboardContent?->data_info?->withdraw, '120x120') }}" alt="Withdrawals">
                                </div>
                                <div class="dashboard-card__txt">
                                    <span class="dashboard-card__title">@lang('Withdrawals')</span>
                                    <span class="dashboard-card__number">{{ $setting->cur_sym }}{{ showAmount($user->withdrawals->sum('amount')) }}</span>
                                </div>
                            </span>
                        </div>
                        <div class="col-xxxl-2 col-xl-3 col-md-4 col-sm-6 col-xsm-8">
                            <span class="dashboard-card">
                                <div class="dashboard-card__icon">
                                    <img src="{{ getImage($activeThemeTrue . 'images/site/user_dashboard/' . $dashboardContent?->data_info?->referral, '120x105') }}"
                                        alt="Referral Bonus">
                                </div>
                                <div class="dashboard-card__txt">
                                    <span class="dashboard-card__title">@lang('Referral Bonus')</span>
                                    <span class="dashboard-card__number">{{ $setting->cur_sym }}{{ showAmount($user->commissions->sum('commission_amount')) }}</span>
                                </div>
                            </span>
                        </div>
                        <div class="col-xxxl-2 col-xl-3 col-md-4 col-sm-6 col-xsm-8">
                            <span class="dashboard-card">
                                <div class="dashboard-card__icon">
                                    <img src="{{ getImage($activeThemeTrue . 'images/site/user_dashboard/' . $dashboardContent?->data_info?->earning, '120x120') }}" alt="Earnings">
                                </div>
                                <div class="dashboard-card__txt">
                                    <span class="dashboard-card__title">@lang('Earnings')</span>
                                    <span class="dashboard-card__number">{{ $setting->cur_sym }}{{ showAmount($user->earnings->sum('amount')) }}</span>
                                </div>
                            </span>
                        </div>
                        <div class="col-xxxl-2 col-xl-3 col-md-4 col-sm-6 col-xsm-8">
                            <span class="dashboard-card">
                                <div class="dashboard-card__icon">
                                    <img src="{{ getImage($activeThemeTrue . 'images/site/user_dashboard/' . $dashboardContent?->data_info?->asset, '120x105') }}" alt="Assets">
                                </div>
                                <div class="dashboard-card__txt">
                                    <span class="dashboard-card__title">@lang('Assets')</span>
                                    <span class="dashboard-card__number">{{ formatNumber($user->approvedImages->count()) }}</span>
                                </div>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="custom--card">
                        <div class="card-header">
                            <h3 class="title">@lang('Earning Last 30 Days')</h3>
                        </div>
                        <div id="earning__last__30__days"></div>
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="custom--card border-0">
                        <div class="card-header">
                            <h3 class="title">@lang('Latest Transactions')</h3>
                        </div>
                        <table class="table table-borderless table--striped top-rounded-0 table--responsive--md">
                            <thead>
                                <tr>
                                    <th>@lang('TRX')</th>
                                    <th>@lang('Transacted')</th>
                                    <th>@lang('Amount')</th>
                                    <th>@lang('Post Balance')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($transactions as $transaction)
                                    <tr>
                                        <td>{{ $transaction->trx }}</td>
                                        <td>
                                            <div>
                                                <p>{{ showDateTime($transaction->created_at, 'M d, Y') }}</p>
                                                <p>{{ diffForHumans($transaction->created_at) }}</p>
                                            </div>
                                        </td>
                                        <td><span class="text--success">{{ $transaction->trx_type }} {{ showAmount($transaction->amount) }} {{ __($setting->site_cur) }}</span></td>
                                        <td>{{ showAmount($user->balance) }} {{ __($setting->site_cur) }}</td>
                                    </tr>
                                @empty
                                    @include($activeTheme . 'partials.noData')
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <div class="col-12">
                    <div class="row g-4 justify-content-center">
                        <div class="col-xxxl-3 col-xl-3 col-md-4 col-sm-6 col-xsm-8">
                            <span class="dashboard-card">
                                <div class="dashboard-card__icon">
                                    <img src="{{ getImage($activeThemeTrue . 'images/site/user_dashboard/' . $dashboardContent?->data_info?->balance, '120x115') }}" alt="Balance">
                                </div>
                                <div class="dashboard-card__txt">
                                    <span class="dashboard-card__title">@lang('Balance')</span>
                                    <span class="dashboard-card__number">{{ $setting->cur_sym }}{{ showAmount($user->balance) }}</span>
                                </div>
                            </span>
                        </div>
                        <div class="col-xxxl-3 col-xl-3 col-md-4 col-sm-6 col-xsm-8">
                            <span class="dashboard-card">
                                <div class="dashboard-card__icon">
                                    <img src="{{ getImage($activeThemeTrue . 'images/site/user_dashboard/' . $dashboardContent?->data_info?->deposit, '120x105') }}" alt="Deposits">
                                </div>
                                <div class="dashboard-card__txt">
                                    <span class="dashboard-card__title">@lang('Deposits')</span>
                                    <span class="dashboard-card__number">{{ $setting->cur_sym }}{{ showAmount($user->deposits->sum('amount')) }}</span>
                                </div>
                            </span>
                        </div>
                        <div class="col-xxxl-3 col-xl-3 col-md-4 col-sm-6 col-xsm-8">
                            <span class="dashboard-card">
                                <div class="dashboard-card__icon">
                                    <img src="{{ getImage($activeThemeTrue . 'images/site/user_dashboard/' . $dashboardContent?->data_info?->withdraw, '120x120') }}" alt="Withdrawals">
                                </div>
                                <div class="dashboard-card__txt">
                                    <span class="dashboard-card__title">@lang('Withdrawals')</span>
                                    <span class="dashboard-card__number">{{ $setting->cur_sym }}{{ showAmount($user->withdrawals->sum('amount')) }}</span>
                                </div>
                            </span>
                        </div>
                        <div class="col-xxxl-3 col-xl-3 col-md-4 col-sm-6 col-xsm-8">
                            <span class="dashboard-card">
                                <div class="dashboard-card__icon">
                                    <img src="{{ getImage($activeThemeTrue . 'images/site/user_dashboard/' . $dashboardContent?->data_info?->referral, '120x105') }}"
                                        alt="Referral Bonus">
                                </div>
                                <div class="dashboard-card__txt">
                                    <span class="dashboard-card__title">@lang('Referral Bonus')</span>
                                    <span class="dashboard-card__number">{{ $setting->cur_sym }}{{ showAmount($user->commissions->sum('commission_amount')) }}</span>
                                </div>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-xl-12">
                    <div class="custom--card border-0">
                        <div class="card-header">
                            <h3 class="title">@lang('Latest Transactions')</h3>
                        </div>
                        <table class="table table-borderless table--striped top-rounded-0 table--responsive--md">
                            <thead>
                                <tr>
                                    <th>@lang('TRX')</th>
                                    <th>@lang('Transacted')</th>
                                    <th>@lang('Amount')</th>
                                    <th>@lang('Post Balance')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($transactions as $transaction)
                                    <tr>
                                        <td>{{ $transaction->trx }}</td>
                                        <td>
                                            <div>
                                                <p>{{ showDateTime($transaction->created_at, 'M d, Y') }}</p>
                                                <p>{{ diffForHumans($transaction->created_at) }}</p>
                                            </div>
                                        </td>
                                        <td><span class="text--success">{{ $transaction->trx_type }} {{ showAmount($transaction->amount) }} {{ __($setting->site_cur) }}</span></td>
                                        <td>{{ showAmount($user->balance) }} {{ __($setting->site_cur) }}</td>
                                    </tr>
                                @empty
                                    @include($activeTheme . 'partials.noData')
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- User's Uploaded Images Section -->
                @if($user->author_status == ManageStatus::AUTHOR_APPROVED && $user->approvedImages->count() > 0)
                    <div class="col-12">
                        <div class="custom--card">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h3 class="title">@lang('Your Uploaded Assets') ({{ $user->approvedImages->count() }})</h3>
                                    <a href="{{ route('user.asset.index') }}" class="btn btn--sm btn--base">@lang('View All')</a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    @foreach($user->approvedImages->take(6) as $image)
                                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                                            <div class="dashboard-asset-card">
                                                <div class="asset-thumbnail">
                                                    @if($image->image_name)
                                                        <img src="{{ getImage(getFilePath('stockImage') . '/' . $image->image_name) }}" 
                                                             alt="{{ $image->title }}" 
                                                             class="img-fluid rounded"
                                                             style="height: 150px; object-fit: cover; width: 100%;">
                                                    @else
                                                        <div class="no-image-placeholder d-flex align-items-center justify-content-center" 
                                                             style="height: 150px; background-color: #f8f9fa; border-radius: 4px;">
                                                            <i class="ti ti-photo-off fs-1 text-muted"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="asset-info mt-2">
                                                    <h6 class="asset-title text-truncate" title="{{ $image->title }}">{{ strLimit($image->title, 30) }}</h6>
                                                    <div class="d-flex justify-content-between align-items-center mt-1">
                                                        <span class="badge bg-success">@lang('Approved')</span>
                                                        <small class="text-muted">{{ showDateTime($image->created_at, 'M d') }}</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                
                                @if($user->approvedImages->count() > 6)
                                    <div class="text-center mt-4">
                                        <a href="{{ route('user.asset.index') }}" class="btn btn--base">@lang('View All Assets') ({{ $user->approvedImages->count() }})</a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @elseif($user->author_status == ManageStatus::AUTHOR_APPROVED)
                    <div class="col-12">
                        <div class="custom--card text-center py-5">
                            <div class="card-body">
                                <i class="ti ti-photo-off fs-1 text-muted mb-3"></i>
                                <h4 class="mb-2">@lang('No Assets Uploaded Yet')</h4>
                                <p class="text-muted mb-4">@lang('Upload your first asset to get started')</p>
                                <a href="{{ route('user.asset.upload') }}" class="btn btn--base">
                                    <i class="ti ti-upload"></i> @lang('Upload Asset')
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </div>
@endsection

@push('page-script')
    <script>
        (function($) {
            'use strict';

            if($("#earning__last__30__days").length) {
                function getLast30Days() {
                    const dates = [];
                    const today = new Date();
                    for (let i = 0; i < 30; i++) {
                    const date = new Date(today);
                    date.setDate(today.getDate() - i);
                    const year = date.getFullYear();
                    const month = String(date.getMonth() + 1).padStart(2, '0');
                    const day = String(date.getDate()).padStart(2, '0');
                    dates.push(`${year}-${month}-${day}`);
                    }
                    return dates.reverse();
                }
                const last30Days = getLast30Days();

                let earningsData = [
                    @foreach ($formattedData as $index => $value)
                        {{ $value }}@if(!$loop->last), @endif
                    @endforeach
                ];


                var earning__last__30__days__options = {
                    series: [{
                        name: 'series1',
                        data: earningsData
                    }],
                    chart: {
                        height: 385,
                        type: 'area',
                        toolbar: {
                            show: false,
                        }
                    },
                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        curve: 'smooth',
                        width: 2
                    },
                    xaxis: {
                        type: 'datetime',
                        categories: last30Days
                    },
                    tooltip: {
                        x: {
                            format: 'dd/MM/yy HH:mm'
                        },
                    },
                    responsive: [{
                        breakpoint: 768,
                        options: {
                            chart: {
                            height: 300,
                            }
                        },
                    },{
                        breakpoint: 576,
                        options: {
                            chart: {
                            height: 250,
                            }
                        },
                    }]
                };

                var chart = new ApexCharts(document.querySelector("#earning__last__30__days"), earning__last__30__days__options);
                chart.render();
            }

            $('.countdown[data-expiry]').each(function () {
                let $this  = $(this);
                let expiry = $this.data('expiry');
                startCountdown($this, expiry);
            });

            function startCountdown($container, expiryTime) {
                let endTime = new Date(expiryTime).getTime();

                function updateCountdown() {
                    let now      = new Date().getTime();
                    let distance = endTime - now;

                    if (distance <= 0) {
                        $container.html(`<span>@lang('Expired')</span>`);
                        return;
                    }

                    let days    = Math.floor(distance / (1000 * 60 * 60 * 24));
                    let hours   = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    let seconds = Math.floor((distance % (1000 * 60)) / 1000);

                    $container.find('.days').text(days + 'd');
                    $container.find('.hours').text(hours + 'h');
                    $container.find('.minutes').text(minutes + 'm');
                    $container.find('.seconds').text(seconds + 's');

                    setTimeout(updateCountdown, 1000);
                }

                updateCountdown();
            }
        })(jQuery);
    </script>
@endpush