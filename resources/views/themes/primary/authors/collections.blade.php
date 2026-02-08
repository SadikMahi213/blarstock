@extends($activeTheme . 'layouts.frontend')
@section('frontend')
    @include($activeTheme . 'authors.banner', ['author' => $author])

    <div class="product py-120">
        <div class="container">
            <div class="product__nav">
                <a href="{{ route('author.profile', [encrypt($author->id), slug($author->author_name)]) }}" class="product__nav__link"><i class="ti ti-library-photo"></i> @lang('Assets') ({{ formatNumber($author->images()->approved()->count()) }})</a>
                <a href="{{ route('author.collections', [encrypt($author->id), slug($author->author_name)]) }}" class="product__nav__link active"><i class="ti ti-copy-plus"></i> @lang('Collections') ({{ formatNumber($author->collections->count()) }})</a>
                <a href="{{ route('author.followers', [encrypt($author->id), slug($author->author_name)]) }}" class="product__nav__link"><i class="ti ti-user-plus"></i> @lang('Followers & Following')</a>
            </div>

            <div id="authorCollectionDiv">
                @include($activeTheme . 'ajax.authorCollections', ['collections' => $collections, 'author' => $author])
            </div>
            
        </div>
   </div>
@endsection

@push('page-script')
    <script>
        (function($) {
            'use strict';

            $(document).on('submit', '.authorCollectionPaginationForm', function(e) {
                e.preventDefault();

                let pageNumber = $(this).find('#pageNumberInput').val();

                authorCollectionPaginationAjax(pageNumber);
            });

            $(document).on('click', '.authorCollectionPagination a', function(e) {
                e.preventDefault();

                let page = $(this).attr('href').split('page=')[1];
                
                authorCollectionPaginationAjax(page);
            });

            function authorCollectionPaginationAjax(page) {

                let baseUrl = `{{ route('author.collections', [encrypt($author->id), slug($author->author_name)]) }}`;
                
                $.ajax({
                    type    : "GET",
                    url     : baseUrl + "?page=" + page,
                    dataType: "json",
                    success: function (response) {
                        if (response.success) {
                            $(window).scrollTop(0);
                            $('#authorCollectionDiv').html(response.html);
                        } else {
                            showToasts('error', response.message);
                        }
                    },
                    error: function() {
                        showToasts('error', 'Something went wrong');
                    }
                });
            }
        })(jQuery);
    </script>
@endpush