@extends($activeTheme . 'layouts.frontend')
@section('frontend')
    @include($activeTheme . 'authors.banner', ['author' => $author])

    <div class="product py-120">
        <div class="container">
            <div class="product__nav">
                <a href="{{ route('author.profile', [encrypt($author->id), slug($author->author_name)]) }}" class="product__nav__link"><i class="ti ti-library-photo"></i> @lang('Assets') ({{ formatNumber($author->images()->approved()->count()) }})</a>
                <a href="{{ route('author.collections', [encrypt($author->id), slug($author->author_name)]) }}" class="product__nav__link"><i class="ti ti-copy-plus"></i> @lang('Collections') ({{ formatNumber($author->collections->count()) }})</a>
                <a href="{{ route('author.followers', [encrypt($author->id), slug($author->author_name)]) }}" class="product__nav__link active"><i class="ti ti-user-plus"></i> @lang('Followers & Following')</a>
            </div>
            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="custom--card">
                        <div class="card-header">
                            <h3 class="title">@lang('Followers')</h3>
                        </div>
                        <div class="card-body">
                            <div class="user-list" id="followers-list" data-offset="28">
                                @if ($followers->isNotEmpty())
                                    @include($activeTheme . 'ajax.followers', ['followers' => $followers])
                                @else
                                    <div class="no-data-found">
                                        <p>@lang('No followers available')</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="custom--card">
                        <div class="card-header">
                            <h3 class="title">@lang('Followings')</h3>
                        </div>
                        <div class="card-body">
                            <div class="user-list" id="followings-list" data-offset="28">
                                @if ($followings->isNotEmpty())
                                    @include($activeTheme . 'ajax.following', ['followings' => $followings])
                                @else
                                    <div class="no-data-found">
                                        <p>@lang('Not following anyone yet')</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
   </div>
@endsection

@push('page-script')
    <script>
        (function($) {
            'use strict';

            let loadingFollowers = false;
            let noMoreFollowers  = false;

            $('#followers-list').on('scroll', function() {
                if (loadingFollowers || noMoreFollowers) {
                    return;
                }

                let authorId  = '{{ $author->id }}';
                let offset    = parseInt($(this).attr('data-offset'));
                let scrollDiv = $(this);

                let scrollPosition = $('#followers-list')[0].scrollHeight - $('#followers-list').scrollTop() - $('#followers-list').outerHeight();

                if (scrollPosition <= 0) {
                    loadingFollowers = true;

                    let data = {
                        author_id: authorId,
                        offset   : offset
                    }

                    $.ajax({
                        type: "GET",
                        url : "{{ route('author.load.more.followers') }}",
                        data: data,
                        success: function (response) {
                            
                            if (response.success && response.html) {
                                $('#followers-list').append(response.html);

                                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title], [data-title], [data-bs-title]'))
                                tooltipTriggerList.map(function (tooltipTriggerEl) {
                                    return new bootstrap.Tooltip(tooltipTriggerEl)
                                });
                            }

                            if (!response.hasMore) {
                                noMoreFollowers = true;
                            } else {
                                let newOffset = offset + response.followers.length;
                                scrollDiv.attr('data-offset', newOffset).data('offset', newOffset);
                            }

                            loadingFollowers = false;

                            if (!response.success) {
                                showToasts('error', response.message);
                            }
                        },
                        'error': function() {
                            loadingFollowers = false;
                            showToasts('error', 'Something went wrong while loading followers');
                        }
                    });
                }
                
            });

            let loadingFollowings = false;
            let noMoreFollowings  = false;

            $('#followings-list').on('scroll', function() {
                if (loadingFollowings || noMoreFollowings) {
                    return;
                }

                let authorId  = '{{ $author->id }}';
                let offset    = parseInt($(this).attr('data-offset'));
                let scrollDiv = $(this);

                let scrollPosition = $('#followings-list')[0].scrollHeight - $('#followings-list').scrollTop() - $('#followings-list').outerHeight();

                if (scrollPosition <= 0) {
                    loadingFollowings = true;

                    let data = {
                        author_id: authorId,
                        offset   : offset
                    }

                    $.ajax({
                        type: "GET",
                        url : "{{ route('author.load.more.followings') }}",
                        data: data,
                        success: function (response) {
                            if (response.success && response.html) {
                                $('#followings-list').append(response.html);

                                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title], [data-title], [data-bs-title]'))
                                tooltipTriggerList.map(function (tooltipTriggerEl) {
                                    return new bootstrap.Tooltip(tooltipTriggerEl)
                                });
                            }

                            if (!response.hasMore) {
                                noMoreFollowings = true;
                            } else {
                                let newOffset = offset + response.followings.length;
                                scrollDiv.attr('data-offset', newOffset).data('offset', newOffset);
                            }

                            if (!response.success) {
                                showToasts('error', response.message);
                            }
                        },
                        error: function() {
                            showToasts('error', 'Something went wrong while loading following authors');
                        }
                    });
                }
            });

        })(jQuery);
    </script>
@endpush