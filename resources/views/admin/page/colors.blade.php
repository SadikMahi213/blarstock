@extends('admin.layouts.master')

    @section('master')
        <div class="col-12">
            <div class="table-responsive scroll">
                <table class="table table--striped table-borderless table--responsive--sm">
                    <thead>
                        <tr>
                            <th>@lang('S.N.')</th>
                            <th>@lang('Name')</th>
                            <th>@lang('Color Code')</th>
                            <th>@lang('Status')</th>
                            <th>@lang('Action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($colors as $color)
                            <tr>
                                <td>{{ $colors->firstItem() + $loop->index }}</td>
                                <td><span class="d-inline-flex align-items-center gap-2"><span class="p-2 rounded-pill" data-bg-color="{{ $color->code }}"></span> {{ __($color->name) }}</span></td>
                                <td>{{ $color->code }}</td>
                                <td>@php echo $color->statusBadge; @endphp</td>
                                <td>
                                    <div class="d-flex justify-content-end gap-2">
                                        <button class="btn btn--sm btn-outline--base editBtn" 
                                            data-resource="{{ $color }}" 
                                            data-action="{{ route('admin.color.store', $color->id) }}"
                                            >
                                            <i class="ti ti-edit"></i> @lang('Edit')
                                        </button>

                                        <div class="custom--dropdown">
                                            <button class="btn btn--icon btn--sm btn--base" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="ti ti-dots-vertical"></i></button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <button type="button" class="dropdown-item text--danger decisionBtn" 
                                                        data-question="@lang('Are you confirming the deletion of this reviewer')?" 
                                                        data-action="{{ route('admin.color.delete', $color->id) }}">
                                                        <span class="dropdown-icon"><i class="ti ti-trash"></i></span> @lang('Delete')
                                                    </button>
                                                </li>
                                                @if ($color->status)
                                                    <li>
                                                        <button type="button" class="dropdown-item text--warning decisionBtn" 
                                                            data-question="@lang('Are you confirming the inactivation of this color')?" 
                                                            data-action="{{ route('admin.color.status', $color->id) }}">
                                                            <span class="dropdown-icon"><i class="ti ti-ban"></i></span> @lang('Inactive')
                                                        </button>
                                                    </li>
                                                @else
                                                    <li>
                                                        <button type="button" class="dropdown-item text--success decisionBtn" 
                                                            data-question="@lang('Are you confirming the activation of this color')?" 
                                                            data-action="{{ route('admin.color.status', $color->id) }}">
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

            @if ($colors->hasPages())
                {{ paginateLinks($colors) }}
            @endif
        </div>

        {{-- Add Modal --}}
        <div class="modal custom--modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title" id="addModalLabel">@lang('New Color')</h2>
                        <button type="button" class="btn btn--sm btn--icon btn-outline--secondary modal-close" data-bs-dismiss="modal" aria-label="Close"><i class="ti ti-x"></i></button>
                    </div>
                    <form action="{{ route('admin.color.store') }}" method="POST">
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
                                            <label class="col-form--label required">@lang('Code')</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="input--group colorpicker">
                                                <input type="color" class="form--control" value="#{{ $setting->second_color }}">
                                                <input type="text" class="form--control" name="code" value="#{{ $setting->second_color }}" placeholder="@lang('Hex Code e.g. #ffff00')" required>
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

        {{-- Add Modal --}}
        <div class="modal custom--modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title" id="updatedModalLabel">@lang('Update Color')</h2>
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
                                            <label class="col-form--label required">@lang('Code')</label>
                                        </div>
                                        <div class="col-sm-8 color">
                                            
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
        <x-searchForm placeholder="Name or Code" />
        <button type="button" class="btn btn--sm btn--base addBtn"><i class="ti ti-circle-plus"></i> @lang('Add New')</button>
    @endpush

    @push('page-script')
        <script>
            (function($) {
                'use strict';

                $('[data-bg-color]').each(function(){
                    var colorCode = $(this).data('bg-color');
                    $(this).css('background-color', colorCode);
                });

                function changeColorInput() {
                    $('.colorpicker').find('input[type=color]').on('input', function(){
                        var colorCode = $(this).val();
                        $(this).siblings('input').val(colorCode);
                    });
                }

                $('.addBtn').on('click', function() {
                    let modal = $('#addModal');

                    changeColorInput();

                    modal.modal('show');
                });

                $('.editBtn').on('click', function() {
                    let modal       = $('#updateModal');
                    let actionRoute = $(this).data('action');
                    let resource    = $(this).data('resource');

                    modal.find('[name=name]').val(resource.name);
                    modal.find('.color').html(`<div class="input--group colorpicker">
                                                <input type="color" class="form--control" value="${resource.code}">
                                                <input type="text" class="form--control" name="code" value="${resource.code}" placeholder="@lang('Hex Code e.g. #ffff00')" required>
                                            </div>`);
                    modal.find('form').attr('action', actionRoute);

                    changeColorInput();
                    
                    modal.modal('show');
                });
            })(jQuery);
        </script>
    @endpush