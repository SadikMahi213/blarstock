@extends($activeTheme . 'layouts.app')

@section('content')
    @include($activeTheme . 'partials.header')
    @yield('frontend')
    @include($activeTheme . 'partials.footer')
    @include($activeTheme . 'partials.signInModal')
    @include($activeTheme . 'partials.collectionModal')
    @include($activeTheme . 'partials.shareAssetModal')
    
    <div class="modal custom--modal fade" id="downloadConfirmationModal" tabindex="-1" aria-labelledby="downloadConfirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title fs-5" id="downloadConfirmationModalLabel"></h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3 align-items-center">
                        <div class="col-sm-12">
                            <label class="col-form--label">@lang('This file is ready for download'). @lang('Do you want to proceed') ?</label>
                        </div>
                        
                        <div class="col-12">
                            <a href="" class="btn btn--sm btn--base w-100">@lang('Download')</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('page-style-lib')
        <link rel="stylesheet" href="{{ asset($activeThemeTrue . 'css/slick.css') }}">
        <link rel="stylesheet" href="{{ asset($activeThemeTrue . 'css/select2.min.css') }}">
    @endpush

    @push('page-script-lib')
        <script src="{{ asset($activeThemeTrue . 'js/gsap/gsap.min.js') }}"></script>
        <script src="{{ asset($activeThemeTrue . 'js/gsap/SplitText.min.js') }}"></script>
        <script src="{{ asset($activeThemeTrue . 'js/gsap/scrolltrigger.min.js') }}"></script>
        <script src="{{ asset($activeThemeTrue . 'js/gsap/scrolltoplugin.js') }}"></script>
        <script src="{{ asset($activeThemeTrue . 'js/slick.min.js') }}"></script>
        <script src="{{ asset($activeThemeTrue . 'js/select2.min.js') }}"></script>
        <script src="{{ asset($activeThemeTrue . 'js/imagesloaded.pkgd.min.js') }}"></script>
        <script src="{{ asset($activeThemeTrue . 'js/wookmark.min.js') }}"></script>
        <script src="{{ asset($activeThemeTrue . 'js/apexcharts.js') }}"></script>
    @endpush
@endsection

@push('page-script')
    <script>
        (function($) {

            'use strict';

            signInfoModalShow();
            likeOperation();
            collectionModalView();
            videoPlay();


            $('.langSel').on('change', function() {
                let homeUrl = '{{ route('home') }}';
                let langCode = $(this).val();

                window.location.href = `${homeUrl}/change-language/${langCode}`
            });

            $('.advertisement').on('click', function(e) {
                e.preventDefault();

                let id = $(this).data('id');

                $.ajax({
                    type    : "GET",
                    url     : "{{ route('add.click') }}",
                    data    : { id : id },
                    dataType: "json",
                    success: function (response) {
                        if (response.success) {
                            if (response.data && response.data.redirect_url != '#') {
                                window.open(response.data.redirect_url, '_blank');
                            }
                        } else {
                            showToasts('error', response.message);
                        }
                    }, 
                    error: function() {
                        showToasts('error', 'Something went wrong while clicking advertisement');
                    }
                });
            });

            // _______________________________________Asset Search Start____________________________________________
            let color       = null;
            let extension   = null;
            let shape       = null;
            let publish     = null;

            let activeFileType = $('.searchByFileType.active');
            let fileType       = activeFileType.length ? activeFileType.data('file_type_id') : null;

            let activeCategory = $('.searchByCategory.search-result__guide__type');
            let category       = activeCategory.length ? activeCategory.data('category_id') : null;

            let activeSort = $('.searchBySort.active');
            let sort       = activeSort.length ? activeSort.data('sort') : null;

            let activeLicense = $('.searchByLicense.active');
            let license       = activeLicense.length ? activeLicense.data('license') : null;

            let activeDataType = $('.searchByDataType.active');
            let dataType       = activeDataType.length ? activeDataType.data('data_type') : null;

            let tag = `{{ request('tag') ? request('tag') : null }}`;
            
            let searchTitle = `{{ request('search_title') ? request('search_title') : null }}`;

            $('.searchForm').on('submit', function(e) {
                e.preventDefault();

                searchTitle = $(this).find('[name=search_by_title]').val();

                if (searchTitle == null) {
                    return;
                }

                allAssetsSearchAjax();
            });

            $('.searchByFileType').on('click', function() {
                fileType = $(this).data('file_type_id');

                $(this).addClass('active').siblings().removeClass('active');
                allAssetsSearchAjax();
            });

            $('.searchByCategory').on('click', function() {
                let $clickedLink = $(this);
                let $allLinks    = $clickedLink.closest('li').siblings().find('.searchByCategory');

                $allLinks.removeClass('search-result__guide__type').addClass('search-result__guide__search');
                $clickedLink.removeClass('search-result__guide__search').addClass('search-result__guide__type');

                category = $clickedLink.data('category_id');

                allAssetsSearchAjax();
            });

            $('.searchByColor').on('click', function() {
                color = $(this).data('filter-color');
                
                allAssetsSearchAjax();
            });

            $('.searchByExtension').on('click', function() {
                extension = $(this).data('file_type');

                $(this).addClass('active').siblings().removeClass('active');

                allAssetsSearchAjax();
            });

            $('.searchByOrientation').on('click', function() {
                shape = $(this).data('shape');

                $(this).addClass('active').siblings().removeClass('active');

                allAssetsSearchAjax();
            });

            $('.searchByLicense').on('click', function() {
                license = $(this).data('license');

                $(this).addClass('active').siblings().removeClass('active');

                allAssetsSearchAjax();
            });

            $('.searchBySort').on('click', function() {
                sort = $(this).data('sort');

                $(this).addClass('active').siblings().removeClass('active');

                allAssetsSearchAjax();
            });

            $('.searchByPublish').on('click', function() {
                publish = $(this).data('publish');

                $(this).addClass('active').siblings().removeClass('active');

                allAssetsSearchAjax();
            });

            $('.searchByDataType').on('click', function() {
                dataType = $(this).data('data_type');

                $(this).addClass('active').siblings().removeClass('active');

                allAssetsSearchAjax();
            });

            $(document).on('submit', '.allAssetsPaginationForm', function(e) {
                e.preventDefault();

                let pageNumber = $(this).find('#pageNumberInput').val();

                allAssetsSearchAjax(pageNumber);
            });

            $(document).on('click', '.allAssetsPagination a', function(e) {
                e.preventDefault();

                let page = $(this).attr('href').split('page=')[1];
                
                allAssetsSearchAjax(page);
            });

            function allAssetsSearchAjax(page) {

                let filterType  = `{{ request()->route('filterType') ?? null }}`;
                let filterValue = `{{ request()->route('filterValue') ?? null }}`;

                $.ajax({
                    type    : "GET",
                    url: "{{ route('all.assets') }}?page=" + page,
                    data    : {
                        filter_type : filterType,
                        filter_value: filterValue,
                        color_code  : color,
                        extension   : extension,
                        category_id : category,
                        file_type_id: fileType,
                        search_title : searchTitle,
                        shape       : shape,
                        license     : license,
                        sort        : sort,
                        publish     : publish,
                        data_type   : dataType,
                        tag         : tag
                    },
                    dataType: "json",
                    success: function (response) {
                        if (response.success) {
                            $(window).scrollTop(0);

                            if (response.isAssetsOrCollectionAssets == 'allAssets') {
                                $('.collectedAssetsCount').html('');
                                $('.assetsCount').html(`(${response.assetsCount})`);
                            } else {
                                $('.assetsCount').html('');
                                $('.collectedAssetsCount').html(`(${response.assetsCount})`);
                            }

                            $('#allAssetsDiv').html(response.html);

                            productRowDesign();
                            videoPlay();
                            likeOperation();
                            signInfoModalShow();
                            collectionModalView();

                        } else {
                            showToasts('error', response.message);
                        }
                    },
                    error: function() {
                        showToasts('error', 'Something went wrong');
                    }
                });
            }

            // ________________________________Asset search end_____________________________________________________

            function productRowDesign() {
                let container = $('.product__row:not(.product__row-empty)');
                if (container.length) {
                    function setWookmarkOffset() {
                        let offset;
                        if (window.matchMedia("(max-width: 768px)").matches) {
                            offset = 10;
                        } else {
                            offset = 15;
                        }
                        
                        container.wookmark({
                            align: 'left',
                            offset: offset,
                            outerOffset: 0,
                        });
                    }
                    container.imagesLoaded(function() {
                        setWookmarkOffset();
                    });
                    $(window).resize(function() {
                        setWookmarkOffset();
                    });
                }
            }

            function videoPlay() {
                $('.product__card.product-video').on('mouseenter', function(){
                    let videoSrc = $(this).find('.product__card__thumb').attr('data-video-src');
                    let videoElement = $('<video autoplay muted loop></video>').attr('src', videoSrc);
                    
                    $(this).find('.product__card__thumb').append('<span class="product__card__vdo"></span>');
                    $(this).find('.product__card__vdo').append(videoElement);
                });
                
                $('.product__card.product-video').on('mouseleave', function(){
                    $(this).find('.product__card__thumb').find('.product__card__vdo').remove();
                });
            }

            function signInfoModalShow() {
                $('.signInfoBtn').on('click', function() {
                    let modal     = $('#signInfoModal');
                    let labelText = $(this).data('label_text');

                    modal.find('#signInfoModalLabel').text(labelText + ' !');

                    modal.modal('show');
                })
            }


            function likeOperation() {
                $('.likeBtn').on('click', function(event) {
                    event.preventDefault();

                    let button  = $(this);
                    let assetId = $(this).data('asset_id');
                    let userId  = $(this).data('user_id');

                    if (!assetId || !userId) {
                        showToasts('error', 'Missing necessary parameters');
                        return;
                    }

                    let likedText = "{{ trans('Liked') }}";
                    let likeText  = "{{ trans('Like') }}";

                    let data = {
                        'asset_id': assetId,
                        'user_id' : userId
                    };

                    $.ajax({
                        type: "GET",
                        url : "{{ route('user.asset.like') }}",
                        data: data,
                        success: function (response) {

                            if (response.success) {
                                button.addClass('btn--base text-white');
                                button.find('.buttonText').text(likedText);

                                showToasts('success', response.message);

                            } else if (response.warning) {
                                button.removeClass('btn--base text-white');
                                button.find('.buttonText').text(likeText);

                                showToasts('success', response.message);
                            
                            } else {
                                showToasts('error', response.message);
                            }
                        }, 
                        error: function() {
                            showToasts('error', 'Something went wrong while liking')
                        }
                    });
                });
            }


    //__________________________________________Collection Start________________________________________________ 
            let selectedCollections = [];

            function collectionModalList(modal) {
                modal.find('.collection-modal__list ul').off('click', 'li').on('click', 'li', function () {
                    let $this        = $(this);
                    let collectionId = $(this).data('collection-id');
                    let $icon        = $this.find('.collection-modal__list__action .ti');

                    if ($this.hasClass('selected')) {
                        $this.removeClass('selected');
                        $icon.removeClass('ti-circle-minus text--danger').addClass('ti-circle-plus');
                        selectedCollections = selectedCollections.filter(id => id != collectionId);
                    } else {
                        $this.addClass('selected');
                        $icon.removeClass('ti-circle-plus').addClass('ti-circle-minus text--danger');
                        selectedCollections.push(collectionId);
                    }
                    
                    updateHiddenInput(modal);
                });
            }

            function updateHiddenInput(modal) {
                modal.find('[name="collection_ids[]"]').remove();

                selectedCollections.forEach(id => {
                    modal.find('form').append(`<input type="hidden" name="collection_ids[]" value="${id}">`);
                });
            }

            function createNewCollection(modal) {
                modal.find('.addCollectionBtn').off('click').on('click', function() {
                    let title  = modal.find('[name=title]').val();
                    let userId = modal.find('[name=user_id]').val();

                    if (!title) {
                        modal.find('[name=title]').addClass('boarder--danger');
                        showToasts('error', 'This field is required');
                        return false;
                    }

                    let data = {
                        _token : '{{ csrf_token() }}',
                        title : title,
                        user_id: userId
                    };

                    $.ajax({
                        type: "POST",
                        url: "{{ route('user.collection.add') }}",
                        data: data,
                        success: function (response) {
                                if (response.success) {
                                    showToasts('success', response.message);

                                    modal.find('[name=title]').val('');
                                    let collectionUl = modal.find('.collectionUl');
                                    collectionUl.prepend(`
                                        <li data-collection-id="${response.collection.id}">
                                            <span class="collection-modal__list__txt">${response.collection.title}</span>
                                            <span class="collection-modal__list__action"><i class="ti ti-circle-plus"></i></span>
                                        </li>
                                    `);

                                    if (selectedCollections.includes(response.collection.id)) {
                                        collectionUl.find(`li[data-collection-id="${response.collection.id}"]`).addClass('selected');
                                    }

                                    modal.find('[name=collection_ids]').val(selectedCollections.join(','));

                                    collectionModalList(modal);
                                } else {
                                    showToasts('error', response.message);
                                }
                        },
                        error: function() {
                                showToasts('error', 'Something went wrong while creating new collection');
                        }
                    });
                });
            }


            function collectionModalView() {
                $('.collectionBtn').on('click', function() {
                    let modal   = $('#addToCollectionModal');
                    let assetId = $(this).data('asset_id');
                    let userId  = $(this).data('user_id');

                    let data = {
                        asset_id : assetId,
                        user_id  : userId
                    };

                    $.ajax({
                        type: "GET",
                        url : "{{ route('user.collection.modal.view') }}",
                        data: data,
                        success: function (response) {
                                if (response.success) {
                                    modal.find('.modal-content').html(response.html);

                                    selectedCollections = [];

                                    modal.find('.collection-item.selected').each(function() {
                                        selectedCollections.push($(this).data('collection-id'));
                                    });

                                    collectionModalList(modal);

                                    createNewCollection(modal);

                                    handleCollectionFormSubmission(modal);

                                    modal.modal('show');
                                } else {
                                    showToasts('error', response.message);
                                }
                        },
                        error: function() {
                            showToasts('error', 'Something went wrong while viewing collection modal');
                        }
                    });
                });
            }

            function handleCollectionFormSubmission(modal) {
                modal.find('form').off('submit').on('submit', function(e) {
                    e.preventDefault();

                    let form = $(this);
                    let data = form.serialize();

                    $.ajax({
                        type: "POST",
                        url: form.attr('action'),
                        data: data,
                        success: function (response) {
                            if (response.success) {
                                showToasts('success', response.message);
                                modal.modal('hide');
                            } else {
                                showToasts('error', response.message);
                            }
                        },
                        error: function() {
                            showToasts('error', 'Something went wrong while enriching collection');
                        }
                    });
                });
            }
    //__________________________________________Collection End________________________________________________
    
            $(document).on('click', '.shareAssetBtn', function() {
                let modal        = $('#shareAssetModal');
                let route        = $(this).data('route');
                let encodedRoute = $(this).data('encoded_route');
                let title        = $(this).data('asset_title');

                modal.find('.facebookLink').attr('href', `https://www.facebook.com/sharer/sharer.php?u=${encodedRoute}`);
                modal.find('.xLink').attr('href', `https://twitter.com/intent/tweet?url=${encodedRoute}&text=${title}`);
                modal.find('.linkedInLink').attr('href', `https://www.linkedin.com/sharing/share-offsite/?url=${encodedRoute}`);
                modal.find('.copyUrl').val(route);

                modal.find('.shareLinkCopyBtn').on('click', function() {
                    let inputElement = modal.find('.copyUrl');
                    inputElement.select();
                    document.execCommand('copy');

                    $(this).html('<i class="ti ti-circle-check"></i>');

                    setTimeout(() => {
                        modal.find('.shareLinkCopyBtn').html('<i class="ti ti-copy"></i>');
                    }, 1000);
                });

                modal.modal('show');
            });

            $('.downloadBtn').on('click', function() {
                let modal = $('#downloadConfirmationModal');
                let route = $(this).data('route');
                let label = $(this).data('label');

                modal.find('.modal-title').text(label);
                modal.find('a').attr('href', route);

                modal.modal('show');
            });
            

            
        })(jQuery);
    </script>
@endpush