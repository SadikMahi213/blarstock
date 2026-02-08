@extends('admin.layouts.master')

    @section('master')
        <div class="col-12">
            <div class="table-responsive scroll">
                <table class="table table--striped table-borderless table--responsive--sm">
                    <thead>
                        <tr>
                            <th>@lang('S.N.')</th>
                            <th>@lang('Resolution')</th>
                            <th>@lang('Status')</th>
                            <th>@lang('Action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($resolutions as $resolution)
                            <tr>
                                <td>{{ $resolutions->firstItem() + $loop->index }}</td>
                                <td>{{ $resolution->resolution }}</td>
                                <td>@php echo $resolution->statusBadge; @endphp </td>
                                <td>
                                    <div>
                                        <button class="btn btn--sm btn-outline--base editBtn" 
                                            data-resource="{{ $resolution }}" 
                                            data-action="{{ route('admin.resolution.store', $resolution->id) }}">
                                            <i class="ti ti-edit"></i> @lang('Edit')
                                        </button>
        
                                        @if ($resolution->status)
                                            <button type="button" class="btn btn--sm btn--warning decisionBtn" 
                                                data-question="@lang('Are you confirming the inactivation of this resolution')?" 
                                                data-action="{{ route('admin.resolution.status', $resolution->id) }}">
                                                <i class="ti ti-ban"></i> @lang('Inactive')
                                            </button>
                                        @else
                                            <button type="button" class="btn btn--sm btn--success decisionBtn" 
                                                data-question="@lang('Are you confirming the activation of this resolution')?" 
                                                data-action="{{ route('admin.resolution.status', $resolution->id) }}">
                                                <i class="ti ti-circle-check"></i> @lang('Active')
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            @include('partials.noData')
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($resolutions->hasPages())
                {{ paginateLinks($resolutions) }}
            @endif
        </div>

        {{-- Add Modal --}}
        <div class="modal custom--modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title" id="addModalLabel">@lang('New Resolution')</h2>
                        <button type="button" class="btn btn--sm btn--icon btn-outline--secondary modal-close" data-bs-dismiss="modal" aria-label="Close"><i class="ti ti-x"></i></button>
                    </div>
                    <form action="{{ route('admin.resolution.store') }}" method="POST">
                        @csrf
        
                        <div class="modal-body text-center">
                            <div class="row g-3 align-items-center">

                                <div class="col-12">
                                    <div class="row gy-2">
                                        <div class="col-sm-4">
                                            <label class="col-form--label required">@lang('Resolution')</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="input--group">
                                                <input type="text" class="form--control" name="resolution" placeholder="@lang('E.g 600x1200')" required>
                                                <span class="input-group-text">@lang('PX')</span>
                                            </div>
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

        {{-- Update Modal --}}
        <div class="modal custom--modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title" id="editModalLabel">@lang('Update Resolution')</h2>
                        <button type="button" class="btn btn--sm btn--icon btn-outline--secondary modal-close" data-bs-dismiss="modal" aria-label="Close"><i class="ti ti-x"></i></button>
                    </div>
                    <form method="POST">
                        @csrf

                        <div class="modal-body text-center">
                            <div class="row g-3 align-items-center">

                                <div class="col-12">
                                    <div class="row gy-2">
                                        <div class="col-sm-4">
                                            <label class="col-form--label required">@lang('Resolution')</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="input--group">
                                                <input type="text" class="form--control" name="resolution" placeholder="@lang('E.g 600x1200')" required>
                                                <span class="input-group-text">@lang('PX')</span>
                                            </div>
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
        <x-searchForm placeholder="Name or Extension" />
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

                $(document).on('change', '[name=resolution]', function() {
                    let inputValue = $(this).val().trim();
                    let pattern = /^\d+x\d+$/;

                    if (!pattern.test(inputValue)) {
                        $(this).val('');

                        showToasts('warning', 'Invalid input format');
                    }
                });

                $('.editBtn').on('click', function() {
                    let modal       = $('#editModal');
                    let resource    = $(this).data('resource');
                    let actionRoute = $(this).data('action');

                    modal.find('[name=resolution]').val(resource.resolution);
                    modal.find('form').attr('action', actionRoute);

                    modal.modal('show');
                });
            })(jQuery);
        </script>
    @endpush