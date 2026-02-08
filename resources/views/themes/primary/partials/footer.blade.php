<!-- ==================== Footer Start Here ==================== -->
@php
    $content     = getSiteData('footer.content', true);
    $elements    = getSiteData('footer.element');
    $contactInfo = getSiteData('contact_us.content', true);
    $policyPages = getSiteData('policy_pages.element', false, null, true);
@endphp
<footer class="footer-area">
    <div class="footer-area__bg bg-img" data-background-image="{{ getImage($activeThemeTrue . 'images/site/footer/' . $content?->data_info?->bg_image, '1920x1280') }}"></div>
    <div class="container">
        <div class="footer-area__wrap">
            <div class="py-60">
                <div class="row justify-content-center gy-5">
                    <div class="col-xl-4 col-sm-6 col-xsm-6">
                        <div class="footer-item">
                            <div class="footer-item__logo">
                                <a href="{{ route('home') }}"> <img src="{{ getImage(getFilePath('logoFavicon').'/logo_light.png') }}" alt="logo"></a>
                            </div>
                            <p class="footer-item__desc">{{ __($content?->data_info?->description) }}</p>
                            <ul class="social-list">
                                @foreach ($elements as $element)
                                    <li class="social-list__item"><a href="{{ $element?->data_info?->social_site_link }}" class="social-list__link flex-center">@php echo $element?->data_info?->social_site_icon; @endphp</a> </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    
                    <div class="col-xl-2 col-sm-6 col-xsm-6">
                        <div class="footer-item">
                            <h5 class="footer-item__title">@lang('Useful Link')</h5>
                            <ul class="footer-menu">
                                <li class="footer-menu__item"><a href="{{ route('home') }}" class="footer-menu__link">@lang('Home')</a></li>
                                <li class="footer-menu__item"><a href="{{ route('author.index') }}" class="footer-menu__link">@lang('Authors') </a></li>
                                <li class="footer-menu__item"><a href="{{ route('contact') }}" class="footer-menu__link">@lang('Contact Us') </a></li>
                                <li class="footer-menu__item"><a href="{{ route('user.author.form') }}" class="footer-menu__link">@lang('Apply for author')</a></li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="col-xl-3 col-sm-6 col-xsm-6">
                        <div class="footer-item">
                            <h5 class="footer-item__title">@lang('Policies')</h5>
                            <ul class="footer-menu">
                                <li class="footer-menu__item"><a href="{{ route('cookie.policy') }}" class="footer-menu__link">@lang('Cookie Policy')</a></li>

                                @foreach ($policyPages as $policy)
                                    <li class="footer-menu__item"><a href="{{ route('policy.pages', [slug($policy->data_info->title), $policy->id]) }}" class="footer-menu__link">{{ __($policy->data_info->title) }} </a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <div class="col-xl-3 col-sm-6 col-xsm-6">
                        <div class="footer-item">
                            <h5 class="footer-item__title">@lang('Contact With Us')</h5>
                            <ul class="footer-contact-menu">
                                <li class="footer-contact-menu__item"> 
                                    <div class="footer-contact-menu__item-icon">
                                        <i class="ti ti-map-pin"></i>
                                    </div>
                                    <div class="footer-contact-menu__item-content" title="{{ __($contactInfo?->data_info?->address) }}">
                                        <p>{{ __(strLimit($contactInfo?->data_info?->address, 70)) }}</p>
                                    </div>
                                </li>
                                <li class="footer-contact-menu__item"> 
                                    <div class="footer-contact-menu__item-icon">
                                        <i class="ti ti-mail"></i>
                                    </div>
                                    <div class="footer-contact-menu__item-content" title="{{ $contactInfo?->data_info?->email }}">
                                        <p>{{ strLimit($contactInfo?->data_info?->email, 30) }}</p>
                                    </div>
                                </li>
                                <li class="footer-contact-menu__item"> 
                                    <div class="footer-contact-menu__item-icon">
                                        <i class="ti ti-phone"></i>
                                    </div>
                                    <div class="footer-contact-menu__item-content">
                                        <p>{{ $contactInfo?->data_info?->contact_number }} </p>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Footer Top End-->
        
            <!-- bottom Footer -->
            <div class="bottom-footer py-3">
                <div class="text-center">
                    <p class="bottom-footer__text">{{ __($content?->data_info?->copyright_text) }}</p>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- ==================== Footer End Here ==================== -->