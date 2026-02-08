@extends('admin.layouts.master')

    @section('master')
        <div class="col-12">
            <div class="table-responsive scroll">
                <table class="table table--striped table-borderless table--responsive--sm">
                    <thead>
                        <tr>
                            <th>@lang('S.N.')</th>
                            <th>@lang('Title')</th>
                            <th>@lang('Status')</th>
                            <th>@lang('Action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($reasons as $reason)
                            <tr>
                                <td>{{ $reasons->firstItem() + $loop->index }}</td>
                                <td title="{{ __($reason->title) }}">{{ __(strLimit($reason->title, 25)) }}</td>
                                <td>@php echo $reason->statusBadge; @endphp</td>
                                <td>
                                    <div class="d-flex justify-content-end gap-2">
                                        <button class="btn btn--sm btn-outline--base editBtn" 
                                            data-resource="{{ $reason }}" 
                                            data-action="{{ route('admin.reason.store', $reason->id) }}">
                                            <i class="ti ti-edit"></i> @lang('Edit')
                                        </button>

                                        <div class="custom--dropdown">
                                            <button class="btn btn--icon btn--sm btn--base" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="ti ti-dots-vertical"></i></button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <button type="button" class="dropdown-item text--danger decisionBtn" 
                                                        data-question="@lang('Are you confirming the deletion of this reason')?" 
                                                        data-action="{{ route('admin.reason.delete', $reason->id) }}">
                                                        <span class="dropdown-icon"><i class="ti ti-trash"></i></span> @lang('Delete')
                                                    </button>
                                                </li>
                                                @if ($reason->status)
                                                    <li>
                                                        <button type="button" class="dropdown-item text--warning decisionBtn" 
                                                            data-question="@lang('Are you confirming the inactivation of this reason')?" 
                                                            data-action="{{ route('admin.reason.status', $reason->id) }}">
                                                            <span class="dropdown-icon"><i class="ti ti-ban"></i></span> @lang('Inactive')
                                                        </button>
                                                    </li>
                                                @else
                                                    <li>
                                                        <button type="button" class="dropdown-item text--success decisionBtn" 
                                                            data-question="@lang('Are you confirming the activation of this reason')?" 
                                                            data-action="{{ route('admin.reason.status', $reason->id) }}">
                                                            <span class="dropdown-icon"><i class="ti ti-circle-check"></i></span> @lang('Active')
                                                        </button>
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            @include('partials.noData')
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if ($reasons->hasPages())
                {{ paginateLinks($reasons) }}
            @endif
        </div>

        {{-- Add Modal --}}
        <div class="modal custom--modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title" id="addModalLabel">@lang('New Reason')</h2>
                        <button type="button" class="btn btn--sm btn--icon btn-outline--secondary modal-close" data-bs-dismiss="modal" aria-label="Close"><i class="ti ti-x"></i></button>
                    </div>
                    <form action="{{ route('admin.reason.store') }}" method="POST">
                        @csrf
        
                        <div class="modal-body text-center">
                            <div class="row g-3 align-items-center">
                                <div class="col-12">
                                    <div class="row gy-2">
                                        <div class="col-sm-4">
                                            <label class="col-form--label required">@lang('Title')</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" class="form--control" name="title" required>
                                        </div>
                                    </div>
                                </div>
        
                                <div class="col-12">
                                    <div class="row gy-2">
                                        <div class="col-sm-4">
                                            <label class="col-form--label required">@lang('Description')</label>
                                        </div>
                                        <div class="col-sm-8 editor-wrapper">
                                            <textarea class="form--control" name="description" required></textarea>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
        
                        <div class="modal-footer d-flex justify-content-end gap-2">
                            <button type="button" data-bs-dismiss="modal" class="btn btn--sm btn--secondary">@lang('Close')</button>
                            <button class="btn btn--sm btn--base" type="submit">@lang('Add')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Add Modal --}}
        <div class="modal custom--modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title" id="updatedModalLabel">@lang('Update Reason')</h2>
                        <button type="button" class="btn btn--sm btn--icon btn-outline--secondary modal-close" data-bs-dismiss="modal" aria-label="Close"><i class="ti ti-x"></i></button>
                    </div>
                    <form method="POST">
                        @csrf
        
                        <div class="modal-body text-center">
                            <div class="row g-3 align-items-center">
        
                                <div class="col-12">
                                    <div class="row gy-2">
                                        <div class="col-sm-4">
                                            <label class="col-form--label required">@lang('Title')</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" class="form--control" name="title" required>
                                        </div>
                                    </div>
                                </div>
        
                                <div class="col-12">
                                    <div class="row gy-2">
                                        <div class="col-sm-4">
                                            <label class="col-form--label">@lang('Description')</label>
                                        </div>
                                        <div class="col-sm-8 editor-wrapper">
                                            <textarea class="form--control" name="description" required></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
        
                        <div class="modal-footer d-flex justify-content-end gap-2">
                            <button type="button" data-bs-dismiss="modal" class="btn btn--sm btn--secondary">@lang('Close')</button>
                            <button class="btn btn--sm btn--base" type="submit">@lang('Update')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <x-decisionModal />
    @endsection

    @push('breadcrumb')
        <x-searchForm placeholder="Search with title" />
        <button type="button" class="btn btn--sm btn--base addBtn"><i class="ti ti-circle-plus"></i> @lang('Add New')</button>
    @endpush

    @push('page-script')
        <script>
            (function($) {
                'use strict';

                $('.addBtn').on('click', function() {
                    let modal = $('#addModal');

                    modal.modal('show');
                });

                $('.editBtn').on('click', function() {
                    let modal       = $('#updateModal');
                    let actionRoute = $(this).data('action');
                    let resource    = $(this).data('resource');

                    modal.find('[name=title]').val(resource.title);
                    modal.find('[name=description]').val(resource.description);
                    modal.find('form').attr('action', actionRoute);
                    
                    modal.modal('show');
                });
            })(jQuery);
        </script>
    @endpush