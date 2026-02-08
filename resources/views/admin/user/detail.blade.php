@extends('admin.layouts.master')

@section('master')
    <div class="col-12">
        <div class="dashboard-widget-row">
            <a href="{{ route('admin.transaction.index') }}?search={{ $user->username }}" class="dashboard-widget-2 dashboard-widget-2__success">
                <div class="dashboard-widget-2__icon">
                    <i class="ti ti-coins"></i>
                </div>
                <h3 class="dashboard-widget-2__number">{{ showAmount($user->balance) }} {{ __($setting->site_cur) }}</h3>
                <p class="dashboard-widget-2__txt">@lang('Balance')</p>
                <div class="dashboard-widget-2__vector">
                    <img src="{{ asset('assets/admin/images/users/user-balance.png') }}" alt="Image">
                </div>
            </a>
            <a href="{{ route('admin.deposit.index') }}?search={{ $user->username }}" class="dashboard-widget-2 dashboard-widget-2__warning">
                <div class="dashboard-widget-2__icon">
                    <i class="ti ti-pig"></i>
                </div>
                <h3 class="dashboard-widget-2__number">{{ showAmount($totalDeposit) }} {{ __($setting->site_cur) }}</h3>
                <p class="dashboard-widget-2__txt">@lang('Total Deposits')</p>
                <div class="dashboard-widget-2__vector">
                    <img src="{{ asset('assets/admin/images/users/user-deposit.png') }}" alt="Image">
                </div>
            </a>
            <a href="{{ route('admin.withdraw.index') }}?search={{ $user->username }}" class="dashboard-widget-2 dashboard-widget-2__danger">
                <div class="dashboard-widget-2__icon">
                    <i class="ti ti-credit-card-pay"></i>
                </div>
                <h3 class="dashboard-widget-2__number">{{ showAmount($totalWithdrawal) }} {{ __($setting->site_cur) }}</h3>
                <p class="dashboard-widget-2__txt">@lang('Total Withdrawals')</p>
                <div class="dashboard-widget-2__vector">
                    <img src="{{ asset('assets/admin/images/users/user-withdraw.png') }}" alt="Image">
                </div>
            </a>
            <a href="{{ route('admin.transaction.index') }}?search={{ $user->username }}" class="dashboard-widget-2 dashboard-widget-2__info">
                <div class="dashboard-widget-2__icon">
                    <i class="ti ti-arrows-right-left"></i>
                </div>
                <h3 class="dashboard-widget-2__number">{{ $totalTransactions }}</h3>
                <p class="dashboard-widget-2__txt">@lang('Total Transactions')</p>
                <div class="dashboard-widget-2__vector">
                    <img src="{{ asset('assets/admin/images/users/user-transaction.png') }}" alt="Image">
                </div>
            </a>
        </div>
    </div>
    <div class="col-12">
        <div class="row g-lg-4 g-3">
            <div class="col-xl-3 col-sm-6">
                <a href="{{ route('admin.transaction.index', ['search' => $user->username]) }}" class="dashboard-widget-4">
                    <div class="dashboard-widget-4__content">
                        <div class="dashboard-widget-4__icon">
                            <i class="ti ti-coins"></i>
                        </div>
                        <p class="dashboard-widget-4__txt">@lang('Balance')</p>
                    </div>
                    <h3 class="dashboard-widget-4__number">{{ showAmount($user->balance) . ' ' . __($setting->site_cur) }}</h3>
                    <div class="dashboard-widget-4__vector">
                        <i class="ti ti-coins"></i>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-sm-6">
                <a href="{{ route('admin.deposit.index', ['search' => $user->username]) }}" class="dashboard-widget-4 dashboard-widget-4__success">
                    <div class="dashboard-widget-4__content">
                        <div class="dashboard-widget-4__icon">
                            <i class="ti ti-wallet"></i>
                        </div>
                        <p class="dashboard-widget-4__txt">@lang('Total Deposit')</p>
                    </div>
                    <h3 class="dashboard-widget-4__number">{{ showAmount($totalDeposit) . ' ' . __($setting->site_cur) }}</h3>
                    <div class="dashboard-widget-4__vector">
                        <i class="ti ti-wallet"></i>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-sm-6">
                <a href="{{ route('admin.withdraw.index', ['search' => $user->username]) }}" class="dashboard-widget-4 dashboard-widget-4__warning">
                    <div class="dashboard-widget-4__content">
                        <div class="dashboard-widget-4__icon">
                            <i class="ti ti-cash-banknote"></i>
                        </div>
                        <p class="dashboard-widget-4__txt">@lang('Total Withdrawal')</p>
                    </div>
                    <h3 class="dashboard-widget-4__number">{{ showAmount($totalWithdrawal) . ' ' . __($setting->site_cur) }}</h3>
                    <div class="dashboard-widget-4__vector">
                        <i class="ti ti-cash-banknote"></i>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-sm-6">
                <a href="{{ route('admin.transaction.index', ['search' => $user->username]) }}" class="dashboard-widget-4 dashboard-widget-4__info">
                    <div class="dashboard-widget-4__content">
                        <div class="dashboard-widget-4__icon">
                            <i class="ti ti-transform"></i>
                        </div>
                        <p class="dashboard-widget-4__txt">@lang('Total Transactions')</p>
                    </div>
                    <h3 class="dashboard-widget-4__number">{{ $totalTransactions }}</h3>
                    <div class="dashboard-widget-4__vector">
                        <i class="ti ti-transform"></i>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="custom--modal modal fade" id="balanceUpdateModal" tabindex="-1" aria-labelledby="balanceUpdateModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                 <div class="modal-content">
                      <div class="modal-header">
                           <h2 class="modal-title" id="balanceUpdateModalLabel">@lang('Add Balance')</h2>
                           <button type="button" class="btn btn--sm btn--icon btn-outline--secondary modal-close" data-bs-dismiss="modal" aria-label="Close"><i class="ti ti-x"></i></button>
                      </div>
                      <form action="{{ route('admin.user.add.sub.balance', $user->id) }}" method="POST">
                            @csrf

                            <input type="hidden" name="act">

                            <div class="modal-body">
                                <div class="row g-3">
                                    <div class="rol-12">
                                        <label class="form--label required">@lang('Amount')</label>
                                        <div class="input--group">
                                            <input type="number" step="any" min="0" class="form--control form--control--sm" name="amount" placeholder="@lang('Kindly enter an amount that is positive')" required>
                                            <span class="input-group-text">{{ __($setting->site_cur) }}</span>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <label class="form--label required">@lang('Remark')</label>
                                        <textarea class="form--control form--control--sm" name="remark" placeholder="@lang('Remark')" required></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer gap-2">
                                <button type="button" class="btn btn--sm btn--secondary" data-bs-dismiss="modal">@lang('Close')</button>
                                <button class="btn btn--sm btn--base" type="submit">@lang('Submit')</button>
                            </div>
                      </form>
                 </div>
            </div>
       </div>
    </div>

    <div class="col-12">
        <div class="custom--modal modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                 <div class="modal-content">
                    <button type="button" class="btn btn--sm btn--icon btn-outline--secondary modal-close" data-bs-dismiss="modal" aria-label="Close"><i class="ti ti-x"></i></button>

                    <form action="{{ route('admin.user.status', $user->id) }}" method="POST">
                        @csrf
                        <div class="modal-body text-center modal-alert">
                            <div class="modal-thumb">
                                <img src="{{ asset('assets/admin/images/light.png') }}" alt="Image">
                            </div>
                            <h2 class="modal-title" id="statusModalLabel">
                                {{ $user->status ? trans('Ban User') : trans('Unban User') }}
                            </h2>
                            <p class="mb-3">
                                @if ($user->status)
                                    @lang('Banning this user will restrict their access to the dashboard').
                                @else
                                    @lang('Do you confirm the action to unban on this user')?
                                @endif
                            </p>

                            @if ($user->status)
                                <label class="form--label required">@lang('Reason') :</label>
                                <textarea class="form--control form--control--sm mb-3" name="ban_reason" required></textarea>
                            @else
                                <b class="mb-2">@lang('Banning reason was')</b>
                                <p class="mb-4">{{ __($user->ban_reason) }}</p>
                            @endif

                            <div class="d-flex gap-2 justify-content-center">
                                <button type="button" class="btn btn--sm btn--secondary" data-bs-dismiss="modal">@lang('No')</button>
                                <button class="btn btn--sm btn--base" type="submit">@lang('Yes')</button>
                            </div>
                        </div>
                    </form>
                 </div>
            </div>
       </div>
    </div>
@endsection

@push('breadcrumb')
    <div class="custom--dropdown">
        <button class="btn btn--sm btn--icon btn--base" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="ti ti-dots-vertical"></i>
        </button>
        <ul class="dropdown-menu">
            <li>
                <a href="{{route('admin.user.login', $user->id)}}" target="_blank" class="dropdown-item text--info"><span class="dropdown-icon"><i class="ti ti-login-2"></i></span> @lang('Login as User')</a>
            </li>
            <li>
                <button type="button" class="dropdown-item text--success balanceUpdateBtn" data-act="add"><span class="dropdown-icon"><i class="ti ti-circle-plus"></i></span> @lang('Add Balance')</button>
            </li>
            <li>
                <button type="button" class="dropdown-item text--warning balanceUpdateBtn" data-act="sub"><span class="dropdown-icon"><i class="ti ti-circle-minus"></i></span> @lang('Sub Balance')</button>
            </li>
            <li>
                @if ($user->status)
                    <button type="button" class="dropdown-item text--danger" data-bs-toggle="modal" data-bs-target="#statusModal">
                        <span class="dropdown-icon"><i class="ti ti-user-cancel"></i></span> @lang('Ban User')
                    </button>
                @else
                    <button type="button" class="dropdown-item text--base" data-bs-toggle="modal" data-bs-target="#statusModal">
                        <span class="dropdown-icon"><i class="ti ti-user-check"></i></span> @lang('Unban User')
                    </button>
                @endif
            </li>
        </ul>
    </div>
@endpush

@push('page-script')
    <script>
        (function($){
            "use strict";

            $('.balanceUpdateBtn').on('click', function () {
                let modal = $('#balanceUpdateModal');
                let act   = $(this).data('act');

                modal.find('[name=act]').val(act);

                if (act == 'add') {
                    modal.find('.modal-title').text(`@lang('Add Balance')`);
                }else{
                    modal.find('.modal-title').text(`@lang('Subtract Balance')`);
                }

                modal.modal('show');
            });

            let mobileElement = $('.mobile-code');

            $('[name=country]').change(function() {
                mobileElement.text(`+${$('[name=country] :selected').data('mobile_code')}`);
            });

            $('[name=country]').val('{{$user->country_code}}');

            let dialCode     = $('[name=country] :selected').data('mobile_code');
            let mobileNumber = `{{ $user->mobile }}`;
            mobileNumber     = mobileNumber.replace(dialCode,'');

            $('[name=mobile]').val(mobileNumber);

            mobileElement.text(`+${dialCode}`);
        })(jQuery);
    </script>
@endpush
