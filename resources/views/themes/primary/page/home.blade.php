@extends($activeTheme. 'layouts.frontend')
@section('frontend')
    @include($activeTheme . 'sections.banner')
    @include($activeTheme . 'sections.category')
    @include($activeTheme . 'sections.product')
    
    @include($activeTheme . 'sections.whyChooseUs')
    @include($activeTheme . 'sections.popularCollection')
    @include($activeTheme . 'sections.bundleOffer')
    @include($activeTheme . 'sections.callToAction')
    @include($activeTheme . 'sections.contributor')
    @include($activeTheme . 'sections.callToSell')
@endsection

