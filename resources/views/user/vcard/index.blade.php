@extends('user.layout')
@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ $keywords['vCards_Management'] ?? __('vCards Management') }}</h4>
        <ul class="breadcrumbs">
            <li class="nav-home">
                <a href="{{ route('admin.dashboard') }}">
                    <i class="flaticon-home"></i>
                </a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ $keywords['vCards_Management'] ?? __('vCards Management') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ $keywords['vCards'] ?? __('vCards') }}</a>
            </li>
        </ul>
    </div>
    <div class="row">
        @php
            $user = Auth::guard('web')->user();
            $vcard_limit = App\Http\Helpers\LimitCheckerHelper::vcardLimitchecker($user->id);
            $totalVcard = App\Models\User\UserVcard::where('user_id', $user->id)
                ->orderBy('id', 'DESC')
                ->get();
        @endphp
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title d-inline-block">{{ $keywords['vCards'] ?? __('vCards') }}</div>
                    <button class="btn btn-danger float-right btn-sm mr-2 d-none bulk-delete"
                        data-href="{{ route('user.vcard.bulk.delete') }}"><i class="flaticon-interface-5"></i>
                        {{ $keywords['Delete'] ?? __('Delete') }}</button>
                    <a href="{{ route('user.vcard.create') . '?language=' . request('language') }}"
                        class="btn btn-sm btn-primary float-right">{{ $keywords['Add_vCard'] ?? __('Add vCard') }}</a>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning">
                        @if ($totalVcard->count() >= $vcard_limit)
                            {{ $keywords['You added maximum number of vCard in your list'] ?? __('You added maximum number of vCard in your list') }}
                            <br>
                        @endif
                        {{ $keywords['Your_current_package_vCard_limit'] ?? __('Your current package vCard limit') }} :
                        {{ $vcard_limit }} <br>
                        {{ $keywords['Your_total_vCard'] ?? __('Your total vCard') }} : {{ $totalVcard->count() }} <br>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            @if (count($vcards) == 0)
                                <h3 class="text-center">{{ $keywords['NO_VCARD_FOUND'] ?? __('NO VCARD FOUND') }}</h3>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-striped mt-3" id="basic-datatables">
                                        <thead>
                                            <tr>
                                                <th scope="col">
                                                    <input type="checkbox" class="bulk-check" data-val="all">
                                                </th>
                                                <th scope="col">{{ $keywords['vCard_Name'] ?? __('vCard Name') }}</th>
                                                <th scope="col">{{ $keywords['Preview'] ?? __('Preview') }}</th>
                                                <th scope="col">{{ $keywords['Direction'] ?? __('Direction') }}</th>
                                                <th scope="col">{{ $keywords['Actions'] ?? __('Actions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($vcards as $key => $vcard)
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" class="bulk-check"
                                                            data-val="{{ $vcard->id }}">
                                                    </td>
                                                    <td>
                                                        {{ strlen($vcard->vcard_name) > 20 ? mb_substr($vcard->vcard_name, 0, 20, 'UTF-8') . '...' : $vcard->vcard_name }}
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-primary btn-sm" data-toggle="modal"
                                                            data-target="#urlsModal{{ $vcard->id }}"><i
                                                                class="fas fa-link"></i>
                                                            {{ $keywords['URLs'] ?? __('URLs') }}</button>
                                                    </td>
                                                    <td>{{ $vcard->direction == 1 ? $keywords['Left_to_Right'] ?? __('Left to Right') : $keywords['Right_to_Left'] ?? __('Right to Left') }}
                                                    </td>
                                                    <td>
                                                        <div class="dropdown show d-inline-block mr-2">
                                                            <a class="btn btn-secondary btn-sm dropdown-toggle"
                                                                href="#" role="button" id="dropdownMenuLink"
                                                                data-toggle="dropdown" aria-haspopup="true"
                                                                aria-expanded="false">
                                                                {{ $keywords['Manage'] ?? __('Manage') }}
                                                            </a>

                                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                                                <a target="_blank"
                                                                    @if ($totalVcard->count() >= $vcard_limit) class="dropdown-item text-muted" @else class="dropdown-item" href="{{ route('user.vcard.edit', $vcard->id) . '?language=' . request('language') }}" @endif>{{ $keywords['Infromation'] ?? __('Infromation') }}</a>
                                                                <a target="_blank"
                                                                    @if ($totalVcard->count() >= $vcard_limit) class="dropdown-item text-muted" @else class="dropdown-item" href="{{ route('user.vcard.color', $vcard->id) . '?language=' . request('language') }}" @endif>{{ $keywords['Colors'] ?? __('Colors') }}</a>
                                                                <a target="_blank"
                                                                    @if ($totalVcard->count() >= $vcard_limit) class="dropdown-item text-muted" @else class="dropdown-item" href="{{ route('user.vcard.preferences', $vcard->id) . '?language=' . request('language') }}" @endif>{{ $keywords['Preferences'] ?? __('Preferences') }}</a>
                                                                <a target="_blank"
                                                                    @if ($totalVcard->count() >= $vcard_limit) class="dropdown-item text-muted" @else class="dropdown-item" href="{{ route('user.vcard.services', $vcard->id) . '?language=' . request('language') }}" @endif>{{ $keywords['Services'] ?? __('Services') }}</a>
                                                                <a target="_blank"
                                                                    @if ($totalVcard->count() >= $vcard_limit) class="dropdown-item text-muted" @else class="dropdown-item" href="{{ route('user.vcard.projects', $vcard->id) . '?language=' . request('language') }}" @endif>{{ $keywords['Projects'] ?? __('Projects') }}</a>
                                                                <a target="_blank"
                                                                    @if ($totalVcard->count() >= $vcard_limit) class="dropdown-item text-muted" @else class="dropdown-item" href="{{ route('user.vcard.testimonials', $vcard->id) . '?language=' . request('language') }}" @endif>{{ $keywords['Testimonials'] ?? __('Testimonials') }}</a>
                                                                <a target="_blank"
                                                                    @if ($totalVcard->count() >= $vcard_limit) class="dropdown-item text-muted" @else class="dropdown-item" href="{{ route('user.vcard.about', $vcard->id) . '?language=' . request('language') }}" @endif>{{ $keywords['About_and_video'] ?? __('About & Video') }}</a>
                                                                <a target="_blank"
                                                                    @if ($totalVcard->count() >= $vcard_limit) class="dropdown-item text-muted" @else class="dropdown-item" href="{{ route('user.vcard.keywords', $vcard->id) . '?language=' . request('language') }}" @endif>{{ $keywords['Translate_Keywords'] ?? __('Translate Keywords') }}
                                                                </a>
                                                            </div>
                                                        </div>
                                                        <form class="deleteform d-inline-block"
                                                            action="{{ route('user.vcard.delete') }}" method="post">
                                                            @csrf
                                                            <input type="hidden" name="vcard_id"
                                                                value="{{ $vcard->id }}">
                                                            <button type="submit" class="btn btn-danger btn-sm deletebtn"
                                                                data-toggle="tooltip" data-placement="top" title="Delete">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>

                                                <!-- Modal -->
                                                <div class="modal fade" id="urlsModal{{ $vcard->id }}" tabindex="-1"
                                                    role="dialog" aria-labelledby="urlsModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="urlsModalLabel">
                                                                    {{ $keywords['vCard_URLs'] ?? __('vCard URLs') }}</h5>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <ul>
                                                                    <li>
                                                                        @php
                                                                            $pathUrl = env('WEBSITE_HOST') . '/' . Auth::user()->username . '/vcard/' . $vcard->id;
                                                                        @endphp
                                                                        <strong
                                                                            class="mr-2">{{ $keywords['Path_Based_URL'] ?? __('Path Based URL') }}:</strong>
                                                                        <a target="_blank"
                                                                            href="//{{ $pathUrl }}">{{ $pathUrl }}</a>
                                                                    </li>
                                                                    @if (cPackageHasSubdomain(Auth::user()))
                                                                        <li>
                                                                            @php
                                                                                $subUrl = Auth::user()->username . '.' . env('WEBSITE_HOST') . '/vcard/' . $vcard->id;
                                                                            @endphp
                                                                            <strong
                                                                                class="mr-2">{{ $keywords['Subdomain_Based_URL'] ?? __('Subdomain Based URL') }}:</strong>
                                                                            <a target="_blank"
                                                                                href="//{{ $subUrl }}">{{ $subUrl }}</a>
                                                                        </li>
                                                                    @endif
                                                                    @if (cPackageHasCdomain(Auth::user()))
                                                                        @php
                                                                            $domUrl = Auth::user()
                                                                                ->custom_domains()
                                                                                ->where('status', 1)
                                                                                ->orderBy('id', 'DESC')
                                                                                ->first();
                                                                        @endphp
                                                                        @if (!empty($domUrl))
                                                                            <li>
                                                                                <strong
                                                                                    class="mr-2">{{ $keywords['Domain_Based_URL'] ?? __('Domain Based URL') }}:</strong>
                                                                                <a target="_blank"
                                                                                    href="//{{ $domUrl->requested_domain }}/vcard/{{ $vcard->id }}">{{ $domUrl->requested_domain }}/vcard/{{ $vcard->id }}</a>
                                                                            </li>
                                                                        @endif
                                                                    @endif
                                                                </ul>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal">{{ $keywords['Close'] ?? __('Close') }}</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
