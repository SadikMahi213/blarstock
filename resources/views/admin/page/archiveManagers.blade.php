@extends('admin.layouts.master')

    @section('master')
        <div class="col-12">
            <div class="table-responsive scroll">
                <table class="table table--striped table-borderless table--responsive--sm">
                    <thead>
                        <tr>
                            <th>@lang('S.N.')</th>
                            <th>@lang('Name')</th>
                            <th>@lang('Extension')</th>
                            <th>@lang('Status')</th>
                            <th>@lang('Action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($archiveManagers as $manager)
                            <tr>
                                <td>{{ $archiveManagers->firstItem() + $loop->index }}</td>
                                <td>{{ __($manager->name) }}</td>
                                <td>{{ $manager->extension }}</td>
                                <td>@php echo $manager->statusBadge; @endphp </td>
                                <td>
                                    <div>
                                        <button class="btn btn--sm btn-outline--base editBtn" 
                                            data-resource="{{ $manager }}" 
                                            data-action="{{ route('admin.archive.store', $manager->id) }}">
                                            <i class="ti ti-edit"></i> @lang('Edit')
                                        </button>
        
                                        @if ($manager->status)
                                            <button type="button" class="btn btn--sm btn--warning decisionBtn" 
                                                data-question="@lang('Are you confirming the inactivation of this archive manager')?" 
                                                data-action="{{ route('admin.archive.status', $manager->id) }}">
                                                <i class="ti ti-ban"></i> @lang('Inactive')
                                            </button>
                                        @else
                                            <button type="button" class="btn btn--sm btn--success decisionBtn" 
                                                data-question="@lang('Are you confirming the activation of this archive manager')?" 
                                                data-action="{{ route('admin.archive.status', $manager->id) }}">
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
            
            @if ($archiveManagers->hasPages())
                {{ paginateLinks($archiveManagers) }}
            @endif
        </div>

        {{-- Add Modal --}}
        <div class="modal custom--modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title" id="addModalLabel">@lang('New Archive Manager')</h2>
                        <button type="button" class="btn btn--sm btn--icon btn-outline--secondary modal-close" data-bs-dismiss="modal" aria-label="Close"><i class="ti ti-x"></i></button>
                    </div>
                    <form action="{{ route('admin.archive.store') }}" method="POST">
                        @csrf
        
                        <div class="modal-body text-center">
                            <div class="row g-3 align-items-center">

                                <div class="col-12">
                                    <div class="row gy-2">
                                        <div class="col-sm-4">
                                            <label class="col-form--label required">@lang('Name')</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" class="form--control" name="name" required>
                                        </div>
                                    </div>
                                </div>
        
                                <div class="col-12">
                                    <div class="row gy-2">
                                        <div class="col-sm-4">
                                            <label class="col-form--label required">@lang('Extension')</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" class="form--control" name="extension" placeholder="@lang('Enter extension like .zip, .rar')" required>
                                            <p class="text-start text--muted">@lang('Provide') "<code class="fw-bold">.</code>" @lang('befor extension')</p> 
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
                        <h2 class="modal-title" id="editModalLabel">@lang('Update Archive Manager')</h2>
                        <button type="button" class="btn btn--sm btn--icon btn-outline--secondary modal-close" data-bs-dismiss="modal" aria-label="Close"><i class="ti ti-x"></i></button>
                    </div>
                    <form method="POST">
                        @csrf

                        <div class="modal-body text-center">
                            <div class="row g-3 align-items-center">

                                <div class="col-12">
                                    <div class="row gy-2">
                                        <div class="col-sm-4">
                                            <label class="col-form--label required">@lang('Name')</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" class="form--control" name="name" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="row gy-2">
                                        <div class="col-sm-4">
                                            <label class="col-form--label required">@lang('Extension')</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" class="form--control" name="extension" placeholder="@lang('Enter extension like .zip, .rar')" required>
                                            <p class="text-start text--muted">@lang('Provide') "<code class="fw-bold">.</code>" @lang('befor extension')</p> 
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

                $('.editBtn').on('click', function() {
                    let modal       = $('#editModal');
                    let resource    = $(this).data('resource');
                    let actionRoute = $(this).data('action');

                    modal.find('[name=name]').val(resource.name);
                    modal.find('[name=extension]').val(resource.extension);
                    modal.find('form').attr('action', actionRoute);

                    modal.modal('show');
                });
            })(jQuery);
        </script>
    @endpush