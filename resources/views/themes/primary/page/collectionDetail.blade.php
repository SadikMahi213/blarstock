@extends($activeTheme . 'layouts.frontend')
@section('frontend')
    <div class="product-collection__banner breadcrumb">
    <div class="breadcrumb__bg bg-img" data-background-image="{{ getImage($activeThemeTrue . 'images/site/breadcrumb/' . $content->data_info?->bg_image, '1920x1280') }}"></div>
        <div class="container">
            <h2 class="product-collection__banner__title">{{ __($pageTitle) }}</h2>
            <div class="product-collection__banner__info">
                <span><i class="ti ti-triangle-square-circle"></i> {{ formatNumber($collection->images->count()) }} @lang('Assets')</span>
                <span><i class="ti ti-user"></i> @lang('by') <a href="{{ $collection->user->author_status ? route('author.profile', [encrypt($collection->user_id), slug($collection->user->author_name)]) : route('member.user.profile', [encrypt($collection->user_id), slug($collection->user->username)]) }}">{{ $collection->user->author_status ? __($collection->user->author_name) : __($collection->user->username) }}</a></span>
            </div>
        </div>
    </div>

    <div class="container py-120">
        <div class="product" id="collectionAssetsDiv">
            @include($activeTheme . 'ajax.collectionAssets', ['assets' => $assets, 'user' => $user])
        </div>
    </div>

    @include($activeTheme . 'partials.ads')
@endsection

@push('page-script')
    <script>
        (function($) {
            'use strict';

            $(document).on('click', '.collectionAssetsPagination a', function(e) {
                e.preventDefault();

                let page = $(this).attr('href').split('page=')[1];
                
                collectionAssetsPaginationAjax(page);
            });

            $(document).on('submit', '.collectionAssetPaginationForm', function(e) {
                e.preventDefault();

                let pageNumber = $(this).find('#pageNumberInput').val();

                collectionAssetsPaginationAjax(pageNumber);
            });

            function collectionAssetsPaginationAjax(page) {
                let baseUrl = `{{ route('collection.detail', [encrypt($collection->id), slug($collection->title)]) }}`;

                $.ajax({
                    type    : "GET",
                    url     : baseUrl + "?page=" + page,
                    dataType: "json",
                    success: function (response) {
                        if (response.success) {
                            $(window).scrollTop(0);

                            $('#collectionAssetsDiv').html(response.html);
                            
                            productRowDesign()
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
        })(jQuery);
    </script>
@endpush