@extends($activeTheme . 'layouts.auth')
@section('auth')
    <div class="py-120">
        <div class="custom--card">
            <div class="card-header">
                <h3 class="title">@lang('Referrals')</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form--label">@lang('Referral Link')</label>
                    <div class="input--group">
                        <input type="text" class="form--control" id="referralLink" value="{{ $link }}" readonly="">
                        <button class="btn btn--base px-3 referral-link__copy"><i class="ti ti-copy"></i></button>
                    </div>
                </div>
            </div>
        </div>

        <div class="custom--card mt-5">
            <div class="card-header">
                <h3 class="title">@lang('Users Referred By Me')</h3>
            </div>
            <div class="card-body">
                @if ($user->refBy)
                    <div class="d-flex flex-wrap justify-content-center">
                        <h5>
                            <span class="mb-2">@lang('You are referred by')</span>
                            <span class="text--base">{{ $user->refBy->username }}</span>
                        </h5>
                    </div>
                @endif

                <div class="treeview-container">
                    <ul class="treeview">
                        @if ($user->allReferrals->count() > 0 && $maxLevel > 0)
                            <li class="items-expanded"> {{ $user->username }}
                                @include($activeTheme . 'user.referral.underTree', ['user' => $user, 'layer' => 0, 'isFirst' => true])
                            </li>
                        @else
                            <li class="items-expanded">@lang('No user found')</li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('page-style-lib')
    <link rel="stylesheet" href="{{ asset($activeThemeTrue . 'css/page/treeView.css') }}">
@endpush

@push('page-script-lib')
    <script src="{{ asset($activeThemeTrue . 'js/page/treeView.js') }}"></script>
@endpush

@push('page-script')
    <script>
        (function($) {
            "use strict";

            $('.treeview').treeView();
        })(jQuery);
    </script>
@endpush