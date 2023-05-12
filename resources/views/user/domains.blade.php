@extends('user.layout')

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ $keywords['Custom_Domain'] ?? __('Custom Domain') }}</h4>
        <ul class="breadcrumbs">
            <li class="nav-home">
                <a href="{{ route('user-dashboard') }}">
                    <i class="flaticon-home"></i>
                </a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ $keywords['Domains_and_URLs'] ?? __('Domains & URLs') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ $keywords['Custom_Domain'] ?? __('Custom Domain') }}</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <!-- Custom Domain Request Modal -->
            <div class="modal fade" id="customDomainModal" tabindex="-1" role="dialog"
                aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">
                                {{ $keywords['Request_Custom_Domain'] ?? __('Request Custom Domain') }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            @if (cPackageHasCdomain(Auth::user()))
                                @if (Auth::user()->custom_domains()->where('status', 1)->count() > 0)
                                    <div class="alert alert-warning">
                                        {{ $keywords['You_already_have_a_custom_domain'] ?? __('You already have a custom domain') }}
                                        (<a target="_blank"
                                            href="//{{ getCdomain(Auth::user()) }}">{{ getCdomain(Auth::user()) }}</a>)
                                        {{ $keywords['connected_with_your_portfolio_website'] ?? __('connected with your portfolio website.') }}
                                        <br>
                                        {{ $keywords['domain_connection_warning_message'] ?? __('if you request another domain now & if it gets connected with our server, then  your current domain') }}
                                        (<a target="_blank"
                                            href="//{{ getCdomain(Auth::user()) }}">{{ getCdomain(Auth::user()) }}</a>)
                                        {{ $keywords['will_be_removed'] ?? __('will be removed.') }}
                                    </div>
                                @endif
                            @endif
                            <form action="{{ route('user-domain-request') }}" id="customDomainRequestForm" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="">{{ $keywords['Custom_Domain'] ?? __('Custom Domain') }}</label>
                                    <input type="text" class="form-control" name="custom_domain"
                                        placeholder="{{ $keywords['enter_url'] ?? __('enter url') }}" required>
                                    <p class="text-secondary mb-0"><i class="fas fa-exclamation-circle"></i>
                                        {{ $keywords['Do_not_use'] ?? __('Do not use') }}
                                        <strong class="text-danger">http://</strong> {{ $keywords['or'] ?? __('or') }}
                                        <strong class="text-danger">https://</strong>
                                    </p>
                                    <p class="text-secondary mb-0"><i class="fas fa-exclamation-circle"></i>
                                        {{ $keywords['The_valid_format_will_be_exactly_like_this_one'] ?? __('The valid format will be exactly like this one') }}
                                        - <strong class="text-danger">domain.tld, www.domain.tld</strong>
                                        {{ $keywords['or'] ?? __('or') }}
                                        <strong class="text-danger">subdomain.domain.tld,
                                            www.subdomain.domain.tld</strong></strong>
                                    </p>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                data-dismiss="modal">{{ $keywords['Close'] ?? __('Close') }}</button>
                            <button type="submit" class="btn btn-primary" form="customDomainRequestForm">
                                {{ $keywords['Send_Request'] ?? __('Send Request') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            @if (session()->has('domain-success'))
                <div class="alert alert-success bg-success text-white">
                    <p class="mb-0">{!! nl2br(session()->get('domain-success')) !!}</p>
                </div>
            @endif

            @if ($errors->has('custom_domain'))
                <div class="alert alert-danger bg-danger text-white">
                    <p class="mb-0">{!! $errors->first('custom_domain') !!} </p>
                </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="card-title d-inline-block">{{ $keywords['Custom_Domain'] ?? __('Custom Domain') }}
                            </div>
                        </div>
                        <div class="offset-lg-4 col-lg-4 text-right">
                            @if (empty($rcDomain) || $rcDomain->status != 0)
                                <button class="btn btn-primary" data-toggle="modal" data-target="#customDomainModal">
                                    {{ $keywords['Request_Custom_Domain'] ?? __('Request Custom Domain') }}
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            @if (empty($rcDomain))
                                <h3 class="text-center">
                                    {{ $keywords['Requested_custom_domain_not_available'] ?? __('REQUESTED / CONNECTED CUSTOM DOMAIN NOT AVAILABLE') }}
                                </h3>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-striped mt-3">
                                        <thead>
                                            <tr>
                                                <th scope="col">
                                                    {{ $keywords['Requested_Domain'] ?? __('Requested Domain') }}</th>
                                                <th scope="col">
                                                    {{ $keywords['Current_Domain'] ?? __('Current Domain') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    @if ($rcDomain->status == 0)
                                                        <a href="//{{ $rcDomain->requested_domain }}"
                                                            target="_blank">{{ $rcDomain->requested_domain }}</a>
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>
                                                    @if (getCdomain(Auth::user()))
                                                        @php
                                                            $cdomain = getCdomain(Auth::user());
                                                        @endphp
                                                        <a target="_blank"
                                                            href="//{{ $cdomain }}">{{ $cdomain ?? '-' }}</a>
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0"><strong>{{ $be->cname_record_section_title }}</strong></h4>
                </div>
                <div class="card-body">
                    {!! $be->cname_record_section_text !!}
                </div>
            </div>
        </div>
    </div>
@endsection
