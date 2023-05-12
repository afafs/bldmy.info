@extends('user.layout')
@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ $keywords['CV_Section_Content'] ?? __('CV Section Content') }}</h4>
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
                <a href="{{ route('user.cv') }}">{{ $keywords['CV_Management'] ?? __('CVs Management') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ $section->user_cv->cv_name }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a
                    href="{{ route('user.cv.section.index', $section->user_cv_id) }}">{{ $keywords['Sections'] ?? __('Sections') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#" dir="{{ $section->user_cv->direction == 2 ? 'rtl' : 'ltr' }}">{!! $section->name !!}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ $keywords['Content'] ?? __('Content') }}</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="card-title d-inline-block">
                                {{ $keywords['Section_Content'] ?? __('Section Content') }}</div>
                        </div>
                        <div class="col-lg-6">
                            <a href="{{ route('user.cv.section.index', $section->user_cv_id) . '?language=' . request('language') }}"
                                class="btn btn-sm btn-primary float-right"><i class="fas fa-backward"></i>
                                {{ $keywords['Back'] ?? __('Back') }}</a>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-5 pb-5">
                    <div class="row">
                        @if ($section->user_cv->direction == 2)
                            <div class="col-12">
                                <div class="alert alert-info text-dark">
                                    If you want to enter <strong>LTR word / text</strong> in <strong>Duration, Title,
                                        Subtitle, Description</strong> field, then wrap that <strong>word / text</strong>
                                    with <strong><code>{{ '<span dir="ltr"></span>' }}</code></strong>
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
                        @endif
                        <div class="col-lg-12">

                            {{-- Featured image upload end --}}
                            <form id="ajaxForm" class="" action="{{ route('user.cv.section.update.content') }}"
                                method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="section_id" value="{{ $section->id }}">


                                <div id="app">
                                    {{-- content Start --}}
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label for=""
                                                    class="d-block mb-2">{{ $keywords['Content'] ?? __('Content') }}</label>
                                                <button class="btn btn-primary"
                                                    @click="addContent">{{ $keywords['Add_Content'] ?? __('Add Content') }}</button>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="row no-gutters 
                        @if (request()->cookie('user-theme') == 'dark') bg-dark 
                        @elseif(request()->cookie('user-theme') == 'light')
                        bg-light @endif
                        mt-4 pt-2 pb-4"
                                        v-for="(content, index) in contents" :key="content.uniqid">
                                        <div class="col-lg-2">
                                            <div class="form-group">
                                                <label for="">{{ $keywords['Left_Border'] ?? __('Left Border') }}
                                                    **</label>
                                                <select class="form-control" name="left_borders[]"
                                                    v-model="content.left_border">
                                                    <option value="0">{{ $keywords['Disable'] ?? __('Disable') }}
                                                    </option>
                                                    <option value="1">{{ $keywords['Enable'] ?? __('Enable') }}
                                                    </option>
                                                </select>
                                                <p class="em text-danger mb-0" :id="'errleft_borders.' + index"></p>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label for="">{{ $keywords['Duration'] ?? __('Duration') }}
                                                    ({{ $keywords['Optional'] ?? __('Optional') }})</label>
                                                <input name="durations[]"
                                                    class="form-control {{ $section->user_cv->direction == 2 ? 'rtl' : '' }}"
                                                    v-model="content.duration" type="text">
                                                <p class="text-warning mb-0">**
                                                    {{ $keywords['Cv_section_Duration_text'] ?? __('Here, You can enter durations like this - (1st October, 2021 >> Present)') }}
                                                </p>
                                                <p class="em text-danger mb-0" :id="'errdurations.' + index"></p>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <label for="">{{ $keywords['Title'] ?? __('Title') }}
                                                    ({{ $keywords['Optional'] ?? __('Optional') }})</label>
                                                <input name="titles[]"
                                                    class="form-control {{ $section->user_cv->direction == 2 ? 'rtl' : '' }}"
                                                    v-model="content.title" type="text">
                                                <p class="em text-danger mb-0" :id="'errtitles.' + index"></p>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <label for="">{{ $keywords['Subtitle'] ?? __('Subtitle') }}
                                                    ({{ $keywords['Optional'] ?? __('Optional') }})</label>
                                                <input name="subtitles[]"
                                                    class="form-control {{ $section->user_cv->direction == 2 ? 'rtl' : '' }}"
                                                    v-model="content.subtitle" type="text">
                                                <p class="em text-danger mb-0" :id="'errsubtitles.' + index"></p>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label for="">{{ $keywords['Description'] ?? __('Description') }}
                                                    ({{ $keywords['Optional'] ?? __('Optional') }})</label>
                                                <textarea class="form-control {{ $section->user_cv->direction == 2 ? 'rtl' : '' }}" name="descriptions[]"
                                                    id="" cols="30" rows="3" v-model="content.description"></textarea>
                                                <p class="em text-danger mb-0" :id="'errdescriptions.' + index"></p>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <button class="btn btn-sm btn-primary"
                                                    @click="addSpacing(index)">{{ $keywords['Add_Sapcing'] ?? __('Add Sapcing') }}</button>
                                            </div>
                                        </div>
                                        <input type="hidden" name="newLines[]" v-model="content.newLines">
                                        <div class="w-100" v-if="content.newLines > 0"
                                            v-for="n in parseInt(content.newLines)">
                                            <div style="width: 95%; float: left;">
                                                <pre class="mb-0 p-0">
                                    </pre>
                                            </div>
                                            <div>
                                                <button class="btn btn-danger btn-sm" @click="removeSpacing(index)"><i
                                                        class="fas fa-times"></i></button>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 text-center">
                                            <button class="btn btn-danger text-white mt-4" @click="removeContent(index)">
                                                <i
                                                    class="fas fa-times"></i>{{ $keywords['Remove_Content'] ?? __('Remove Content') }}
                                            </button>
                                        </div>
                                    </div>
                                    {{-- content End --}}
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
@endsection

@section('vuescripts')
    <script>
        let app = new Vue({
            el: '#app',
            data() {
                return {
                    contents: []
                }
            },
            methods: {
                addContent() {
                    let n = Math.floor(Math.random() * 11);
                    let k = Math.floor(Math.random() * 1000000);
                    let m = n + k;
                    this.contents.push({
                        uniqid: m,
                        left_border: 0,
                        duration: '',
                        title: '',
                        subtitle: '',
                        description: '',
                        newLines: 0
                    });
                },
                removeContent(index) {
                    this.contents.splice(index, 1);
                },
                addSpacing(index) {
                    this.contents[index].newLines++;
                },
                removeSpacing(index) {
                    this.contents[index].newLines--;
                }
            },
            mounted() {
                this.$nextTick(function() {
                    $.get("{{ route('user.cv.section.getcontents', $section->id) }}", (datas) => {
                        for (let i = 0; i < datas.length; i++) {
                            this.contents.push(datas[i]);
                        }
                    });
                })
            },
            created() {

            }
        });
    </script>
@endsection
