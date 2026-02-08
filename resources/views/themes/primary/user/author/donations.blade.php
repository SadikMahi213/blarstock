@extends($activeTheme . 'layouts.auth')
@section('auth')
<div class="py-120">
    <div class="custom--card border-0">
         <div class="card-header">
              <h3 class="title">{{ __($pageTitle) }}</h3>
         </div>
         <table class="table table-borderless table--striped top-rounded-0 table--responsive--md">
              <thead>
                   <tr>
                        <th>@lang('S.N.')</th>
                        <th>@lang('Donor Name')</th>
                        <th>@lang('Amount')</th>
                        <th>@lang('Status')</th>
                        <th>@lang('Received at')</th>
                   </tr>
              </thead>
              <tbody>
                    @forelse ($donations as $donation)
                        <tr>
                            <td>{{ $donations->firstItem() + $loop->index }}</td>
                            <td>{{ ($donation->donation_sender?->name ?? '') }}</td>
                            <td class="fw-bold">{{ showAmount($donation->amount) }} {{ __($donation->method_currency) }}</td>
                            <td>@php echo $donation->statusBadge; @endphp</td>
                            <td>{{ showDateTime($donation->updated_at, 'M d, Y - h:i A') }}</td>
                        </tr>     
                    @empty
                        <tr>
                            <td class="no-data-table" colspan="100%" rowspan="100%">
                                <div class="no-data-found">
                                    <img src="{{ getImage('assets/universal/images/noData.png') }}" alt="@lang('No Donation found')">
                                    <span>@lang('No donation found')</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
              </tbody>
         </table>
    </div>

    @if ($donations->hasPages())
        <ul class="pagination">
            @if ($donations->onFirstPage())
                <li class="page-item disabled"><span class="page-link"><i class="ti ti-chevrons-left"></i></span></li>
            @else
                <li class="page-item"><a class="page-link" href="{{ $donations->previousPageUrl() }}"><i class="ti ti-chevrons-left"></i></a></li>
            @endif

            @foreach ($donations->links()->elements[0] as $page => $url)
                <li class="page-item {{ $page == $donations->currentPage() ? 'active' : '' }}"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
            @endforeach

            @if ($donations->hasMorePages())
                <li class="page-item"><a class="page-link" href="{{ $donations->nextPageUrl() }}"><i class="ti ti-chevrons-right"></i></a></li>
            @else
                <li class="page-item disabled"><span class="page-link"><i class="ti ti-chevrons-right"></i></span></li>                
            @endif
       </ul>
    @endif
</div>
@endsection