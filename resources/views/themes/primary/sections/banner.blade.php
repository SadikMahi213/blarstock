<!--========================== Banner Section Start ==========================-->
@php
    $bannerContent = getSiteData('banner.content', true);
    $fileTypes     = App\Models\FileType::active()->get();
    $imageTags     = App\Models\Image::approved()->orderByDesc('total_view')->take(10)->pluck('tags')->toArray();
    $bannerTags    = !empty($imageTags) ? array_slice(array_unique(array_merge(...$imageTags)), 0,5) : [];
@endphp

<section class="banner-section">
    <div class="banner-section__bg bg-img" data-background-image="{{ getImage($activeThemeTrue . 'images/site/banner/' . $bannerContent?->data_info?->background_image, '1920x1280') }}"></div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xxl-6 col-md-7">
                <div class="banner-content">
                    <h1 class="banner-content__title slide-to-left">{{ __($bannerContent?->data_info?->title) }}</h1>
                    <form action="{{ route('all.assets') }}" class="banner-content__form searchViaForm" method="GET">
                        <div class="banner-content__select">
                            <button type="button" class="banner-content__select__btn"><span class="banner-content__select__btn__txt"><i class="ti ti-triangle-square-circle"></i> <span class="btn-txt">@lang('Assets')</span></span> <i class="ti ti-chevron-down"></i></button>
                            <ul class="banner-content__select__list">
                                <li>
                                    <input data-filter="library" type="radio" id="searchInAsset" name="data_type" value="assets" checked>
                                    <label for="searchInAsset"><i class="ti ti-triangle-square-circle"></i> @lang('Assets')</label>
                                </li>
                                <li>
                                    <input data-filter="library" type="radio" id="searchInCollection" name="data_type" value="collections">
                                    <label for="searchInCollection"><i class="ti ti-copy-plus"></i> @lang('Collections')</label>
                                </li>
                                <li class="dropdown-divider"></li>

                                @foreach ($fileTypes as $fileType)
                                    <li>
                                        <input data-filter="category" type="checkbox" id="{{ $fileType->id }}" name="file_type_id" value="{{ $fileType->id }}">
                                        <label for="{{ $fileType->id }}">@php echo $fileType->icon; @endphp {{ __($fileType->name) }}</label>
                                    </li>    
                                @endforeach

                                <li class="dropdown-divider"></li>
                                <li>
                                    <input data-filter="access-level" type="checkbox" id="searchAll" name="license" value="">
                                    <label for="searchAll"><i class="ti ti-menu-2"></i> @lang('All')</label>
                                </li>
                                <li>
                                    <input data-filter="access-level" type="checkbox" id="searchPremium" name="license" value="premium">
                                    <label for="searchPremium"><i class="ti ti-crown text--warning"></i> @lang('Premium')</label>
                                </li>
                                <li>
                                    <input data-filter="access-level" type="checkbox" id="searchFree" name="license" value="free">
                                    <label for="searchFree"><i class="ti ti-creative-commons-zero"></i> @lang('Free')</label>
                                </li>
                            </ul>
                        </div>
                        <input type="search" class="search-input" placeholder="{{ __($bannerContent?->data_info?->placeholder_text) }}" name="search_title" value="{{ old('search_title') }}">
                        <button class="search-btn"><i class="ti ti-search"></i> <span class="btn-txt">{{ __($bannerContent?->data_info?->search_button_name) }}</span></button>
                    </form>
                    <div class="banner-content__search-tag">
                        @foreach ($bannerTags as $bannerTag)
                            <a href="{{ route('all.assets', ['tag' => $bannerTag]) }}"><i class="ti ti-search"></i> {{ (ucfirst($bannerTag)) }}</a>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-lg-7 col-md-8">
                <div class="banner-thumb">
                    <div class="banner-thumb__box image-1">
                        <img src="{{ getImage($activeThemeTrue . 'images/site/banner/' . $bannerContent?->data_info?->thumb_top_left, '200x270') }}" alt="Image">
                    </div>
                    <div class="banner-thumb__box image-2">
                        <img src="{{ getImage($activeThemeTrue . 'images/site/banner/' . $bannerContent?->data_info?->thumb_top_right, '250x180') }}" alt="Image">
                    </div>
                    <div class="banner-thumb__box image-3">
                        <img src="{{ getImage($activeThemeTrue . 'images/site/banner/' . $bannerContent?->data_info?->thumb_bottom_left, '180x180') }}" alt="Image">
                    </div>
                    <div class="banner-thumb__box image-4">
                        <img src="{{ getImage($activeThemeTrue . 'images/site/banner/' . $bannerContent?->data_info?->thumb_bottom_right, '180x230') }}" alt="Image">
                    </div>
                    <div class="banner-thumb__box big-image image-5">
                        <img src="{{ getImage($activeThemeTrue . 'images/site/banner/' . $bannerContent?->data_info?->thumb_center, '1000x565') }}" alt="Image">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--========================== Banner Section End ==========================-->