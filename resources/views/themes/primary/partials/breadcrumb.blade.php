<!-- ==================== Breadcrumb Start Here ==================== -->
@php
    $content = getSiteData('breadcrumb.content', true);
@endphp
<section class="breadcrumb">
    <div class="breadcrumb__bg bg-img" data-background-image="{{ getImage($activeThemeTrue . 'images/site/breadcrumb/' . $content->data_info?->bg_image, '1920x1280') }}"></div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="breadcrumb__wrapper">
                    <h1 class="breadcrumb__title">{{ __($pageTitle) }}</h1>
                    <p class="breadcrumb__desc">{{ __($subTitle ?? '') }}</p>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- ==================== Breadcrumb End Here ==================== -->