@extends($activeTheme . 'layouts.frontend')
@section('frontend')
    <div class="dashboard">
        @include($activeTheme . 'user.sections.banner')
        <div class="container">
            @include($activeTheme . 'user.sections.nav')

            @yield('auth')
        </div>   
    </div>
@endsection