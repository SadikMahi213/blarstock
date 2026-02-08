<!-- ==================== Header Start Here ==================== -->
@if($setting->language)
    @php $languages   = App\Models\Language::active()->get(); @endphp
@endif

@php
    $user           = auth()->user();
    $fileTypesQuery = App\Models\FileType::active()->withWhereHas('categories')->approvedImageCount();
    $fileTypesCount = (clone $fileTypesQuery)->count();
    $fileTypes      = (clone $fileTypesQuery)->get();
@endphp

<header class="header @if (request()->routeIs('home')) home-header @endif @if (request()->routeIs(['all.assets', 'asset.detail'])) header-2 @endif" id="header">
    <nav class="navbar navbar-expand-xxl navbar-light">
        <button class="navbar-toggler header-button" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span id="hiddenNav"><i class="ti ti-menu-2"></i></span>
        </button>
        <a class="navbar-brand logo" href="{{ route('home') }}"><img src="{{ getImage(getFilePath('logoFavicon').'/logo_light.png') }}" alt="logo"></a>
        <div class="d-xxl-none">
            <div class="user-dropdown custom--dropdown">
                @auth
                    <span class="user-dropdown__btn" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="{{ getImage(getFilePath('userProfile') . '/' . $user->image, getFileSize('userProfile'), true) }}" alt="User">
                    </span>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li class="dropdown-menu__list"><a class="dropdown-menu__link" href="{{ route('user.author.dashboard') }}"><i class="ti ti-home"></i> @lang('Dashboard')</a></li>
                        <li class="dropdown-menu__list"><a class="dropdown-menu__link" href="{{ route('user.collection.index') }}"><i class="ti ti-copy-plus"></i> @lang('My Collections')</a></li>
                        <li class="dropdown-menu__list"><a class="dropdown-menu__link" href="{{ route('user.profile') }}"><i class="ti ti-user"></i> @lang('My Profile')</a></li>
                        <li class="dropdown-menu__list"><a class="dropdown-menu__link" href="{{ route('user.logout') }}"><i class="ti ti-logout"></i> @lang('Logout')</a></li>
                    </ul>
                @else
                    <a href="{{ route('user.author.dashboard') }}" class="user-dropdown__btn">
                        <i class="ti ti-user-plus"></i>
                    </a>
                @endauth
            </div>
        </div>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav nav-menu ms-auto align-items-xxl-center">
                <li class="nav-item d-block d-xxl-none">
                    <div class="top-button d-flex flex-wrap justify-content-between align-items-center">
                        @if ($setting->language)
                            <div class="language-box">
                                <select class="select form--control form-select langSel">
                                    @foreach ($languages as $lang)
                                        <option value="{{ $lang->code }}" @if (session('lang') == $lang->code) selected @endif>{{ trim(__($lang->name)) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                        @auth
                            @if ($user->author_status == ManageStatus::AUTHOR_APPROVED)
                                <a class="btn btn--sm @if (request()->routeIs('home')) btn--base @else btn-outline--light @endif" href="{{ route('user.asset.add') }}"><i class="ti ti-upload"></i> @lang('Submit Your Art')</a>
                            @endif
                        @endauth
                    </div>
                </li>
                <li class="nav-item d-xxl-block d-none">
                    @auth
                        @if ($user->author_status == ManageStatus::AUTHOR_APPROVED)
                            <a class="btn btn--sm btn--base" href="{{ route('user.asset.add') }}"><i class="ti ti-upload"></i> @lang('Submit Your Art')</a>
                        @endif
                    @endauth
                </li>
                <li class="nav-item custom--dropdown">
                    <a class="nav-link" role="button" data-bs-toggle="dropdown" aria-expanded="false">@lang('Explore') <i class="ti ti-chevron-down nav-item__icon"></i></a>
                    <ul class="dropdown-menu">
                        <li class="dropdown-menu__list">
                            <a class="dropdown-menu__link" href="{{ route('author.index') }}"><i class="ti ti-users-group"></i> @lang('Authors')</a>
                        </li>
                        <li class="dropdown-menu__list">
                            <a class="dropdown-menu__link" href="{{ route('all.assets') }}"><i class="ti ti-triangle-square-circle"></i> @lang('Assets')</a>
                        </li>
                        <li class="dropdown-menu__list">
                            <a class="dropdown-menu__link" href="{{ route('collection.index') }}"><i class="ti ti-copy-plus"></i> @lang('Collections')</a>
                        </li>
                    </ul>
                </li>
                
                @if ($fileTypes->count() <= 4)
                    @foreach ($fileTypes as $fileType)
                        <li class="nav-item custom--dropdown">
                            <a class="nav-link"  data-bs-toggle="dropdown" aria-expanded="false">{{ __($fileType->name) }} <i class="ti ti-chevron-down nav-item__icon"></i></a>
                            <ul class="dropdown-menu mega-menu">
                                <li>
                                    <div class="mega-menu__container">
                                        <ul class="mega-menu__list">
                                            @foreach ($fileType->categories->take(10) as $category)
                                                <li><a href="{{ route('all.assets', ['category_id' => $category->id]) }}" class="mega-menu__link">{{ __($category->name) }}</a></li>
                                            @endforeach
                                        </ul>
                                        <div class="mega-menu__thumb">
                                            <a href="{{ route('all.assets', ['file_type_id' => $fileType->id]) }}">
                                                <img src="{{ getImage(getFilePath('fileTypes') . '/' . $fileType->image) }}" alt="@lang('image')">
                                            </a>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    @endforeach    
                @else
                    @foreach ($fileTypes->take(3) as $fileType)
                        <li class="nav-item custom--dropdown">
                            <a class="nav-link" role="button" data-bs-toggle="dropdown" aria-expanded="false">{{ __($fileType->name) }} <i class="ti ti-chevron-down nav-item__icon"></i></a>
                            <ul class="dropdown-menu mega-menu">
                                <li>
                                    <div class="mega-menu__container">
                                        <ul class="mega-menu__list">
                                            @foreach ($fileType->categories->take(10) as $category)
                                                <li><a href="{{ route('all.assets', ['category_id' => $category->id]) }}" class="mega-menu__link">{{ __($category->name) }}</a></li>
                                            @endforeach
                                        </ul>
                                        <div class="mega-menu__thumb">
                                            <a href="{{ route('all.assets', ['file_type_id' => $fileType->id]) }}">
                                                <img src="{{ getImage(getFilePath('fileTypes') . '/' . $fileType->image) }}" alt="@lang('image')">
                                            </a>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    @endforeach
                    
                    <li class="nav-item custom--dropdown">
                        <a class="nav-link" role="button" data-bs-toggle="dropdown" aria-expanded="false">@lang('More') <i class="ti ti-chevron-down nav-item__icon"></i></a>
                        <ul class="dropdown-menu">
                            @foreach ($fileTypes->slice(3) as $fileType)
                                <li class="dropdown-menu__list custom--dropdown">
                                    <a class="dropdown-menu__link" role="button">{{ __($fileType->name) }}</a>
                                    <ul class="dropdown-menu mega-menu">
                                        <li>
                                            <div class="mega-menu__container">
                                                <ul class="mega-menu__list">
                                                    @foreach ($fileType->categories->take(10) as $category)
                                                        <li><a href="{{ route('all.assets', ['category_id' => $category->id]) }}" class="mega-menu__link">{{ __($category->name) }}</a></li>
                                                    @endforeach
                                                </ul>
                                                <div class="mega-menu__thumb">
                                                    <a href="{{ route('all.assets', ['file_type_id' => $fileType->id]) }}">
                                                        <img src="{{ getImage(getFilePath('fileTypes') . '/' . $fileType->image) }}" alt="@lang('image')">
                                                    </a>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </li>    
                            @endforeach
                        </ul>
                    </li>
                @endif
                
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('plan') }}">@lang('Pricing')</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('contact') }}">@lang('Contact')</a>
                </li>
                <li class="nav-item d-xxl-block d-none">
                    <div class="d-flex align-items-center gap-2">
                        @if ($setting->language)
                            <div class="language-box">
                                <select class="select form--control form-select langSel">
                                    @foreach ($languages as $lang)
                                        <option value="{{$lang->code}}" @if (session('lang') == $lang->code)selected @endif>{{ trim(__($lang->name)) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                        <div class="user-dropdown custom--dropdown">
                            @auth
                                <span class="user-dropdown__btn" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <img src="{{ getImage(getFilePath('userProfile') .'/' . $user->image, getFileSize('userProfile'), true) }}" alt="User">
                                </span>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li class="dropdown-menu__list"><a class="dropdown-menu__link" href="{{ route('user.author.dashboard') }}"><i class="ti ti-home"></i> @lang('Dashboard')</a></li>
                                    <li class="dropdown-menu__list"><a class="dropdown-menu__link" href="{{ route('user.collection.index') }}"><i class="ti ti-copy-plus"></i> @lang('My Collections')</a></li>
                                    <li class="dropdown-menu__list"><a class="dropdown-menu__link" href="{{ route('user.profile') }}"><i class="ti ti-user"></i> @lang('My Profile')</a></li>
                                    <li class="dropdown-menu__list"><a class="dropdown-menu__link" href="{{ route('user.logout') }}"><i class="ti ti-logout"></i> @lang('Logout')</a></li>
                                </ul>
                            @else
                                <a href="{{ route('user.author.dashboard') }}" class="user-dropdown__btn">
                                    <i class="ti ti-user-plus"></i>
                                </a>
                            @endauth
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
</header>
<!-- ==================== Header End Here ==================== -->