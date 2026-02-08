@extends($activeTheme . 'layouts.frontend')
@section('frontend')
@include($activeTheme . 'partials.breadcrumb')

<section class="pricing-plan py-120">
    <div class="container">
        <nav>
            <div class="nav nav-tabs custom--tab pb-0 border-0 justify-content-center" id="nav-tab" role="tablist">
                <button class="nav-link active" id="nav-daily-tab" data-bs-toggle="tab" data-bs-target="#nav-daily" type="button" role="tab" aria-controls="nav-daily" aria-selected="true">@lang('Daily')</button>
                <button class="nav-link" id="nav-weekly-tab" data-bs-toggle="tab" data-bs-target="#nav-weekly" type="button" role="tab" aria-controls="nav-weekly" aria-selected="false">@lang('Weekly')</button>
                <button class="nav-link" id="nav-monthly-tab" data-bs-toggle="tab" data-bs-target="#nav-monthly" type="button" role="tab" aria-controls="nav-monthly" aria-selected="false">@lang('Monthly')</button>
                <button class="nav-link" id="nav-quarterly-tab" data-bs-toggle="tab" data-bs-target="#nav-quarterly" type="button" role="tab" aria-controls="nav-quarterly" aria-selected="false">@lang('Quarter Year')</button>
                <button class="nav-link" id="nav-semiAnnually-tab" data-bs-toggle="tab" data-bs-target="#nav-semiAnnually" type="button" role="tab" aria-controls="nav-semiAnnually" aria-selected="false">@lang('Semi Annual')</button>
                <button class="nav-link" id="nav-annually-tab" data-bs-toggle="tab" data-bs-target="#nav-annually" type="button" role="tab" aria-controls="nav-annually" aria-selected="false">@lang('Annual')</button>
            </div>
        </nav>
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="nav-daily" role="tabpanel" aria-labelledby="nav-daily-tab" tabindex="0">
                <div class="row g-4 justify-content-center">
                    @forelse ($plans['daily'] as $dailyPlan)
                        <div class="col-lg-4 col-sm-6">
                            <div class="pricing-plan__card">
                                <div class="pricing-plan__card__bg bg-img" data-background-image="{{ getImage($activeThemeTrue . 'images/site/plan/' . $cardBg, '500x750') }}"></div>
                                <div class="pricing-plan__card__top" title="{{ __($dailyPlan->name) }}">
                                    <span class="pricing-plan__card__top__shape"></span>
                                    <div class="pricing-plan__card__icon">
                                        <img src="{{ getImage(getFilePath('plans') . '/' . $dailyPlan->image , getFileSize('plans')) }}" alt="{{ __($dailyPlan->name) }}">
                                    </div>
                                    <p class="pricing-plan__card__name">{{ __($dailyPlan->title) }}</p>
                                    <h3 class="pricing-plan__card__number">{{ $setting->cur_sym }}{{ showAmount($dailyPlan->price) }}</h3>
                                </div>
                                <ul class="pricing-plan__card__list">
                                    <li class="pricing-plan__card__list__item">
                                        @if ($dailyPlan->daily_limit == -1)
                                            @lang('Unlimited downloads')
                                        @else
                                            {{ $dailyPlan->daily_limit }} @lang('daily downloads')
                                        @endif
                                    </li>
                                </ul>
                                <div class="pricing-plan__card__bottom">
                                    @if ($user)
                                        <button class="btn btn--base planPurchaseBtn"
                                            data-id="{{ $dailyPlan->id }}"
                                            data-daily_limit="{{ $dailyPlan->daily_limit }}"
                                            data-plan_title="{{ __($dailyPlan->title) }}"
                                            data-plan_duration="{{ $dailyPlan->plan_duration }}"
                                            data-action="{{ route('user.plan.purchase', $dailyPlan->id) }}">
                                            @lang('Purchase Now')
                                        </button>
                                    @else
                                        <button class="btn btn--base signInfoBtn" data-label_text="{{ trans('Purchase Plan') }}">@lang('Purchase Now')</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        @include($activeTheme . 'partials.noDataDiv')
                    @endforelse
                </div>
            </div>

            <div class="tab-pane fade" id="nav-weekly" role="tabpanel" aria-labelledby="nav-weekly-tab" tabindex="0">
                <div class="row g-4 justify-content-center">
                    @forelse ($plans['weekly'] as $weeklyPlan)
                        <div class="col-lg-4 col-sm-6">
                            <div class="pricing-plan__card">
                                <div class="pricing-plan__card__bg bg-img" data-background-image="{{ getImage($activeThemeTrue . 'images/site/plan/' . $cardBg, '500x750') }}"></div>
                                <div class="pricing-plan__card__top" title="{{ __($weeklyPlan->name) }}">
                                    <span class="pricing-plan__card__top__shape"></span>
                                    <div class="pricing-plan__card__icon">
                                        <img src="{{ getImage(getFilePath('plans') . '/' . $weeklyPlan->image , getFileSize('plans')) }}" alt="{{ __($weeklyPlan->name) }}">
                                    </div>
                                    <p class="pricing-plan__card__name">{{ __($weeklyPlan->title) }}</p>
                                    <h3 class="pricing-plan__card__number">{{ $setting->cur_sym }}{{ showAmount($weeklyPlan->price) }}</h3>
                                </div>
                                <ul class="pricing-plan__card__list">
                                    <li class="pricing-plan__card__list__item">
                                        @if ($weeklyPlan->daily_limit == -1)
                                            @lang('Unlimited downloads')
                                        @else
                                            {{ $weeklyPlan->daily_limit }} @lang('daily downloads')
                                        @endif
                                    </li>
                                </ul>
                                <div class="pricing-plan__card__bottom">
                                    @if ($user)
                                        <button class="btn btn--base planPurchaseBtn"
                                            data-id="{{ $weeklyPlan->id }}"
                                            data-daily_limit="{{ $weeklyPlan->daily_limit }}"
                                            data-monthly_limit="{{ $weeklyPlan->monthly_limit }}"
                                            data-plan_title="{{ __($weeklyPlan->title) }}"
                                            data-period="{{ $weeklyPlan->plan_duration }}"
                                            data-action="{{ route('user.plan.purchase', $weeklyPlan->id) }}">
                                            @lang('Purchase Now')
                                        </button>
                                    @else
                                        <button class="btn btn--base signInfoBtn" data-label_text="{{ trans('Purchase Plan') }}">@lang('Purchase Now')</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        @include($activeTheme . 'partials.noDataDiv')
                    @endforelse
                </div>
            </div>

            <div class="tab-pane fade" id="nav-monthly" role="tabpanel" aria-labelledby="nav-monthly-tab" tabindex="0">
                <div class="row g-4 justify-content-center">
                    @forelse ($plans['monthly'] as $monthlyPlan)
                        <div class="col-lg-4 col-sm-6">
                            <div class="pricing-plan__card">
                                <div class="pricing-plan__card__bg bg-img" data-background-image="{{ getImage($activeThemeTrue . 'images/site/plan/' . $cardBg, '500x750') }}"></div>
                                <div class="pricing-plan__card__top" title="{{ __($monthlyPlan->name) }}">
                                    <span class="pricing-plan__card__top__shape"></span>
                                    <div class="pricing-plan__card__icon">
                                        <img src="{{ getImage(getFilePath('plans') . '/' . $monthlyPlan->image , getFileSize('plans')) }}" alt="{{ __($monthlyPlan->name) }}">
                                    </div>
                                    <p class="pricing-plan__card__name">{{ __($monthlyPlan->title) }}</p>
                                    <h3 class="pricing-plan__card__number">{{ $setting->cur_sym }}{{ showAmount($monthlyPlan->price) }}</h3>
                                </div>
                                <ul class="pricing-plan__card__list">
                                    <li class="pricing-plan__card__list__item">
                                        @if ($monthlyPlan->daily_limit == -1)
                                            @lang('Unlimited downloads')
                                        @else
                                            {{ $monthlyPlan->daily_limit }} @lang('daily downloads')
                                        @endif
                                    </li>
                                    <li class="pricing-plan__card__list__item">
                                        @if ($monthlyPlan->monthly_limit == -1)
                                            @lang('Unlimited downloads')
                                        @else
                                            {{ $monthlyPlan->monthly_limit }} @lang('monthly downloads')
                                        @endif
                                    </li>
                                </ul>
                                <div class="pricing-plan__card__bottom">
                                    @if ($user)
                                        <button class="btn btn--base planPurchaseBtn"
                                            data-id="{{ $monthlyPlan->id }}"
                                            data-daily_limit="{{ $monthlyPlan->daily_limit }}"
                                            data-monthly_limit="{{ $monthlyPlan->monthly_limit }}"
                                            data-plan_title="{{ __($monthlyPlan->title) }}"
                                            data-period="{{ $monthlyPlan->plan_duration }}"
                                            data-action="{{ route('user.plan.purchase', $monthlyPlan->id) }}">
                                            @lang('Purchase Now')
                                        </button>
                                    @else
                                        <button class="btn btn--base signInfoBtn" data-label_text="{{ trans('Purchase Plan') }}">@lang('Purchase Now')</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        @include($activeTheme . 'partials.noDataDiv')
                    @endforelse
                </div>
            </div>

            <div class="tab-pane fade" id="nav-quarterly" role="tabpanel" aria-labelledby="nav-quarterly-tab" tabindex="0">
                <div class="row g-4 justify-content-center">
                    @forelse ($plans['quarterAnnual'] as $quarterAnnualPlan)
                        <div class="col-lg-4 col-sm-6">
                            <div class="pricing-plan__card">
                                <div class="pricing-plan__card__bg bg-img" data-background-image="{{ getImage($activeThemeTrue . 'images/site/plan/' . $cardBg, '500x750') }}"></div>
                                <div class="pricing-plan__card__top" title="{{ __($quarterAnnualPlan->name) }}">
                                    <span class="pricing-plan__card__top__shape"></span>
                                    <div class="pricing-plan__card__icon">
                                        <img src="{{ getImage(getFilePath('plans') . '/' . $quarterAnnualPlan->image , getFileSize('plans')) }}" alt="{{ __($quarterAnnualPlan->name) }}">
                                    </div>
                                    <p class="pricing-plan__card__name">{{ __($quarterAnnualPlan->title) }}</p>
                                    <h3 class="pricing-plan__card__number">{{ $setting->cur_sym }}{{ showAmount($quarterAnnualPlan->price) }}</h3>
                                </div>
                                <ul class="pricing-plan__card__list">
                                    <li class="pricing-plan__card__list__item">
                                        @if ($quarterAnnualPlan->daily_limit == -1)
                                            @lang('Unlimited downloads')
                                        @else
                                            {{ $quarterAnnualPlan->daily_limit }} @lang('daily downloads')
                                        @endif
                                    </li>
                                    <li class="pricing-plan__card__list__item">
                                        @if ($quarterAnnualPlan->monthly_limit == -1)
                                            @lang('Unlimited downloads')
                                        @else
                                            {{ $quarterAnnualPlan->monthly_limit }} @lang('monthly downloads')
                                        @endif
                                    </li>
                                </ul>
                                <div class="pricing-plan__card__bottom">
                                    @if ($user)
                                        <button class="btn btn--base planPurchaseBtn"
                                            data-id="{{ $quarterAnnualPlan->id }}"
                                            data-daily_limit="{{ $quarterAnnualPlan->daily_limit }}"
                                            data-monthly_limit="{{ $quarterAnnualPlan->monthly_limit }}"
                                            data-plan_title="{{ __($quarterAnnualPlan->title) }}"
                                            data-period="{{ $quarterAnnualPlan->plan_duration }}"
                                            data-action="{{ route('user.plan.purchase', $quarterAnnualPlan->id) }}">
                                            @lang('Purchase Now')
                                        </button>
                                    @else
                                        <button class="btn btn--base signInfoBtn" data-label_text="{{ trans('Purchase Plan') }}">@lang('Purchase Now')</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        @include($activeTheme . 'partials.noDataDiv')
                    @endforelse
                </div>
            </div>
        
            <div class="tab-pane fade" id="nav-semiAnnually" role="tabpanel" aria-labelledby="nav-semiAnnually-tab" tabindex="0">
                <div class="row g-4 justify-content-center">
                    @forelse ($plans['semiAnnual'] as $semiAnnualPlan)
                        <div class="col-lg-4 col-sm-6">
                            <div class="pricing-plan__card">
                                <div class="pricing-plan__card__bg bg-img" data-background-image="{{ getImage($activeThemeTrue . 'images/site/plan/' . $cardBg, '500x750') }}"></div>
                                <div class="pricing-plan__card__top" title="{{ __($semiAnnualPlan->name) }}">
                                    <span class="pricing-plan__card__top__shape"></span>
                                    <div class="pricing-plan__card__icon">
                                        <img src="{{ getImage(getFilePath('plans') . '/' . $semiAnnualPlan->image , getFileSize('plans')) }}" alt="{{ __($semiAnnualPlan->name) }}">
                                    </div>
                                    <p class="pricing-plan__card__name">{{ __($semiAnnualPlan->title) }}</p>
                                    <h3 class="pricing-plan__card__number">{{ $setting->cur_sym }}{{ showAmount($semiAnnualPlan->price) }}</h3>
                                </div>
                                <ul class="pricing-plan__card__list">
                                    <li class="pricing-plan__card__list__item">
                                        @if ($semiAnnualPlan->daily_limit == -1)
                                            @lang('Unlimited downloads')
                                        @else
                                            {{ $semiAnnualPlan->daily_limit }} @lang('daily downloads')
                                        @endif
                                    </li>
                                    <li class="pricing-plan__card__list__item">
                                        @if ($semiAnnualPlan->monthly_limit == -1)
                                            @lang('Unlimited downloads')
                                        @else
                                            {{ $semiAnnualPlan->monthly_limit }} @lang('monthly downloads')
                                        @endif
                                    </li>
                                </ul>
                                <div class="pricing-plan__card__bottom">
                                    @if ($user)
                                        <button class="btn btn--base planPurchaseBtn"
                                            data-id="{{ $semiAnnualPlan->id }}"
                                            data-daily_limit="{{ $semiAnnualPlan->daily_limit }}"
                                            data-monthly_limit="{{ $semiAnnualPlan->monthly_limit }}"
                                            data-plan_title="{{ __($semiAnnualPlan->title) }}"
                                            data-period="{{ $semiAnnualPlan->plan_duration }}"
                                            data-action="{{ route('user.plan.purchase', $semiAnnualPlan->id) }}">
                                            @lang('Purchase Now')
                                        </button>
                                    @else
                                        <button class="btn btn--base signInfoBtn" data-label_text="{{ trans('Purchase Plan') }}">@lang('Purchase Now')</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        @include($activeTheme . 'partials.noDataDiv')
                    @endforelse
                </div>
            </div>
        
            <div class="tab-pane fade" id="nav-annually" role="tabpanel" aria-labelledby="nav-annually-tab" tabindex="0">
                <div class="row g-4 justify-content-center">
                    @forelse ($plans['annual'] as $annualPlan)
                        <div class="col-lg-4 col-sm-6">
                            <div class="pricing-plan__card">
                                <div class="pricing-plan__card__bg bg-img" data-background-image="{{ getImage($activeThemeTrue . 'images/site/plan/' . $cardBg, '500x750') }}"></div>
                                <div class="pricing-plan__card__top" title="{{ __($annualPlan->name) }}">
                                    <span class="pricing-plan__card__top__shape"></span>
                                    <div class="pricing-plan__card__icon">
                                        <img src="{{ getImage(getFilePath('plans') . '/' . $annualPlan->image , getFileSize('plans')) }}" alt="{{ __($annualPlan->name) }}">
                                    </div>
                                    <p class="pricing-plan__card__name">{{ __($annualPlan->title) }}</p>
                                    <h3 class="pricing-plan__card__number">{{ $setting->cur_sym }}{{ showAmount($annualPlan->price) }}</h3>
                                </div>
                                <ul class="pricing-plan__card__list">
                                    <li class="pricing-plan__card__list__item">
                                        @if ($annualPlan->daily_limit == -1)
                                            @lang('Unlimited downloads')
                                        @else
                                            {{ $annualPlan->daily_limit }} @lang('daily downloads')
                                        @endif
                                    </li>
                                    <li class="pricing-plan__card__list__item">
                                        @if ($annualPlan->monthly_limit == -1)
                                            @lang('Unlimited downloads')
                                        @else
                                            {{ $annualPlan->monthly_limit }} @lang('monthly downloads')
                                        @endif
                                    </li>
                                </ul>
                                <div class="pricing-plan__card__bottom">
                                    @if ($user)
                                        <button class="btn btn--base planPurchaseBtn"
                                            data-id="{{ $annualPlan->id }}"
                                            data-daily_limit="{{ $annualPlan->daily_limit }}"
                                            data-monthly_limit="{{ $annualPlan->monthly_limit }}"
                                            data-plan_title="{{ __($annualPlan->title) }}"
                                            data-period="{{ $annualPlan->plan_duration }}"
                                            data-action="{{ route('user.plan.purchase', $annualPlan->id) }}">
                                            @lang('Purchase Now')
                                        </button>
                                    @else
                                        <button class="btn btn--base signInfoBtn" data-label_text="{{ trans('Purchase Plan') }}">@lang('Purchase Now')</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        @include($activeTheme . 'partials.noDataDiv')
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="modal custom--modal fade" id="planPurchaseModal" tabindex="-1" aria-labelledby="planPurchaseModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
             <div class="modal-content">
                  <div class="modal-header">
                       <h2 class="modal-title fs-5" id="planPurchaseModal">@lang('Purchase Plan')</h2>
                       <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                       <form method="POST" class="row g-3 align-items-center">
                           @csrf

                            <input type="hidden" name="plan_id">

                            <div class="col-12 warning d-none">
                                <div class="alert alert--warning warningMessage">
                                    
                                </div>
                            </div>
                            
                            <div class="col-sm-4">
                                 <label class="col-form--label">@lang('Payment Type')</label>
                            </div>
                            <div class="col-sm-8">
                                <select class="form--control form-select" name="payment_type">
                                    <option value="" disabled selected>@lang('Select One')</option>
                                    <option value="wallet">@lang('From Wallet')</option>
                                    <option value="direct">@lang('Direct Payment')</option>
                                </select>
                            </div>
                            <div class="col-12">
                                 <button type="submit" class="btn btn--sm btn--base w-100">@lang('Purchase')</button>
                            </div>
                       </form>
                  </div>
             </div>
        </div>
    </div>
</section>

@include($activeTheme . 'partials.ads')

@endsection

@push('page-script')
    <script>
        (function($) {
            'use strict';

            $('.planPurchaseBtn').on('click', function() {
                let modal        = $('#planPurchaseModal');
                let planTitle    = $(this).data('plan_title');
                let planId       = $(this).data('id');
                let dailyLimit   = $(this).data('daily_limit');
                let monthlyLimit = $(this).data('monthly_limit');
                let period       = $(this).data('period');
                let action       = $(this).data('action');

                modal.find('[name=plan_id]').val(planId);
                modal.find('form').attr('action', action);

                $.ajax({
                    type: "GET",
                    url: "{{ route('user.plan.exist.check') }}",
                    success: function (response) {
                        if (response.success) {
                            modal.find('.warning').removeClass('d-none');
                            modal.find('.warningMessage').html(`<p class="small fw-semibold ">@lang('You have already purchased <strong>${response.planTitle}</strong> remaining <strong>${response.existingTime}</strong>. Your purchased plan will override for purchasing a new plan')</p>`);
                            
                            modal.modal('show');
                        } else {
                            modal.modal('show');
                        }
                    }
                });
            });

            $('#planPurchaseModal').on('hidden.bs.modal', function() {
                $(this).find('form')[0].reset();
                $(this).find('.planTitle, .dailyDownload, .monthlyDownload').text('');
                $(this).find('[name=plan_id]').val('');
                $(this).find('form').attr('action',  '');
            });
        })(jQuery);
    </script>
@endpush