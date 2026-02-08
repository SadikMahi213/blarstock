@extends($activeTheme . 'layouts.auth')
@section('auth')
     <div class="py-120">
          <div class="row gy-4">
               <div class="custom--card border-0">
                    <div class="card-header">
                         <div class="d-flex align-items-center justify-content-between">
                              <h3 class="title">@lang('Social Profiles')</h3>
                              <span role="button" class="btn btn--sm btn--light py-1 socialBtn"><i class="ti ti-circle-plus"></i> @lang('Add New')</span>
                         </div>
                    </div>
                    <table class="table table-striped table-borderless top-rounded-0">
                         <thead>
                              <tr>
                                   <th>@lang('S.N.')</th>
                                   <th>@lang('Name')</th>
                                   <th>@lang('icon')</th>
                                   <th>@lang('link')</th>
                                   <th>@lang('Status')</th>
                                   <th>@lang('Action')</th>
                              </tr>
                         </thead>
                         <tbody>
                              @forelse ($socialAccounts as $account)
                                   <tr>
                                        <td>{{ $socialAccounts->firstItem() + $loop->index }}</td>
                                        <td @if (strLen($account->name) > 25) title={{ __($account->name) }} @endif>{{ __(strLimit(ucfirst($account->name), 25)) }}</td>
                                        <td>@php echo $account->icon; @endphp</td>
                                        <td title="{{ $account->url }}">{{ strLimit($account->url, 20) }}</td>
                                        <td>@php echo $account->statusBadge; @endphp</td>
                                        <td>
                                             @if ($account->status)
                                                  <span class="btn btn--sm btn--icon btn--warning decisionBtn" title="@lang('Inactive')"
                                                       data-label="@lang('Inactivate Account')"
                                                       data-question="@lang('Are you confirming the inactivation of this social account')?" 
                                                       data-action="{{ route('user.social.status', $account->id) }}">
                                                       <i class="ti ti-ban"></i>
                                                  </span>
                                             @else
                                                  <span class="btn btn--sm btn--icon btn--success decisionBtn" title="@lang('Active')"
                                                       data-label="@lang('Activate Account')"
                                                       data-question="@lang('Are you confirming the activation of this social account')?" 
                                                       data-action="{{ route('user.social.status', $account->id) }}">
                                                       <i class="ti ti-check"></i>
                                                  </span>
                                             @endif
                                             <span class="btn btn--sm btn--icon btn--base updateBtn" data-resource="{{ $account }}" data-action="{{ route('user.social.store', $account->id) }}"><i class="ti ti-edit"></i></span>
                                             <span class="btn btn--sm btn--icon btn--danger decisionBtn"
                                                  data-label="@lang('Delete Account')"
                                                  data-question="@lang('Are you confirming the activation of this social account')?" 
                                                  data-action="{{ route('user.social.delete', $account->id) }}">
                                                  <i class="ti ti-trash"></i>
                                             </span>
                                        </td>
                                   </tr>    
                              @empty
                                   <tr>
                                        <td class="no-data-table" colspan="100%" rowspan="100%">
                                             <div class="no-data-found">
                                                  <img src="{{ getImage('assets/universal/images/noData.png') }}" alt="@lang('No collection found')">
                                                  <span>@lang('No Social Profile Account Found')</span>
                                             </div>
                                        </td>
                                   </tr>
                              @endforelse
                         </tbody>
                    </table>
               </div>

               @if ($socialAccounts->hasPages())
                    <div class="col-12">
                         <div class="row g-3 align-items-center">
                              <div class="col-lg-4 col-md-5 d-flex justify-content-md-start justify-content-center">
                                   <span>@lang('Showing') <span class="fw-semibold">{{ $socialAccounts->firstItem() }}</span> @lang('to') <span class="fw-semibold">{{ $socialAccounts->lastItem() }}</span> @lang('of') <span class="fw-semibold">{{ $socialAccounts->total() }}</span> @lang('results')</span>
                              </div>
                              <div class="col-lg-8 col-md-7 authorIndex">
                                   <ul class="pagination mt-0 justify-content-md-end justify-content-center">
                                        @if ($socialAccounts->onFirstPage())
                                             <li class="page-item disabled"><span class="page-link"><i class="ti ti-chevrons-left"></i></span></li>
                                        @else
                                             <li class="page-item"><a class="page-link" href="{{ $socialAccounts->previousPageUrl() }}"><i class="ti ti-chevrons-left"></i></a></li>
                                        @endif
                         
                                        @foreach ($socialAccounts->links()->elements[0] as $page => $url)
                                             <li class="page-item {{ $page == $socialAccounts->currentPage() ? 'active' : '' }}"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                                        @endforeach
                         
                                        @if ($socialAccounts->hasMorePages())
                                             <li class="page-item"><a class="page-link" href="{{ $socialAccounts->nextPageUrl() }}"><i class="ti ti-chevrons-right"></i></a></li>
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

     <div class="modal custom--modal fade" id="socialAddModal" tabindex="-1" aria-labelledby="socialAddModalLabel" aria-hidden="true">
          <div class="modal-dialog">
               <div class="modal-content">
                    <div class="modal-header">
                         <h3 class="modal-title" id="socialAddModalLabel">@lang('Add Social Profile')</h3>
                         <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('user.social.store') }}" method="POST">
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
                                             <label class="col-form--label required">@lang('Icon')</label>
                                        </div>
                                        <div class="col-sm-8">
                                             <div class="input--group">
                                                  <input type="text" class="form--control iconPicker icon" name="icon" autocomplete="off" required>
                                                  <span class="input-group-text input-group-addon" data-icon="ti ti-home" role="iconpicker"></span>
                                             </div>
                                        </div>
                                        </div>
                                   </div>

                                   <div class="col-12">
                                        <div class="row gy-2">
                                             <div class="col-sm-4">
                                                  <label class="col-form--label required">@lang('Url')</label>
                                             </div>
                                             <div class="col-sm-8">
                                                  <input type="url" class="form--control" name="url" required>
                                             </div>
                                        </div>
                                   </div>
                              </div>
                         </div>

                         <div class="modal-footer d-flex justify-content-end gap-2">
                              <button type="button" data-bs-dismiss="modal" class="btn btn--sm btn--secondary">@lang('Close')</button>
                              <button class="btn btn--sm btn--base" type="submit">@lang('Save')</button>
                         </div>
                    </form>
               </div>
          </div>
     </div>

     <div class="modal custom--modal fade" id="socialUpdateModal" tabindex="-1" aria-labelledby="socialUpdateModalLabel" aria-hidden="true">
          <div class="modal-dialog">
               <div class="modal-content">
                    <div class="modal-header">
                         <h3 class="modal-title" id="socialUpdateModalLabel">@lang('Add Social Profile')</h3>
                         <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="" method="POST">
                         @csrf

                         <div class="modal-body text-center">
                              <div class="row g-3 align-items-center">
                                   
                                   <div class="col-12">
                                        <div class="row gy-2">
                                             <div class="col-sm-4">
                                                  <label class="col-form--label required">@lang('Name')</label>
                                             </div>
                                             <div class="col-sm-8">
                                                  <input type=" text" class="form--control" name="name" required>
                                             </div>
                                        </div>
                                   </div>

                                   <div class="col-12">
                                        <div class="row gy-2">
                                        <div class="col-sm-4">
                                             <label class="col-form--label required">@lang('Icon')</label>
                                        </div>
                                        <div class="col-sm-8">
                                             <div class="input--group">
                                                  <input type="text" class="form--control iconPicker icon" name="icon" autocomplete="off" required>
                                                  <span class="input-group-text input-group-addon" data-icon="ti ti-home" role="iconpicker"></span>
                                             </div>
                                        </div>
                                        </div>
                                   </div>

                                   <div class="col-12">
                                        <div class="row gy-2">
                                             <div class="col-sm-4">
                                                  <label class="col-form--label required">@lang('Url')</label>
                                             </div>
                                             <div class="col-sm-8">
                                                  <input type="url" class="form--control" name="url" required>
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

     <div class="modal custom--modal fade" id="decisionModal" tabindex="-1" aria-labelledby="decisionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title fs-5" id="decisionModalLabel"></h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3 align-items-center">
                        <div class="col-sm-12">
                            <label class="col-form--label questionText"></label>
                        </div>
                        
                        <form action="" method="POST">
                              @csrf

                              <div class="d-flex justify-content-center gap-2 mt-3">
                                   <button type="button" data-bs-dismiss="modal" class="btn btn--sm btn--secondary">@lang('Close')</button>
                                   <button class="btn btn--sm btn--base" type="submit">@lang('Yes')</button>
                              </div>
                         </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('page-style')
     <style>
          .iconpicker-popover.fade {
               opacity: 1;
          }
     </style>
@endpush

@push('page-style-lib')
     <link rel="stylesheet" href="{{ asset($activeThemeTrue . 'css/page/iconpicker.css') }}">
@endpush

@push('page-script-lib')
     <script src="{{ asset($activeThemeTrue . 'js/page/iconpicker.js') }}"></script>
@endpush



@push('page-script')
    <script>
     (function($) {
          'use strict';

          
          $('.iconPicker').iconpicker().on('iconpickerSelected', function (e) {
               $(this).closest('.input--group').find('.iconpicker-input').val(`<i class="${e.iconpickerValue}"></i>`);
          });

          $('.socialBtn').on('click', function() {
               let modal = $('#socialAddModal');

               modal.modal('show');
          });

          $('.updateBtn').on('click', function() {
               let modal    = $('#socialUpdateModal');
               let resource = $(this).data('resource');
               let action   = $(this).data('action');

               modal.find('[name=name]').val(resource.name);
               modal.find('[name=icon]').val(resource.icon);
               modal.find('[name=url]').val(resource.url);
               modal.find('form').attr('action', action);

               modal.modal('show');
          });

          $('.decisionBtn').on('click', function() {
               let modal = $('#decisionModal');
               let data  = $(this).data();

               modal.find('#decisionModalLabel').text(`${data.label}`);
               modal.find('.questionText').text(`${data.question}`);
               modal.find('form').attr('action', `${data.action}`);

               modal.modal('show');
          });
     })(jQuery);
    </script>
@endpush