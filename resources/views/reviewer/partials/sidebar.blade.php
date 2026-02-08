

<div class="main-sidebar">
    <form class="header__search d-md-none">
         <span class="header__search__icon">
             <i class="ti ti-search"></i>
         </span>

         <input type="search" class="header__search__input" placeholder="@lang('Search')..." id="searchInput" autocomplete="off">
          <ul class="search-list d-none"></ul>
    </form>
    <ul class="sidebar-menu scroll">
         <li class="sidebar-item">
               <a href="{{ route('reviewer.dashboard') }}" class="sidebar-link {{ navigationActive('reviewer.dashboard', 2) }}">
                    <span class="nav-icon"><i class="ti ti-layout-dashboard"></i></span>
                    <span class="sidebar-txt">@lang('Dashboard')</span>
               </a>
         </li>
          <li class="sidebar-item">
               <a href="{{ route('reviewer.asset.pending') }}" class="sidebar-link {{ navigationActive('admin.subscriber*', 2) }}">
                    <span class="nav-icon"><i class="ti ti-loader"></i></span>
                    <span class="sidebar-txt">@lang('Pending Assets')</span>
                    @if($pendingAssetCount)
                         <span class="badge badge--danger rounded-1">{{ $pendingAssetCount }}</span>
                    @endif
               </a>
          </li>
          <li class="sidebar-item">
               <a href="{{ route('reviewer.asset.approved') }}" class="sidebar-link {{ navigationActive('admin.basic*', 2) }}">
                    <span class="nav-icon"><i class="ti ti-circle-dashed-check"></i></span>
                    <span class="sidebar-txt">@lang('Approved Assets')</span>
               </a>
          </li>
          <li class="sidebar-item">
                <a href="{{ route('reviewer.asset.rejected') }}" class="sidebar-link {{ navigationActive('admin.author.guideline*', 2) }}">
                     <span class="nav-icon"><i class="ti ti-circle-dashed-x"></i></span>
                     <span class="sidebar-txt">@lang('Rejected Assets')</span>
                </a>
           </li>
    </ul>
</div>