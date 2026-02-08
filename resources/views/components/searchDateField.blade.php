<div class="input--group">
    <input type="search" class="form--control form--control--sm datepicker-here" name="date" value="{{ request()->date }}" data-range="true" data-multiple-dates-separator=" - " data-language="en" placeholder="@lang('Start Date - End Date')" autocomplete="off">
    <button class="btn btn--sm btn--icon btn--base" type="submit"><i class="ti ti-search"></i></button>
</div>

@push('page-style-lib')
    <link rel="stylesheet" href="{{ asset('assets/universal/css/datepicker.css') }}">
@endpush

@push('page-script-lib')
    <script src="{{ asset('assets/universal/js/datepicker.js') }}"></script>
    <script src="{{ asset('assets/universal/js/datepicker.en.js') }}"></script>
@endpush

@push('page-script')
    <script>
        (function ($) {
            "use strict";

            $('.datepicker-here').on('input keyup keydown keypress', function () {
                return false;
            });
            
            if(!$('.datepicker-here').val()){
                $('.datepicker-here').datepicker();
            }
        })(jQuery);
    </script>
@endpush

