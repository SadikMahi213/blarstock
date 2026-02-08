@extends('reviewer.layouts.app')
@section('content')
    <a class="scroll-top"><i class="ti ti-arrow-bar-up"></i></a>

    @include('reviewer.partials.topbar')
    @include('reviewer.partials.sidebar')

    <div class="main-content">
        <div class="row g-4">
            <div class="col-12">
                <div class="dashboard-top-bar">
                     <h2 class="dashboard-top-bar__title">{{ __($pageTitle) }}</h2>
                     <div class="dashboard-top-bar__action">
                        @stack('breadcrumb')
                     </div>
                </div>
           </div>

            @yield('master')
        </div>
    </div>

    <footer>
        <p>
            <span class="fw-semibold text--base">{{ __(systemDetails()['name']) }}</span> , {{ systemDetails()['version'] }} , © <script>document.write(new Date().getFullYear())</script>
        </p>
   </footer>
@endsection

@push('page-style-lib')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/OverlayScrollbars.min.css') }}">
@endpush

@push('page-script-lib')
    <script src="{{asset('assets/admin/js/overlayScrollbars.min.js')}}"></script>
@endpush
