@extends($activeTheme . 'layouts.frontend')
@section('frontend')
    <section class="user-profile">
        @include($activeTheme . 'authors.banner', ['author' => $author])
        <div class="product py-120">
            <div class="container">
                <div class="product__nav">
                    <a href="{{ route('author.profile', [encrypt($author->id), slug($author->author_name)]) }}" class="product__nav__link active"><i class="ti ti-library-photo"></i> @lang('Assets') ({{ formatNumber($author->images()->approved()->count()) }})</a>
                    <a href="{{ route('author.collections', [encrypt($author->id), slug($author->author_name)]) }}" class="product__nav__link"><i class="ti ti-copy-plus"></i> @lang('Collections') ({{ formatNumber($author->collections->count()) }})</a>
                    <a href="{{ route('author.followers', [encrypt($author->id), slug($author->author_name)]) }}" class="product__nav__link"><i class="ti ti-user-plus"></i> @lang('Followers & Following')</a>
                </div>
                <div id="authorAssetsDiv">
                    @include($activeTheme . 'ajax.authorAssets', ['assets' => $assets, 'user' => $user])
                </div>
            </div>
        </div>
    </section>
    
@endsection

@push('page-script')
    <script>
        (function($) {
            'use strict';

            $(document).on('submit', '.authorAssetsPaginationForm', function(e) {
                e.preventDefault();

                let pageNumber = $(this).find('#pageNumberInput').val();

                authorAssetsPaginationAjax(pageNumber);
            });

            $(document).on('click', '.authorAssetsPagination a', function(e) {
                e.preventDefault();

                let page = $(this).attr('href').split('page=')[1];
                
                authorAssetsPaginationAjax(page);
            });

            function authorAssetsPaginationAjax(page) {
                let baseUrl = `{{ route('author.profile', [encrypt($author->id), slug($author->author_name)]) }}`;

                $.ajax({
                    type    : "GET",
                    url     : baseUrl + "?page=" + page,
                    dataType: "json",
                    success: function (response) {
                        if (response.success) {
                            $(window).scrollTop(0);

                            $('#authorAssetsDiv').html(response.html);
                            
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

            function signInfoModalShow() {
                $('.signInfoBtn').on('click', function() {
                    let modal     = $('#signInfoModal');
                    let labelText = $(this).data('label_text');

                    modal.find('#signInfoModalLabel').text(labelText + ' !');

                    modal.modal('show');
                })
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
                    let title = modal.find('[name=title]').val();
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

        })(jQuery);
    </script>
@endpush