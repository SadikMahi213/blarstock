@extends('admin.layouts.master')

    @section('master')
        <div class="col-12">
            <div class="table-responsive scroll">
                <table class="table table--striped table-borderless table--responsive--sm">
                    <thead>
                        <tr>
                            <th>@lang('Author')</th>
                            <th>@lang('Username')</th>
                            <th>@lang('Assets')</th>
                            <th>@lang('Joined')</th>
                            <th>@lang('Status')</th>
                            <th>@lang('Action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($authors as $author)
                            <tr>
                                <td>
                                    <div class="table-card-with-image">
                                        <div class="table-card-with-image__img">
                                            <img src="{{ getImage(getFilePath('userProfile').'/'.$author->image, getFileSize('userProfile'), true) }}" alt="Image">
                                        </div>
                                        <div class="table-card-with-image__content">
                                            <p class="fw-semibold" title="{{ __($author->author_name) }}">{{ __(strLimit($author->author_name, 20)) }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <a href="{{ route('admin.user.details', $author->id) }}" title="{{ $author->username }}"><small>@</small>{{ strLimit($author->username, 20) }}</a>
                                </td>
                                <td>{{ $author->approved_images_count }}</td>
                                <td>
                                    @if ($author->joined_at)
                                        {{ showDateTime($author->joined_at, 'M d, Y - h:i A') }}</span>
                                    @else
                                        @lang('Not Joined')
                                    @endif
                                </td>
                                <td>@php echo $author->authorStatusBadge; @endphp </td>
                                <td>
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="#authorCanvas" class="btn btn--sm btn-outline--base detailBtn"
                                            data-bs-toggle="offcanvas"
                                            data-author_name="{{ __($author->author_name) }}"
                                            data-author_data="{{ json_encode($author->author_data) }}"
                                            data-total_follower="{{ $author->total_follower }}"
                                            data-total_following="{{ $author->total_following }}"
                                            data-total_download="{{ $author->total_others_download }}"
                                            data-joined_at="{{ $author->joined_at ? showDateTime($author->joined_at) : trans('Not Joined') }}"
                                            data-balance="{{ ($setting->cur_sym) . showAmount($author->balance) }}"
                                            data-total_earning="{{ showAmount($author->total_earnings_sum) }}"
                                            data-author_status="{{ $author->author_status }}">
                                            <i class="ti ti-info-square-rounded"></i> @lang('Details')
                                        </a>

                                        <div class="custom--dropdown">
                                            <button class="btn btn--icon btn--sm btn--base" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="ti ti-dots-vertical"></i></button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a href="{{route('admin.user.login', $author->id)}}" target="_blank" class="dropdown-item text--info"><span class="dropdown-icon"><i class="ti ti-login-2"></i></span> {{ $author->author_status == ManageStatus::AUTHOR_APPROVED ? trans('Login as Author') : trans('Login as User')}}</a>
                                                </li>

                                                @if ($author->author_status == ManageStatus::AUTHOR_PENDING || $author->author_status == ManageStatus::AUTHOR_REJECTED)
                                                    <li>
                                                        <button type="button" class="dropdown-item text--success authorBtn" 
                                                            data-question="@lang('Are you confirming the approval of this author')?" 
                                                            data-action="{{ Route('admin.author.status', $author->id) }}"
                                                            data-step="approve">
                                                            <span class="dropdown-icon"><i class="ti ti-circle-check"></i></span> @lang('Approve')
                                                        </button>
                                                    </li>
                                                    <li>
                                                        <button type="button" class="dropdown-item text--danger authorBtn" 
                                                            data-question="@lang('Are you confirming the rejection of this author')?" 
                                                            data-action="{{ route('admin.author.status', $author->id) }}"
                                                            data-step="reject">
                                                            <span class="dropdown-icon"><i class="ti ti-circle-dashed-x"></i></span> @lang('Reject')
                                                        </button>
                                                    </li>    
                                                @elseif ($author->author_status == ManageStatus::AUTHOR_APPROVED)
                                                    <li>
                                                        <button type="button" class="dropdown-item text--success balanceUpdateBtn" data-act="add" data-action="{{ route('admin.user.add.sub.balance', $author->id) }}"><span class="dropdown-icon"><i class="ti ti-circle-plus"></i></span> @lang('Add Balance')</button>
                                                    </li>
                                                    <li>
                                                        <button type="button" class="dropdown-item text--dark balanceUpdateBtn" data-act="sub" data-action="{{ route('admin.user.add.sub.balance', $author->id) }}"><span class="dropdown-icon"><i class="ti ti-circle-minus"></i></span> @lang('Sub Balance')</button>
                                                    </li>
                                                    <li>
                                                        <button type="button" class="dropdown-item text--warning authorBtn" 
                                                            data-question="@lang('Are you confirming the ban of this author')?" 
                                                            data-action="{{ Route('admin.author.status', $author->id) }}"
                                                            data-step="ban">
                                                            <span class="dropdown-icon"><i class="ti ti-ban"></i></span> @lang('Ban')
                                                        </button>
                                                    </li>
                                                @elseif($author->author_status ==ManageStatus::AUTHOR_BANNED)
                                                    <li>
                                                        <button type="button" class="dropdown-item text--danger authorBtn" 
                                                            data-question="@lang('Are you confirming the allow of this author')?" 
                                                            data-action="{{ route('admin.author.status', $author->id) }}"
                                                            data-step="allow">
                                                            <span class="dropdown-icon"><i class="ti ti-circle-check"></i></span> @lang('Unban')
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

            @if ($authors->hasPages()) 
                {{ paginateLinks($authors) }}
            @endif
        </div>

        <div class="col-12">
            <div class="custom--offcanvas offcanvas offcanvas-end" tabindex="-1" id="authorCanvas" aria-labelledby="authorLabel">
                <div class="offcanvas-header">
                     <h5 class="offcanvas-title" id="authorLabel">@lang('Author Details')</h5>
                     <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                     <h6 class="mb-2">@lang('Basic Information')</h6>
                     <table class="table table-borderless mb-3">
                          <tbody class="basic-details"></tbody>
                     </table>
    
                     <div class="author-data"></div>
                </div>
           </div>
        </div>

        <div class="col-12">
            <div class="custom--modal modal fade" id="authorModal" tabindex="-1" aria-labelledby="authorModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                     <div class="modal-content">
                          <button type="button" class="btn btn--sm btn--icon btn-outline--secondary modal-close" data-bs-dismiss="modal" aria-label="Close"><i class="ti ti-x"></i></button>
                          <div class="modal-body modal-alert">
                               <div class="text-center">
                                    <div class="modal-thumb">
                                         <img src="{{ asset('assets/admin/images/light.png') }}" alt="Image">
                                    </div>
                                    <h2 class="modal-title" id="decisionModalLabel">@lang('Make Your Decision')</h2>
                                    <div class="onboarding-info question"></div>
        
                                    <form action="" method="POST">
                                        @csrf

                                        <input type="hidden" name="step" required>

                                        <div class="col-12 reasonDiv">
                                            <div class="row gy-2">
                                                <div class="col-sm-12 editor-wrapper">
                                                    <textarea class="form--control" name="reason" placeholder="@lang('Reason: ')"></textarea>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-center gap-2 mt-3 buttonDiv">
                                            <button type="button" data-bs-dismiss="modal" class="btn btn--sm btn--secondary">@lang('Close')</button>
                                            <button class="btn btn--sm btn--base" type="submit">@lang('Yes')</button>
                                        </div>
                                    </form>
                               </div>
                          </div>
                     </div>
                </div>
           </div>
        </div>

        <div class="col-12">
            <div class="custom--modal modal fade" id="balanceUpdateModal" tabindex="-1" aria-labelledby="balanceUpdateModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h2 class="modal-title" id="balanceUpdateModalLabel">@lang('Add Balance')</h2>
                            <button type="button" class="btn btn--sm btn--icon btn-outline--secondary modal-close" data-bs-dismiss="modal" aria-label="Close"><i class="ti ti-x"></i></button>
                        </div>
                        <form action="" method="POST">
                            @csrf

                            <input type="hidden" name="act">

                            <div class="modal-body">
                                <div class="row g-3">
                                    <div class="rol-12">
                                        <label class="form--label required">@lang('Amount')</label>
                                        <div class="input--group">
                                            <input type="number" step="any" min="0" class="form--control form--control--sm" name="amount" placeholder="@lang('Kindly enter an amount that is positive')" required>
                                            <span class="input-group-text">{{ __($setting->site_cur) }}</span>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <label class="form--label required">@lang('Remark')</label>
                                        <textarea class="form--control form--control--sm" name="remark" placeholder="@lang('Remark')" required></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer gap-2">
                                <button type="button" class="btn btn--sm btn--secondary" data-bs-dismiss="modal">@lang('Close')</button>
                                <button class="btn btn--sm btn--base" type="submit">@lang('Submit')</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endsection

    @push('breadcrumb')
        <x-searchForm placeholder="Search" />
    @endpush

    @push('page-script')
        <script>
            (function($) {
                'use strict';

                $('.authorBtn').on('click', function() {
                    let modal    = $('#authorModal');
                    let step     = $(this).data('step');
                    let action   = $(this).data('action');
                    let question = $(this).data('question');

                    if (step == 'allow' || step == 'approve') {
                        modal.find('.reasonDiv').addClass('d-none');
                        modal.find('[name=reason]').prop('disabled', true);
                    } else {
                        modal.find('.reasonDiv').removeClass('d-none');
                        modal.find('[name=reason]').prop('disabled', false).prop('required', true);
                    }

                    modal.find('[name=step]').val(step);
                    modal.find('.question').text(question);
                    modal.find('form').attr('action', action);

                    modal.modal('show');
                });

                $('.balanceUpdateBtn').on('click', function () {
                    let modal  = $('#balanceUpdateModal');
                    let act    = $(this).data('act');
                    let action = $(this).data('action');

                    modal.find('[name=act]').val(act);

                    if (act == 'add') {
                        modal.find('.modal-title').text(`@lang('Add Balance')`);
                    }else{
                        modal.find('.modal-title').text(`@lang('Subtract Balance')`);
                    }

                    modal.find('form').attr('action', action);

                    modal.modal('show');
                });

                $('.detailBtn').on('click', function() {
                    let authorName = $(this).data('author_name');
                    
                    let authorData = $(this).data('author_data');

                    let basicHtml = ``;
                                    
                    basicHtml += `
                                    <tr>
                                        <td class="fw-bold">@lang('Author Name')</td>
                                        <td>${authorName}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">@lang('Joined At')</td>
                                        <td>${$(this).data('joined_at')}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">@lang('Total Follower')</td>
                                        <td>${$(this).data('total_follower')}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">@lang('Total Following')</td>
                                        <td>${$(this).data('total_following')}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">@lang('Total Download')</td>
                                        <td>${$(this).data('total_download')}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">@lang('Total Earned')</td>
                                        <td>{{ $setting->cur_sym }}${$(this).data('total_earning')}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">@lang('Total Balance')</td>
                                        <td>${$(this).data('balance')}</td>
                                    </tr>

                    `

                    if ($(this).data('author_status') == '{{ ManageStatus::AUTHOR_APPROVED }}') {
                        basicHtml += `
                                    <tr>
                                        <td class="fw-bold">@lang('Status')</td>
                                        <td><span class="badge badge--success">@lang('Approved')</span></td>
                                    </tr>
                        `;
                    } else if ($(this).data('author_status') == '{{ ManageStatus::AUTHOR_PENDING }}') {
                        basicHtml += `
                                    <tr>
                                        <td class="fw-bold">@lang('Status')</td>
                                        <td><span class="badge badge--warning">@lang('Pending')</span></td>
                                    </tr>
                                `;
                    } else if ($(this).data('author_status') == '{{ ManageStatus::AUTHOR_REJECTED }}') {
                        basicHtml += `
                                    <tr>
                                        <td class="fw-bold">@lang('Status')</td>
                                        <td><span class="badge badge--danger">@lang('Rejected')</span></td>
                                    </tr>
                                `;
                    } else if ($(this).data('author_status') == '{{ ManageStatus::AUTHOR_BANNED }}') {
                        basicHtml += `
                                    <tr>
                                        <td class="fw-bold">@lang('Status')</td>
                                        <td><span class="badge badge--dark">@lang('Banned')</span></td>
                                    </tr>
                                `;
                    }
                    

                    $('.basic-details').html(basicHtml);

                    if (authorData) {
                        let fileDownloadUrl = '{{ route("admin.file.download", ["filePath" => "verify"]) }}';
                        let infoHtml = `<h6 class="mb-2">@lang('Author Data')</h6>
                                            <table class="table table-borderless mb-3">
                                                <tbody>`;

                        authorData.forEach(element => {
                            if (!element.value) {
                                return;
                            }

                            if (element.type != 'file') {
                                let valueHtml = ``;

                                if (Array.isArray(element.value)) {
                                    element.value.forEach((value, index) => {
                                        if (index === element.value.length -  1) {
                                            valueHtml += `<p class="mb-0 text-end">${value}</p>`;
                                        } else {
                                            valueHtml += `<p class="mb-0 text-end">${value}, </p>`;
                                        }
                                    });
                                } else {
                                    valueHtml += `${element.value}`;
                                }

                                infoHtml += `<tr>
                                                <td class="fw-bold">${element.name}</td>
                                                <td>${valueHtml}</td>
                                            </tr>`;
                            } else {
                                infoHtml += `<tr>
                                                <td class="fw-bold">${element.name}</td>
                                                <td>
                                                    <a href="${fileDownloadUrl}&fileName=${element.value}" class="btn btn--sm btn-outline--secondary">
                                                        <i class="ti ti-download"></i> @lang('Attachment')
                                                    </a>
                                                </td>
                                            </tr>`;
                            }
                        });

                        infoHtml += `</tbody>
                                    </tbody>`;

                        $('.author-data').html(infoHtml);
                    } else {
                        $('.author-data').empty();
                    }
                });
            })(jQuery);
        </script>
    @endpush