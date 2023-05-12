@extends('user.layout')

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ $keywords['Saved_QR_Codes'] ?? __('Saved QR Codes') }}</h4>
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
                <a href="#">{{ $keywords['QR_Codes'] ?? __('QR Codes') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ $keywords['Saved_QR_Codes'] ?? __('Saved QR Codes') }}</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title d-inline-block">{{ $keywords['QR_Codes'] ?? __('QR Codes') }}</div>
                    <button class="btn btn-danger float-right btn-sm mr-2 d-none bulk-delete"
                        data-href="{{ route('user.qrcode.bulk.delete') }}"><i class="flaticon-interface-5"></i>
                        {{ $keywords['Delete'] ?? __('Delete') }}</button>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            @if (count($qrcodes) == 0)
                                <h3 class="text-center">{{ $keywords['NO_QR_CODE_FOUND'] ?? __('NO QR CODE FOUND') }}</h3>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-striped mt-3" id="basic-datatables">
                                        <thead>
                                            <tr>
                                                <th scope="col">
                                                    <input type="checkbox" class="bulk-check" data-val="all">
                                                </th>
                                                <th scope="col">{{ $keywords['Name'] ?? __('Name') }}</th>
                                                <th scope="col">{{ $keywords['URL'] ?? __('URL') }}</th>
                                                <th scope="col">{{ $keywords['Qr_Code'] ?? __('Qr_Code') }} </th>
                                                <th scope="col">{{ $keywords['Actions'] ?? __('Actions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($qrcodes as $key => $qrcode)
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" class="bulk-check"
                                                            data-val="{{ $qrcode->id }}">
                                                    </td>
                                                    <td>
                                                        {{ $qrcode->name }}
                                                    </td>
                                                    <td>
                                                        {{ $qrcode->url }}
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-primary" data-toggle="modal"
                                                            data-target="#qrModal{{ $qrcode->id }}">
                                                            <i class="far fa-eye"></i>
                                                            {{ $keywords['Show'] ?? __('Show') }}
                                                        </button>
                                                    </td>
                                                    <td>
                                                        <a href="{{ asset('assets/front/img/user/qr/' . $qrcode->image) }}"
                                                            download="{{ $qrcode->name }}.png"
                                                            class="btn btn-secondary btn-sm">
                                                            <i
                                                                class="fas fa-download"></i>{{ $keywords['Download'] ?? __('Download') }}
                                                        </a>
                                                        <form class="deleteform d-inline-block"
                                                            action="{{ route('user.qrcode.delete') }}" method="post">
                                                            @csrf
                                                            <input type="hidden" name="qrcode_id"
                                                                value="{{ $qrcode->id }}">
                                                            <button type="submit" class="btn btn-danger btn-sm deletebtn">
                                                                <span class="btn-label">
                                                                    <i class="fas fa-trash"></i>
                                                                </span>
                                                                {{ $keywords['Delete'] ?? __('Delete') }}
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>

                                                <!-- Modal -->
                                                <div class="modal fade" id="qrModal{{ $qrcode->id }}" tabindex="-1"
                                                    role="dialog" aria-labelledby="qrModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="urlsModalLabel">
                                                                    {{ $keywords['QR_Code'] ?? __('QR Code') }}</h5>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body text-center">
                                                                <div class="p-5 bg-white">
                                                                    <img src="{{ asset('assets/front/img/user/qr/' . $qrcode->image) }}"
                                                                        alt="">
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer justify-content-center">
                                                                <a href="{{ asset('assets/front/img/user/qr/' . $qrcode->image) }}"
                                                                    download="{{ $qrcode->name }}.png"
                                                                    class="btn btn-secondary">
                                                                    <i class="fas fa-download"></i>
                                                                    {{ $keywords['Download'] ?? __('Download') }}
                                                                </a>
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
