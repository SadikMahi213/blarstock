@extends($activeTheme . 'layouts.frontend')
@section('frontend')
    <section class="search-result">
        <div class="header-search">
            <div class="container-fluid">
                <div class="d-flex gap-2">
                    <button class="btn btn--sm btn-outline--secondary search-result__sidebar-toggler"><i class="ti ti-adjustments-horizontal"></i> <span class="btn-txt">@lang('Filter')</span></button>
                    <form class="banner-content__form input--group w-100 searchForm">
                        <input type="search" class="form--control form--control--sm" name="search_by_title" value="{{ request('search_title') ?? '' }}" placeholder="@lang('Search Here')">
                        <button type="submit" class="btn btn--sm btn--base"><i class="ti ti-search"></i> <span class="btn-txt">@lang('Search')</span></button>
                    </form>
                </div>
            </div>
        </div>
        <div class="search-result__sidebar">
            <button class="search-result__sidebar__close"></button>
            <div class="search-result__sidebar__inner">
                <div class="search-result__sidebar__header">
                    <h3 class="search-result__sidebar__title"><i class="ti ti-adjustments-horizontal"></i> @lang('Filters')</h3>
                    <button class="clear-filter clearFilterBtn"><i class="ti ti-x"></i> @lang('Clear')</button>
                </div>
                @if ($assetTypes->isNotEmpty())
                    <div class="search-result__card">
                        <a role="button" class="search-result__card__header opened"><i class="ti ti-triangle-square-circle"></i> @lang('Asset type')</a>
                        <div class="search-result__card__body">
                            <div class="btn-box d-flex flex-wrap gap-2">
                                <span role="button" class="search-result__filter-btn searchByFileType @if(!request('file_type_id')) active @endif">
                                    <i class="ti ti-triangle-square-circle"></i> @lang('All Assets')
                                </span>

                                @foreach ($assetTypes as $assetType)
                                    <span role="button" class="search-result__filter-btn searchByFileType @if (request('file_type_id') == $assetType->id) active @endif"
                                        data-file_type_id="{{ $assetType->id }}">
                                        @php echo $assetType->icon; @endphp {{ __(ucfirst($assetType->name)) }}
                                    </span>
                                @endforeach    
                            </div>
                        </div>
                    </div>
                @endif
                <div class="search-result__card">
                    <a role="button" class="search-result__card__header opened"><i class="ti ti-menu-order"></i> @lang('Sort by')</a>
                    <div class="search-result__card__body">
                        <div class="btn-box d-flex flex-wrap gap-2">
                            <span role="button" class="search-result__filter-btn searchBySort @if(!request()->sort) active @endif"><i class="ti ti-new-section"></i> @lang('Recent')</span>
                            <span role="button" class="search-result__filter-btn searchBySort @if(request()->sort == 'old') active @endif" data-sort="old"><i class="ti ti-old"></i> @lang('Old')</span>
                            <span role="button" class="search-result__filter-btn searchBySort @if(request()->sort == 'popular') active @endif" data-sort="popular"><i class="ti ti-trending-up"></i> @lang('Popular')</span>
                            <span role="button" class="search-result__filter-btn searchBySort @if(request()->sort == 'featured') active @endif" data-sort="featured"><i class="ti ti-sun-high"></i> @lang('Featured')</span>
                            <span role="button" class="search-result__filter-btn searchBySort @if(request()->sort == 'download') active @endif" data-sort="download"><i class="ti ti-file-download"></i> @lang('Most Download')</span>
                        </div>
                    </div>
                </div>
                @if ($colors->isNotEmpty())
                    <div class="search-result__card">
                        <a role="button" class="search-result__card__header opened"><i class="ti ti-palette"></i> @lang('Colors')</a>
                        <div class="search-result__card__body">
                            <div class="btn-box d-flex flex-wrap gap-2">
                                <span role="button" class="search-result__color-filter-btn deselect-color searchByColor" title="@lang('All')"></span>
                                @foreach ($colors as $color)
                                    <span role="button" class="search-result__color-filter-btn searchByColor" data-filter-color="{{ $color->code }}" title="{{ __($color->name) }}"></span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
                <div class="search-result__card">
                    <a role="button" class="search-result__card__header opened"><i class="ti ti-crown"></i> @lang('License')</a>
                    <div class="search-result__card__body">
                        <div class="btn-box d-flex flex-wrap gap-2">
                            <span role="button" class="search-result__filter-btn searchByLicense @if(!request()->license) active @endif"><i class="ti ti-menu-2"></i> @lang('All')</span>
                            <span role="button" class="search-result__filter-btn searchByLicense @if(request()->license == 'free') active @endif" data-license="free"><i class="ti ti-creative-commons-zero"></i> @lang('Free')</span>
                            <span role="button" class="search-result__filter-btn searchByLicense @if(request()->license == 'premium') active @endif" data-license="premium"><i class="ti ti-crown text--warning"></i> @lang('Premium')</span>
                        </div>
                    </div>
                </div>
                <div class="search-result__card">
                    <a role="button" class="search-result__card__header opened"><i class="ti ti-calendar-month"></i> @lang('Publish date')</a>
                    <div class="search-result__card__body">
                        <div class="btn-box d-flex flex-wrap gap-2">
                            <span role="button" class="search-result__filter-btn searchByPublish @if(!request()->publish) active @endif">@lang('All Time')</span>
                            <span role="button" class="search-result__filter-btn searchByPublish" data-publish="3">@lang('Last 3 months')</span>
                            <span role="button" class="search-result__filter-btn searchByPublish" data-publish="6">@lang('Last 6 months')</span>
                            <span role="button" class="search-result__filter-btn searchByPublish" data-publish="1">@lang('Last year')</span>
                        </div>
                    </div>
                </div>
                @if (!empty($fileTypes))
                    <div class="search-result__card">
                        <a role="button" class="search-result__card__header opened"><i class="ti ti-file"></i> @lang('Extentions')</a>
                        <div class="search-result__card__body">
                            <div class="btn-box d-flex flex-wrap gap-2">
                                <span role="button" class="search-result__filter-btn searchByExtension @if(!request()->extension) active @endif" >@lang('All')</span> 

                                @foreach ($fileTypes as $fileType)
                                    <span role="button" class="search-result__filter-btn searchByExtension" data-file_type="{{ $fileType }}">{{ strtoupper($fileType) }}</span>                                
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
                <div class="search-result__card">
                    <a role="button" class="search-result__card__header opened"><i class="ti ti-rotate-rectangle"></i> @lang('Orientation')</a>
                    <div class="search-result__card__body">
                        <div class="btn-box d-flex flex-wrap gap-2">
                            <span role="button" class="search-result__filter-btn searchByOrientation @if(!request()->shape) active @endif" data-shape="horizontal"><i class="ti ti-menu-2"></i> @lang('All')</span>
                            <span role="button" class="search-result__filter-btn searchByOrientation" data-shape="horizontal"><i class="ti ti-crop-landscape"></i> @lang('Horizontal')</span>
                            <span role="button" class="search-result__filter-btn searchByOrientation" data-shape="vertical"><i class="ti ti-crop-portrait"></i> @lang('Vertical')</span>
                            <span role="button" class="search-result__filter-btn searchByOrientation" data-shape="square"><i class="ti ti-crop-1-1"></i> @lang('Square')</span>
                            <span role="button" class="search-result__filter-btn searchByOrientation" data-shape="panoramic"><i class="ti ti-crop-16-9"></i> @lang('Panoramic')</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="search-result__items">
            <div class="search-result__guide">
                <div class="search-result__guide__nav">
                    <button class="search-result__guide__nav__btn" id="searchGuideLeftArrow" disabled><i class="ti ti-chevron-left"></i></button>
                    <button class="search-result__guide__nav__btn" id="searchGuideRightArrow"><i class="ti ti-chevron-right"></i></button>
                </div>
                <div class="search-result__guide__list">
                    <ul>
                        <li><a role="button" class="searchByCategory @if(!request('category_id')) search-result__guide__type @endif">
                            <i class="ti ti-search"></i> @lang('All Categories')</a>
                        </li>

                        @foreach ($categories as $category)
                            <li>
                                <a role="button" class="searchByCategory @if(request('category_id') && request('category_id') == $category->id) search-result__guide__type @else search-result__guide__search @endif" data-category_id="{{ $category->id }}"><i class="ti ti-search"></i> {{ __(ucfirst($category->name)) }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="product">
                <div class="product__nav">
                    <span role="button" class="product__nav__link searchByDataType @if( (!request('data_type')) || (request('data_type') == 'assets')) active @endif" data-data_type="assets"><i class="ti ti-library-photo"></i> @lang('Assets') <span class="assetsCount">@if ($isAssetOrCollectionAssets == 'allAssets')({{ formatNumber($assetsCount) }}) @endif</span></span>

                    <span role="button" class="product__nav__link searchByDataType @if(request('data_type') == 'collections') active @endif" data-data_type="collections"><i class="ti ti-copy-plus"></i> @lang('Collections') <span class="collectedAssetsCount">@if ($isAssetOrCollectionAssets == 'collectedAssets')({{ formatNumber($assetsCount) }}) @endif</span></span>
                </div>
                <div id="allAssetsDiv">
                    @include($activeTheme . 'ajax.assets', ['assets' => $assets, 'user' => $user])
                </div>
            </div>

            @include($activeTheme . 'partials.ads')
        </div>
    </section>
@endsection

@push('page-script')
    <script>
        (function($) {
            'use strict';

            $('.clearFilterBtn').on('click', function() {
                let url = '{{ route('all.assets') }}';

                window.location.href = `${url}`;
            });
        })(jQuery);
    </script>
@endpush