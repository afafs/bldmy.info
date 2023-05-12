@extends('user.layout')
@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ $keywords['CV_Content'] ?? __('CV Content') }}</h4>
        <ul class="breadcrumbs">
            <li class="nav-home">
                <a href="#">
                    <i class="flaticon-home"></i>
                </a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="{{ route('user.cv') }}">{{ $keywords['CV_Management'] ?? __('CV Management') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ $cv->cv_name }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ $keywords['Information'] ?? __('Information') }}</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="card-title d-inline-block" dir="{{ $cv->direction == 2 ? 'rtl' : 'ltr' }}">
                                {!! $cv->contact_title !!}</div>
                            <a class="editbtn btn btn-warning btn-sm ml-4" data-toggle="modal" data-target="#renameModal"
                                title="Rename Contact Section" data-contact_title="{{ $cv->contact_title }}"><i
                                    class="far fa-edit"></i></a>
                        </div>
                        <div class="col-lg-6">
                            <a href="{{ route('user.cv') . '?language=' . request('language') }}"
                                class="btn btn-sm btn-primary float-right">
                                <i class="fas fa-backward"></i>
                                {{ $keywords['Back'] ?? __('Back') }}
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-5 pb-5">
                    <div class="row">
                        <div class="col-lg-12">
                            @if ($cv->direction == 2)
                                <div class="alert alert-info text-dark">
                                    If you want to enter <strong>LTR word / text</strong> in <strong>Content</strong> field,
                                    then wrap that <strong>word / text</strong> with
                                    <strong><code>{{ '<span dir="ltr"></span>' }}</code></strong>
                                    <br>
                                    For example,
                                    <div class="row">
                                        <div class="col-6">
                                            <ul class="pl-3">
                                                <li class="mb-2">
                                                    <input dir="rtl" class="form-control"
                                                        value="{{ '<span dir="ltr">This is LTR Text</span>' }} هناك حقيقة">
                                                </li>
                                                <li class="mb-2">
                                                    <input dir="rtl" class="form-control"
                                                        value="هناك حقيقة {{ '<span dir="ltr">1234567</span>' }}">
                                                </li>
                                                <li>
                                                    <input dir="rtl" class="form-control"
                                                        value="{{ '<span dir="ltr">This is LTR Text</span>' }}">
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            {{-- Featured image upload end --}}
                            <form id="ajaxForm" class="" action="{{ route('user.cv.update.info') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="cv_id" value="{{ $cv->id }}">


                                <div id="app">
                                    {{-- Infromation Start --}}
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label for=""
                                                    class="d-block mb-2">{{ $keywords['Infromation'] ?? __('Infromation') }}</label>
                                                <button class="btn btn-primary"
                                                    @click="addInformation()">{{ $keywords['Add_Information'] ?? __('Add Information') }}</button>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="row no-gutters" v-for="(information, index) in infromations"
                                        :key="information.uniqid">
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <label for="">{{ $keywords['Icon'] ?? __('Icon') }} **</label>
                                                <div class="btn-group d-block">
                                                    <button type="button" class="btn btn-primary iconpicker-component"><i
                                                            :id="'cv-icp-icon' + index"
                                                            :class="information.icon"></i></button>
                                                    <button type="button" :id="'cv-icp' + information.uniqid"
                                                        class="cv-icp cv-icp-dd btn btn-primary dropdown-toggle"
                                                        data-selected="fa-car" data-toggle="dropdown"
                                                        :data-vue_index="index">
                                                    </button>
                                                    <div class="dropdown-menu"></div>
                                                </div>
                                                <input type="hidden" name="icons[]" v-model="information.icon">
                                                <p class="em text-danger mb-0" :id="'erricons.' + index"></p>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <label
                                                    for="">{{ $keywords['Icon_Color'] ?? __('Icon Color') }}</label>
                                                <input name="colors[]"
                                                    :class="'jscolor jscolor' + information.uniqid + ' ltr form-control'"
                                                    :value="information.color" @change="setColor($event, index)"
                                                    type="text">
                                                <p class="em text-danger mb-0" :id="'errcolors.' + index"></p>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label for="">{{ $keywords['Content'] ?? __('Content') }}</label>
                                                <input name="contents[]"
                                                    class="form-control {{ $cv->direction == 2 ? 'rtl' : '' }}"
                                                    v-model="information.content" type="text">
                                                <p class="em text-danger mb-0" :id="'errcontents.' + index"></p>
                                            </div>
                                        </div>
                                        <div class="col-lg-1">
                                            <button class="btn btn-danger text-white mt-4 float-right"
                                                @click="removeInformation(index)">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                    {{-- Infromation End --}}
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="form">
                        <div class="form-group from-show-notify row">
                            <div class="col-12 text-center">
                                <button type="submit" data-form="ajaxForm" id=""
                                    class="submitBtn btn btn-success">{{ $keywords['Update'] ?? __('Update') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Title Modal -->
    <div class="modal fade" id="renameModal" tabindex="-1" role="dialog" aria-labelledby="renameModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">
                        {{ $keywords['Rename_Contact_Section_Title'] ?? __('Rename Contact Section Title') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @if ($cv->direction == 2)
                        <div class="row">
                            <div class="col-12">
                                <div class="alert alert-info text-dark">
                                    If you want to enter <strong>LTR word / text</strong> in <strong>Contact Section
                                        Title</strong> field, then wrap that <strong>word / text</strong> with
                                    <strong><code>{{ '<span dir="ltr"></span>' }}</code></strong>
                                    <br>
                                    For example,
                                    <div class="row">
                                        <div class="col-6">
                                            <ul class="pl-3">
                                                <li class="mb-2">
                                                    <input dir="rtl" class="form-control"
                                                        value="{{ '<span dir="ltr">This is LTR Text</span>' }} هناك حقيقة">
                                                </li>
                                                <li class="mb-2">
                                                    <input dir="rtl" class="form-control"
                                                        value="هناك حقيقة {{ '<span dir="ltr">1234567</span>' }}">
                                                </li>
                                                <li>
                                                    <input dir="rtl" class="form-control"
                                                        value="{{ '<span dir="ltr">This is LTR Text</span>' }}">
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    <form action="{{ route('user.cv.contact_title') }}" id="ajaxEditForm" method="POST">
                        @csrf
                        <input type="hidden" name="cv_id" value="{{ $cv->id }}">
                        <input class="form-control {{ $cv->direction == 2 ? 'rtl' : '' }}" type="text"
                            name="contact_title" value="" id="incontact_title">
                        <p id="eerrcontact_title" class="em text-danger mb-0"></p>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-dismiss="modal">{{ $keywords['Close'] ?? __('Close') }}</button>
                    <button type="button" class="btn btn-primary"
                        id="updateBtn">{{ $keywords['Update'] ?? __('Update') }}</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('vuescripts')
    <script>
        let app = new Vue({
            el: '#app',
            data() {
                return {
                    infromations: []
                }
            },
            methods: {
                setIcon(value, index) {
                    let information = this.infromations[index];
                    information.icon = value;
                    this.$set(this.infromations, index, information);
                },
                setColor($event, index) {
                    let information = this.infromations[index];
                    information.color = $event.target.value;
                    this.$set(this.infromations, index, information);
                },
                addIcpEvent() {
                    let _this = this;
                    this.$nextTick(function() {
                        if ($('.cv-icp').length > 0) {
                            $('.cv-icp').each(function(i) {
                                let index = i;
                                let vIndex = $(this).data('vue_index');
                                $(this).on('iconpickerSelected', function(event) {
                                    let val = $("#cv-icp-icon" + index).attr('class');
                                    $("input[name='icons[]']").eq(index).val(val);
                                    // set the icon in 'informations' array , after icon is changed
                                    _this.setIcon(val, vIndex);
                                });
                            });
                        }
                    })
                },
                uniqid() {
                    let n = Math.floor(Math.random() * 11);
                    let k = Math.floor(Math.random() * 1000000);
                    let m = n + k;
                    return m;
                },
                addInformation() {
                    let _this = this;
                    let uniqid = this.uniqid();
                    this.infromations.push({
                        uniqid: uniqid,
                        icon: 'fas fa-heart',
                        color: '000000',
                        content: ''
                    });

                    // initialize icon picker & color picker for newly added inputs only, also add event to all icon pickers 
                    this.$nextTick(function() {
                        $('#cv-icp' + uniqid).iconpicker();
                        jscolor.installByClassName("jscolor" + uniqid);
                        _this.addIcpEvent();
                    })

                },
                removeInformation(index) {
                    this.infromations.splice(index, 1);
                }
            },
            created() {
                let _this = this;
                $.get("{{ route('user.cv.getinfos', $cv->id) }}", (datas) => {
                    for (let i = 0; i < datas.length; i++) {
                        let obj = datas[i];
                        obj.uniqid = this.uniqid();
                        this.infromations.push(datas[i]);
                    }
                    // initialize icon picker & color picker, also add event to all icon pickers    
                    setTimeout(function() {
                        $('.cv-icp').iconpicker();
                        jscolor.installByClassName("jscolor");
                        _this.addIcpEvent();
                    }, 1000);
                });
            }
        });
    </script>
@endsection
