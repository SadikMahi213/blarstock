@extends('admin.layouts.master')

@section('master')
<div class="d-flex justify-content-center">
    <div class="col-lg-8 col-md-8">
        <div class="custom--card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="title">
                    @lang('Plan Purchase Commission')
                     @if ($setting->referral)
                        <span class="badge badge--success">@lang('Active')</span>
                    @else
                        <span class="badge badge--warning">@lang('Inactive')</span>
                    @endif
                </h3>

                @if ($setting->referral)
                    <button type="button" class="btn btn--sm btn--warning decisionBtn"
                            data-question="@lang('Are you confirming the inactivation of the referral')?"
                            data-action="{{ route('admin.referral.status') }}">
                        <i class="ti ti-ban"></i> @lang('Inactive')
                    </button>
                @else
                    <button type="button" class="btn btn--sm btn--success decisionBtn"
                            data-question="@lang('Are you confirming the activation of the referral')?"
                            data-action="{{ route('admin.referral.status') }}">
                        <i class="ti ti-circle-check"></i> @lang('Active')
                    </button>
                @endif
            </div>
            <div class="card-body p-5">
                <h3 class="divider-title"><span>@lang('Existing Levels')</span></h3>

                <ul class="list--group mb-4">
                     @forelse ($levels as $level)
                        <li class="list-group-item d-flex justify-content-between fw-semibold">
                            @lang('Level'){{ ' #' . $level->level }}<span class="fw-bold">{{ $level->percent . '%' }}</span>
                        </li>
                    @empty
                        @include('partials.noData')
                    @endforelse
                </ul>

                <h3 class="divider-title"><span>@lang('Generate Level')</span></h3>
                <form class="level-generation-form input--group">
                    <input type="number" class="form--control" placeholder="@lang('How many level do you want?')">
                    <button type="submit" class="btn btn--base">@lang('Generate')</button>
                </form>
                <div class="new-referral-levels pt-3 d-none">
                    <p class="text--warning fw-bold text-center mb-2">@lang('The existing configuration will be replaced when a new one is generated')</p>
                    <form action="{{ route('admin.referral.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="commission_type" value="plan_purchase">
                        <div class="new-referral-form d-flex flex-column gap-3 mb-3"></div>
                        <button type="submit" class="btn btn--base w-100">@lang('Submit')</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<x-decisionModal />
@endsection

@push('page-style')
    <style>
        .divider-title {
            text-align: center;
            font-size: 1.25rem;
            line-height: 1;
            color: hsl(var(--base));
            position: relative;
            margin-top: -4px;
            margin-bottom: 20px;
            z-index: 2;
        }

        .divider-title::after {
            content: "";
            position: absolute;
            top: 50%;
            left: 0;
            width: 100%;
            height: 1px;
            background: hsl(var(--secondary-l-800));
            z-index: -1;
        }

        .divider-title span {
            background: hsl(var(--white));
            padding: 0 7px;
        }
    </style>
@endpush

@push('page-script')
    <script>
        (function ($) {
            "use strict"

            $('.level-generation-form').on('submit', function (e) {
                e.preventDefault()

                let levelCount = $(this).find('input').val()
                let newReferralLevels = $(this).siblings('.new-referral-levels')


                if (levelCount) {
                    newReferralLevels.removeClass('d-none')

                    // Clear any existing levels before adding new ones
                    newReferralLevels.find('.new-referral-form').empty()

                    for (let i = 1; i <= levelCount; i++) {
                        newReferralLevels.find('.new-referral-form').append(`
                            <div class="new-referral-form-group d-flex gap-3">
                                <div class="input--group flex-grow-1">
                                    <span class="input-group-text">@lang('Level') #<span class="level-count">${i}</span></span>
                                    <input type="number" step="0.01" min="0" class="form--control form--control--sm" name="percent[]" placeholder="@lang('Enter Commission Percentage')" required>
                                    <span class="input-group-text">%</span>
                                </div>
                                <button type="button" class="btn btn--sm btn--danger px-2 flex-shrink-0 remove-new-level">
                                    <i class="ti ti-x"></i>
                                </button>
                            </div>
                        `)
                    }
                } else {
                    newReferralLevels.addClass('d-none')

                    // Clear any existing levels before adding new ones
                    newReferralLevels.find('.new-referral-form').empty()
                    showToasts('error', 'Level field is required');
                }


                $(this).find('input').val('')
            })

            // Handle click event to remove a level and re-index
            $(document).on('click', '.remove-new-level', function () {
                let referralForm = $(this).closest('.new-referral-form')

                $(this).closest('.new-referral-form-group').remove()

                updateLevelCounts(referralForm)
                checkIfNoLevelsLeft(referralForm)
            })

            // Function to re-index the level counts within a specific form
            function updateLevelCounts(referralForm) {
                referralForm.find('.level-count').each(function (index) {
                    $(this).text(index + 1)
                })
            }

            // Function to check if there are no levels left within a specific form
            function checkIfNoLevelsLeft(referralForm) {
                if (referralForm.find('.new-referral-form-group').length === 0) {
                    referralForm.closest('.new-referral-levels').addClass('d-none')
                }
            }
        })(jQuery)
    </script>
@endpush
