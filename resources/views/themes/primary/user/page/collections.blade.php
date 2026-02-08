@extends($activeTheme . 'layouts.auth')
@section('auth')
<div class="py-120">
    <div class="row gy-4">
         <div class="col-12">
              <div class="custom--card h-auto border-0">
                   <div class="card-header d-flex justify-content-between align-items-center gap-2">
                        <h3 class="title">@lang('My Collections')</h3>
                        <span role="button" class="btn btn--sm btn--light py-1 addBtn"><i class="ti ti-circle-plus"></i> @lang('Add Collection')</span>
                   </div>
                   <table class="table table-borderless table--striped top-rounded-0 table--responsive--sm">
                        <thead>
                             <tr>
                                  <th>@lang('Title')</th>
                                  <th>@lang('Resources')</th>
                                  <th>@lang('Visibility')</th>
                                  <th>@lang('Status')</th>
                                  <th>@lang('Action')</th>
                             </tr>
                        </thead>
                        <tbody>
                            @forelse ($collections as $collection)
                                <tr>
                                    <td><a href="{{ route('collection.detail', [encrypt($collection->id), slug($collection->title)]) }}">{{ __($collection->title) }}</a></td>
                                    <td>{{ formatNumber($collection->images_count) }}</td>
                                    <td>
                                        @if ($collection->visibility == ManageStatus::PUBLIC_COLLECTION)
                                            @lang('Public')
                                        @else
                                            @lang('Private')
                                        @endif
                                    </td>
                                    <td>@php echo $collection->statusBadge; @endphp</td>
                                    <td>
                                        <div class="d-flex justify-content-end gap-2">
                                            <span role="button" class="btn btn--sm btn--icon btn--base editBtn" 
                                                data-resource="{{ $collection }}"
                                                data-action="{{ route('user.collection.store', $collection->id) }}">
                                                <i class="ti ti-edit"></i>
                                            </span>
                                            <span role="button" class="btn btn--sm btn--icon btn--danger deleteBtn" 
                                                data-action="{{ route('user.collection.delete', $collection->id) }}">
                                                <i class="ti ti-trash"></i>
                                            </span>
                                        </div>
                                    </td>
                                </tr>   
                            @empty
                                <tr>
                                    <td class="no-data-table" colspan="100%" rowspan="100%">
                                        <div class="no-data-found">
                                            <img src="{{ getImage('assets/universal/images/noData.png') }}" alt="@lang('No collection found')">
                                            <span>@lang('No Collection Found')</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                   </table>
              </div>
         </div>
         
         @if ($collections->hasPages())
            <div class="col-12">
                <div class="row g-3 align-items-center">
                    <div class="col-lg-4 col-md-5 d-flex justify-content-md-start justify-content-center">
                        <span>@lang('Showing') <span class="fw-semibold">{{ $collections->firstItem() }}</span> @lang('to') <span class="fw-semibold">{{ $collections->lastItem() }}</span> @lang('of') <span class="fw-semibold">{{ $collections->total() }}</span> @lang('results')</span>
                    </div>
                    <div class="col-lg-8 col-md-7 authorIndex">
                        <ul class="pagination mt-0 justify-content-md-end justify-content-center">
                            @if ($collections->onFirstPage())
                                <li class="page-item disabled"><span class="page-link"><i class="ti ti-chevrons-left"></i></span></li>
                            @else
                                <li class="page-item"><a class="page-link" href="{{ $collections->previousPageUrl() }}"><i class="ti ti-chevrons-left"></i></a></li>
                            @endif
            
                            @foreach ($collections->links()->elements[0] as $page => $url)
                                <li class="page-item {{ $page == $collections->currentPage() ? 'active' : '' }}"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                            @endforeach
            
                            @if ($collections->hasMorePages())
                                <li class="page-item"><a class="page-link" href="{{ $collections->nextPageUrl() }}"><i class="ti ti-chevrons-right"></i></a></li>
                            @else
                                <li class="page-item disabled"><span class="page-link"><i class="ti ti-chevrons-right"></i></span></li>                
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Add Collection Modal -->
<div class="modal custom--modal fade" id="addCollectionModal" tabindex="-1" aria-labelledby="addCollectionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title fs-5" id="addCollectionModalLabel">@lang('Add Collection')</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('user.collection.store') }}" method="POST">
                @csrf

                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="addCollectionTitle" class="form--label required">@lang('Title')</label>
                            <input type="text" id="addCollectionTitle" class="form--control form--control--sm" name="title" value="{{ old('title') }}" required>
                        </div>
                        <div class="col-12">
                            <label for="addCollectionDesc" class="form--label">@lang('Description')</label>
                            <textarea id="addCollectionDesc" class="form--control form--control--sm" name="description"></textarea>
                        </div>
                        <div class="col-12">
                            <label for="addCollectionVisibility" class="form--label">@lang('Visibility')</label>
                            <select id="addCollectionVisibility" class="form--control form--control--sm form--select" name="visibility" required>
                                <option value="0">@lang('Private')</option>
                                <option value="1">@lang('Public')</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer gap-2 flex-nowrap">
                    <button type="button" class="btn btn--sm btn--secondary w-100" data-bs-dismiss="modal">@lang('Close')</button>
                    <button type="submit" class="btn btn--sm btn--base w-100">@lang('Submit')</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Collection Modal -->
<div class="modal custom--modal fade" id="editCollectionModal" tabindex="-1" aria-labelledby="editCollectionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title fs-5" id="editCollectionModalLabel">@lang('Edit Collection')</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST">
                @csrf
                
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="editCollectionTitle" class="form--label required">@lang('Title')</label>
                            <input type="text" id="editCollectionTitle" class="form--control form--control--sm" name="title" required>
                        </div>
                        <div class="col-12">
                            <label for="editCollectionDesc" class="form--label">@lang('Description')</label>
                            <textarea id="editCollectionDesc" class="form--control form--control--sm" name="description"></textarea>
                        </div>
                        <div class="col-12">
                            <label for="editCollectionVisibility" class="form--label">@lang('Visibility')</label>
                            <select id="editCollectionVisibility" class="form--control form--control--sm form--select" name="visibility" required>
                                <option value="0">@lang('Private')</option>
                                <option value="1">@lang('Public')</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer gap-2 flex-nowrap">
                    <button type="button" class="btn btn--sm btn--secondary w-100" data-bs-dismiss="modal">@lang('Close')</button>
                    <button type="submit" class="btn btn--sm btn--base w-100">@lang('Save changes')</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Collection Modal -->
<div class="modal custom--modal fade" id="deleteCollectionModal" tabindex="-1" aria-labelledby="deleteCollectionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
         <div class="modal-content">
              <div class="modal-header">
                   <h2 class="modal-title fs-5" id="deleteCollectionModalLabel">@lang('Confirmation Alert')!</h2>
                   <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                   <p>@lang('Are you sure to delete this collection') ?</p>
              </div>
              <form method="POST">
                    @csrf

                    <div class="modal-footer gap-2">
                        <button type="button" class="btn btn--sm btn--secondary" data-bs-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn--sm btn--base">@lang('Delete')</button>
                    </div>
              </form>
         </div>
    </div>
</div>
@endsection

@push('page-script')
    <script>
        (function($) {
            'use strict';

            $('.addBtn').on('click', function() {
                let modal = $('#addCollectionModal');

                modal.modal('show');
            });

            $('.editBtn').on('click', function() {
                let modal       = $('#editCollectionModal');
                let resource    = $(this).data('resource');
                let actionRoute = $(this).data('action');

                modal.find('[name=title]').val(resource.title);
                modal.find('[name=description]').val(resource.description);
                modal.find('[name=visibility]').val(resource.visibility);
                modal.find('form').attr('action', actionRoute);

                modal.modal('show');
            });

            $('.deleteBtn').on('click', function() {
                let modal       = $('#deleteCollectionModal');
                let actionRoute = $(this).data('action');

                modal.find('form').attr('action', actionRoute);

                modal.modal('show');
            });
        })(jQuery);
    </script>
@endpush