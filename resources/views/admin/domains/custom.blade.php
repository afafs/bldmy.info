@extends('admin.layout')
@if (Session::has('admin_lang'))
    @php
        $admin_lang = Session::get('admin_lang');
        $cd = str_replace('admin_', '', $admin_lang);
        $default = \App\Models\Language::where('code', $cd)->first();
    @endphp
@else
    @php
        $default = \App\Models\Language::where('is_default', 1)->first();
    @endphp
@endif
@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ empty(request()->input('type')) ? __('All') : (request()->input('type') == 'pending' ? __('pending') : (request()->input('type') == 'connected' ? __('connected') : __('rejected'))) }}
            {{ __('Custom Domains') }}</h4>
        <ul class="breadcrumbs">
            <li class="nav-home">
                <a href="{{ route('admin.dashboard') . '?language=' . $default->code }}">
                    <i class="flaticon-home"></i>
                </a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('Custom Domains') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ empty(request()->input('type')) ? __('All') : (request()->input('type') == 'pending' ? __('pending') : (request()->input('type') == 'connected' ? __('connected') : __('rejected'))) }}
                    {{ __('Requests') }}</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="card-title d-inline-block">{{ __('All Custom Domains') }}</div>
                        </div>
                        <div class="col-lg-6 offset-lg-2 mt-2 mt-lg-0">
                            <button class="btn btn-danger float-right btn-sm ml-2 d-none bulk-delete"
                                data-href="{{ route('admin.custom-domain.bulk.delete') }}"><i
                                    class="flaticon-interface-5"></i> {{ __('Delete') }}</button>
                            <form action="{{ request()->url() }}" class="float-right d-flex">
                                @if (!empty(request()->input('type')))
                                    <input type="hidden" name="type" value="{{ request()->input('type') }}">
                                @endif
                                <input name="username" style="min-width: 250px;" class="form-control mr-2" type="text"
                                    placeholder="{{ __('Search by Username') }}"
                                    value="{{ request()->input('username') }}">
                                <input name="domain" style="min-width: 250px;" class="form-control" type="text"
                                    placeholder="{{ __('Search by Domain') }}" value="{{ request()->input('domain') }}">
                                <button type="submit" class="d-none"></button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            @if (count($rcDomains) == 0)
                                <h3 class="text-center">{{ __('NO REQUEST FOUND') }}</h3>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-striped mt-3">
                                        <thead>
                                            <tr>
                                                <th scope="col">
                                                    <input type="checkbox" class="bulk-check" data-val="all">
                                                </th>
                                                <th>{{ __('Username') }}</th>
                                                <th scope="col">{{ __('Requested Domain') }}</th>
                                                <th scope="col">{{ __('Current Domain') }}</th>
                                                <th scope="col">{{ __('Status') }}</th>
                                                <th>{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($rcDomains as $rcDomain)
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" class="bulk-check"
                                                            data-val="{{ $rcDomain->id }}">
                                                    </td>
                                                    @if (!empty($rcDomain->user))
                                                        <td><a href="{{ route('register.user.view', $rcDomain->user->id) }}"
                                                                target="_blank">{{ $rcDomain->user->username }}</a></td>
                                                    @else
                                                        <td>-</td>
                                                    @endif
                                                    <td>
                                                        @if (!empty($rcDomain->requested_domain))
                                                            <a href="//{{ $rcDomain->requested_domain }}"
                                                                target="_blank">{{ $rcDomain->requested_domain }}</a>
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if (!empty($rcDomain->current_domain))
                                                            <a href="//{{ $rcDomain->current_domain }}"
                                                                target="_blank">{{ $rcDomain->current_domain }}</a>
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <form id="statusForm{{ $rcDomain->id }}"
                                                            action="{{ route('admin.custom-domain.status') }}"
                                                            method="POST">
                                                            @csrf
                                                            <input type="hidden" name="domain_id"
                                                                value="{{ $rcDomain->id }}">
                                                            <select style="max-width: 130px;"
                                                                class="form-control form-control-sm
                                                    @if ($rcDomain->status == 0) bg-warning text-white
                                                    @elseif($rcDomain->status == 1)
                                                    bg-success text-white
                                                    @elseif($rcDomain->status == 2)
                                                    bg-danger text-white
                                                    @elseif($rcDomain->status == 3)
                                                    bg-dark text-white @endif
                                                    "
                                                                name="status"
                                                                onchange="document.getElementById('statusForm{{ $rcDomain->id }}').submit();">
                                                                <option value="0"
                                                                    {{ $rcDomain->status == 0 ? 'selected' : '' }}>
                                                                    {{ __('Pending') }}
                                                                </option>
                                                                <option value="1"
                                                                    {{ $rcDomain->status == 1 ? 'selected' : '' }}>
                                                                    {{ __('Connected') }}</option>
                                                                <option value="2"
                                                                    {{ $rcDomain->status == 2 ? 'selected' : '' }}>
                                                                    {{ __('Rejected') }}
                                                                </option>
                                                                <option value="3"
                                                                    {{ $rcDomain->status == 3 ? 'selected' : '' }}>
                                                                    {{ __('Removed') }}
                                                                </option>
                                                            </select>
                                                        </form>
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-secondary btn-sm editbtn" data-toggle="modal"
                                                            data-target="#mailModal"
                                                            data-email="{{ !empty($rcDomain->user) ? $rcDomain->user->email : '' }}">{{ __('Mail') }}</button>
                                                        <form class="d-inline-block deleteform"
                                                            action="{{ route('admin.custom-domain.delete') }}"
                                                            method="post">
                                                            @csrf
                                                            <input type="hidden" name="domain_id"
                                                                value="{{ $rcDomain->id }}">
                                                            <button type="submit" class="btn btn-danger btn-sm deletebtn">
                                                                <span class="btn-label">
                                                                    <i class="fas fa-trash"></i>
                                                                </span>
                                                                {{ __('Delete') }}
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    {{ $rcDomains->appends(['type' => request()->input('type'), 'username' => request()->input('username'), 'domain' => request()->input('domain')])->links() }}
                </div>


                <!-- Send Mail Modal -->
                <div class="modal fade" id="mailModal" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Send Mail') }}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form id="ajaxEditForm" class="" action="{{ route('admin.custom-domain.mail') }}"
                                    method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label for="">{{ __('Email Address') }} **</label>
                                        <input id="inemail" type="text" class="form-control" name="email"
                                            value="" placeholder="{{ __('Enter email') }}">
                                        <p id="eerremail" class="mb-0 text-danger em"></p>
                                    </div>
                                    <div class="form-group">
                                        <label for="">{{ __('Subject') }} **</label>
                                        <input id="insubject" type="text" class="form-control" name="subject"
                                            value="" placeholder="{{ __('Enter subject') }}">
                                        <p id="eerrsubject" class="mb-0 text-danger em"></p>
                                    </div>
                                    <div class="form-group">
                                        <label for="">{{ __('Message') }} **</label>
                                        <textarea id="inmessage" class="form-control summernote" name="message" placeholder="{{ __('Enter message') }}"
                                            data-height="150"></textarea>
                                        <p id="eerrmessage" class="mb-0 text-danger em"></p>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                    data-dismiss="modal">{{ __('Close') }}</button>
                                <button id="updateBtn" type="button"
                                    class="btn btn-primary">{{ __('Send Mail') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
