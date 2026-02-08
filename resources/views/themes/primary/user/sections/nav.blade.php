<nav class="navbar dashboard-navbar">
     <ul class="navbar-nav ms-lg-0 m-auto">
          <li class="nav-item">
               <a class="nav-link" href="{{ route('user.author.dashboard') }}"><span class="nav-link__icon"><i class="ti ti-layout-dashboard"></i></span> <span class="nav-link__txt">@lang('Dashboard')</span></a>
          </li>
          <li class="nav-item">
               <a class="nav-link" href="{{ route('user.collection.index') }}"><span class="nav-link__icon"><i class="ti ti-copy-plus"></i></span> <span class="nav-link__txt">@lang('Collections')</span></a>
          </li>
          <li class="nav-item">
               <a class="nav-link" href="{{ route('user.download.index') }}"><span class="nav-link__icon"><i class="ti ti-download"></i></span> <span class="nav-link__txt">@lang('Downloads')</span></a>
          </li>

          @if (auth()->user()->author_status == ManageStatus::AUTHOR_APPROVED)
               <li class="nav-item">
                    <a class="nav-link" href="{{ route('user.author.dashboard') }}"><span class="nav-link__icon"><i class="ti ti-chart-bar"></i></span> <span class="nav-link__txt">@lang('Earnings Dashboard')</span></a>
               </li>
               <li class="nav-item custom--dropdown">
                    <span class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                         <span class="nav-link__icon"><i class="ti ti-triangle-square-circle"></i></span> <span class="nav-link__txt">@lang('Manage Assets')</span>
                    </span>
                    <ul class="dropdown-menu">
                         <li><a class="dropdown-item" href="{{ route('user.asset.pending') }}">@lang('Pending Assets')</a></li>
                         <li><a class="dropdown-item" href="{{ route('user.asset.rejected') }}">@lang('Rejected Assets')</a></li>
                         <li><a class="dropdown-item" href="{{ route('user.asset.approved') }}">@lang('Approved Assets')</a></li>
                         <li><a class="dropdown-item" href="{{ route('user.asset.index') }}">@lang('All Assets')</a></li>
                    </ul>
               </li>
          @elseif (auth()->user()->author_status == ManageStatus::AUTHOR_PENDING)
               <li class="nav-item">
                    <a class="nav-link" href="{{ route('user.author.info') }}"><span class="nav-link__icon"><i class="ti ti-user-circle"></i></span> <span class="nav-link__txt">@lang('Author Info')</span></a>
               </li>
          @elseif (auth()->user()->author_status == ManageStatus::IS_NOT_AUTHOR || auth()->user()->author_status == ManageStatus::AUTHOR_REJECTED)
               <li class="nav-item">
                    <a class="nav-link" href="{{ route('user.author.form') }}"><span class="nav-link__icon"><i class="ti ti-user-circle"></i></span> <span class="nav-link__txt">@lang('Apply for Author')</span></a>
               </li>
          @endif
          
          <li class="nav-item custom--dropdown">
               <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="nav-link__icon"><i class="ti ti-report-money"></i></span> <span class="nav-link__txt">@lang('Finances')</span>
               </a>
               <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('user.deposit.index') }}">@lang('Deposit')</a></li>
                    <li><a class="dropdown-item" href="{{ route('user.deposit.history') }}">@lang('Deposit History')</a></li>
                    <li><a class="dropdown-item" href="{{ route('user.withdraw.methods') }}">@lang('Withdraw')</a></li>
                    <li><a class="dropdown-item" href="{{ route('user.withdraw.index') }}">@lang('Withdraw History')</a></li>
                    <li><a class="dropdown-item" href="{{ route('user.transactions') }}">@lang('Transactions')</a></li>
                    @if (auth()->user()->author_status == ManageStatus::AUTHOR_APPROVED)
                              <li><a class="dropdown-item" href="{{ route('user.author.donations') }}">@lang('Donation Logs')</a></li>
                              <li><a class="dropdown-item" href="{{ route('user.author.earnings') }}">@lang('Earning Logs')</a></li>
                    @endif
                    <li><a class="dropdown-item" href="{{ route('user.payment.history') }}">@lang('Payment History')</a></li>
               </ul>
          </li>
          <li class="nav-item custom--dropdown">
               <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="nav-link__icon"><i class="ti ti-users-group"></i></span> <span class="nav-link__txt">@lang('Referrals')</span>
               </a>
               <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('user.referral.link') }}">@lang('Referrals')</a></li>
                    <li><a class="dropdown-item" href="{{ route('user.referral.log') }}">@lang('Referral Log')</a></li>
               </ul>
          </li>
          <li class="nav-item custom--dropdown">
               <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="nav-link__icon"><i class="ti ti-user-cog"></i></span> <span class="nav-link__txt">@lang('Account')</span>
               </a>
               <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('user.profile') }}">@lang('Profile Settings')</a></li>
                    <li><a class="dropdown-item" href="{{ route('user.social.profile') }}">@lang('Social Profiles')</a></li>
                    <li><a class="dropdown-item" href="{{ route('user.change.password') }}">@lang('Change Password')</a></li>
                    <li><a class="dropdown-item" href="{{ route('user.twofactor.form') }}">@lang('2FA Security')</a></li>
               </ul>
          </li>
     </ul>
</nav>