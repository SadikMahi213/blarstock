@extends($activeTheme . 'layouts.auth')
@section('auth')
    <div class="py-120">
        <div class="custom--card border-0">
            <div class="card-header">
                <h3 class="title">{{ __($pageTitle) }}</h3>
            </div>
            <table class="table table-borderless table--striped top-rounded-0 table--responsive--lg">
                <thead>
                    <tr>
                        <th>@lang('Date')</th>
                        <th>@lang('Asset Title')</th>
                        <th>@lang('Amount')</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($earnings as $earning)
                        <tr>
                            <td>{{ showDateTime($earning->created_at, 'M d, Y') }}</td>
                            <td>{{ __($earning->imageFile->image->title) }}</td>
                            <td class="fw-bold">{{ showAmount($earning->amount) }} {{ __($setting->site_cur) }}</td>
                        </tr>    
                    @empty
                        <tr>
                            <td class="no-data-table" colspan="100%" rowspan="100%">
                                <div class="no-data-found">
                                    <img src="{{ getImage('assets/universal/images/noData.png') }}" alt="@lang('No earnings found')">
                                    <span>@lang('No earnings found')</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($earnings->hasPages())
            <ul class="pagination">
                @if ($earnings->onFirstPage())
                    <li class="page-item disabled"><span class="page-link"><i class="ti ti-chevrons-left"></i></span></li>
                @else
                    <li class="page-item"><a class="page-link" href="{{ $earnings->previousPageUrl() }}"><i class="ti ti-chevrons-left"></i></a></li>
                @endif

                @foreach ($earnings->links()->elements[0] as $page => $url)
                    <li class="page-item {{ $page == $earnings->currentPage() ? 'active' : '' }}"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                @endforeach

                @if ($earnings->hasMorePages())
                    <li class="page-item"><a class="page-link" href="{{ $earnings->nextPageUrl() }}"><i class="ti ti-chevrons-right"></i></a></li>
                @else
                    <li class="page-item disabled"><span class="page-link"><i class="ti ti-chevrons-right"></i></span></li>                
                @endif
            </ul>
        @endif
    </div>
@endsection