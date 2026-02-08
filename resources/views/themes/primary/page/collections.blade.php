@extends($activeTheme . 'layouts.frontend')
@section('frontend')
    @include($activeTheme . 'partials.breadcrumb')

    <div class="product py-120">
        <div class="container">
            <div id="allCollectionDiv">
                @include($activeTheme . 'ajax.collections')
            </div>
        </div>
   </div>
@endsection

@push('page-script')
    <script>
        (function($) {
            'use strict';

            $(document).on('submit', '.allCollectionPaginationForm', function(e) {
                e.preventDefault();

                let pageNumber = $(this).find('#pageNumberInput').val();

                allCollectionPaginationAjax(pageNumber);
            });

            $(document).on('click', '.allCollectionPagination a', function(e) {
                e.preventDefault();

                let page = $(this).attr('href').split('page=')[1];
                
                allCollectionPaginationAjax(page);
            });

            function allCollectionPaginationAjax(page) {

                let baseUrl = `{{ route('collection.index') }}`;
                
                $.ajax({
                    type    : "GET",
                    url     : baseUrl + "?page=" + page,
                    dataType: "json",
                    success: function (response) {
                        if (response.success) {
                            $(window).scrollTop(0);
                            $('#allCollectionDiv').html(response.html);
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