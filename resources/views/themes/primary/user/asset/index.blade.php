@extends($activeTheme . 'layouts.auth')
@section('auth')
<div class="py-120">
    <div class="row gy-4">
         <div class="col-12">
              <div class="custom--card h-auto">
                   <div class="card-header">
                        <h3 class="title">{{ __($pageTitle)}}</h3>
                   </div>
                   <div class="card-body">
                        <div class="row g-4 justify-content-center">
                              @forelse ($assets as $asset)
                                   <div class="col-xxl-3 col-xl-4 col-md-6">
                                        <div class="asset-card">
                                             <a href="{{ route('user.asset.update', encrypt($asset->id)) }}" class="asset-card__btn"><i class="ti ti-edit"></i></a>
                                             <div class="asset-card__thumb">
                                                  <a href="{{ route('asset.detail', [encrypt($asset->id), slug($asset->title)]) }}">
                                                       <img src="{{ imageUrl(getFilePath('stockImage'), $asset->thumb) }}" alt="image">
                                                  </a>
                                             </div>
                                             <div class="asset-card__txt">
                                                  <a href="{{ route('asset.detail', [encrypt($asset->id), slug($asset->title)]) }}" class="asset-card__title">{{ __($asset->title) }}</a>
                                                  <table class="table table-flush bg-transparent">
                                                       <tbody>
                                                            <tr>
                                                                 <td class="py-2"><span class="fw-semibold">@lang('Category') :</span></td>
                                                                 <td class="py-2">{{ __($asset->category->name) }}</td>
                                                            </tr>
                                                            <tr>
                                                                 <td class="py-2"><span class="fw-semibold">@lang('Type') :</span></td>
                                                                 <td class="py-2">{{ __($asset->fileType->name) }}</td>
                                                            </tr>
                                                            <tr>
                                                                 <td class="py-2"><span class="fw-semibold">@lang('Upload') :</span></td>
                                                                 <td class="py-2">{{ showDateTime($asset->created_at, 'F j, Y') }}</td>
                                                            </tr>
                                                            <tr>
                                                                 <td class="py-2"><span class="fw-semibold">@lang('Total Likes') :</span></td>
                                                                 <td class="py-2">{{ __($asset->total_like) }}</td>
                                                            </tr>
                                                            <tr>
                                                                 <td class="py-2"><span class="fw-semibold">@lang('Total Views') :</span></td>
                                                                 <td class="py-2">{{ __($asset->total_view) }}</td>
                                                            </tr>
                                                            <tr>
                                                                 <td class="py-2"><span class="fw-semibold">@lang('Total Downloads') :</span></td>
                                                                 <td class="py-2">{{ $asset->total_download }}</td>
                                                            </tr>
                                                            <tr>
                                                                 <td class="py-2"><span class="fw-semibold">@lang('Status') :</span></td>
                                                                 <td class="py-2">@php echo $asset->statusBadge; @endphp</td>
                                                            </tr>
                                                       </tbody>
                                                  </table>
                                             </div>
                                        </div>
                                   </div>     
                              @empty
                                  <div class="no-data-found">
                                        <img src="{{ asset('assets/universal/images/noData.png') }}" alt="@lang('No Asset Found')">
                                        <span>@lang('No Asset Found')</span>
                                   </div>
                              @endforelse
                        </div>
                   </div>
              </div>
         </div>
         
         @if ($assets->hasPages())
               <ul class="pagination">
                    @if ($assets->onFirstPage())
                         <li class="page-item disabled"><span class="page-link"><i class="ti ti-chevrons-left"></i></span></li>
                    @else
                         <li class="page-item"><a class="page-link" href="{{ $assets->previousPageUrl() }}"><i class="ti ti-chevrons-left"></i></a></li>
                    @endif

                    @foreach ($assets->links()->elements[0] as $page => $url)
                         <li class="page-item {{ $page == $assets->currentPage() ? 'active' : '' }}"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                    @endforeach

                    @if ($assets->hasMorePages())
                         <li class="page-item"><a class="page-link" href="{{ $assets->nextPageUrl() }}"><i class="ti ti-chevrons-right"></i></a></li>
                    @else
                         <li class="page-item disabled"><span class="page-link"><i class="ti ti-chevrons-right"></i></span></li>                
                    @endif
               </ul>
          @endif
    </div>
</div>
@endsection