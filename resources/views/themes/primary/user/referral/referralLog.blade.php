@extends($activeTheme . 'layouts.auth')
@section('auth')
    <div class="py-120">
        <div class="custom--card border-0">
            <div class="card-header">
                <h3 class="title">{{ __($pageTitle) }}</h3>
            </div>
            <table class="table table-borderless table--striped top-rounded-0 table--responsive--sm">
                <thead>
                    <tr>
                        <th>@lang('User')</th>
                        <th>@lang('Amount')</th>
                        <th>@lang('Time')</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($referrals as $referral)
                        <tr>
                            <td>{{ __($referral->fromUser->fullName) }}</td>
                            <td>{{ showAmount($referral->commission_amount) }} {{ __($setting->site_cur) }}</td>
                            <td>{{ showDateTime($referral->created_at, 'd M, Y - h:i A') }}</td>
                        </tr>    
                    @empty
                        <tr>
                            <td class="no-data-table" colspan="100%" rowspan="100%">
                                <div class="no-data-found">
                                    <img src="{{ getImage('assets/universal/images/noData.png') }}" alt="{{ __($emptyMessage) }}">
                                    <span>@lang('No referral commission found')</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection