<div class="pl-lg-1 pt-0">
    <div class="timeline timeline-one timeline-one-tab">
        <article class="timeline-item ">
            <div class="timeline-desk">
                <div class="timeline-box mb-0 mt-0 p-0" style="border-radius: 0">
                    <span class="arrow"></span>
                    <span class="timeline-icon"><i class="mdi mdi-adjust"></i></span>
                    <div class="col-12x">
                        <ul class="nav nav-tabs nav-tabs-highlight nav-tabs-bottom">


                            <?php
                            if (@$table_name === \App\Http\Models\Contract::table_name) {
                                $activeTab = request('tab', 'tabContractQuyTrinhThucHien');

                            } else {
                                $activeTab = request('tab', false);

                            }

                            ?>

                            @if ($table_name ===\App\Http\Models\Contract::table_name)

                                @if(@$extend_id)
                                    <li class="nav-item">
                                        <a href="#tabContractQuyTrinhThucHien" data-toggle="tab"
                                           aria-expanded="true"
                                           class="nav-link {{$activeTab ==='tabContractQuyTrinhThucHien' ? 'active' : ''}}">
                                            Quy trình thực hiện
                                        </a>
                                    </li>
                                @endif
                                <li class="nav-item">
                                    <a href="#tabContractLichSuHopDong" data-toggle="tab" aria-expanded="true"
                                       class="nav-link {{$activeTab ==='tabContractLichSuHopDong' ? 'active' : ''}}">
                                        Lịch sử hợp đồng
                                    </a>
                                </li>
                            @endif
                            @if (isset($options['tabs']) && in_array('chi-tiet-ho-so',$options['tabs']))
                                <?php
                                $activeTab = true;
                                ?>
                                <li class="nav-item">
                                    <a href="#chi-tiet-ho-so" data-toggle="tab" aria-expanded="true"
                                       class="nav-link active">
                                        Chi tiết hồ sơ
                                    </a>
                                </li>
                            @endif
                            <li class="nav-item">
                                <a href="#tabNote" data-toggle="tab" aria-expanded="false"
                                   class="nav-link {{!$activeTab ?'active':''}}">
                                    Ghi chú
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#tabHistory" data-toggle="tab" aria-expanded="true"
                                   class="nav-link {{$activeTab ==='tabHistory' ? 'active' : ''}}">
                                    Lịch sử
                                </a>
                            </li>
                            @if ($table_name ===\App\Http\Models\Staff::table_name)

                                <li class="nav-item">
                                    <a href="#tabAccountWorking" data-toggle="tab" aria-expanded="true"
                                       class="nav-link {{$activeTab ==='tabAccountWorking' ? 'active' : ''}}">
                                        Quá trình công tác
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#tabAccountAccount" data-toggle="tab" aria-expanded="true"
                                       class="nav-link {{$activeTab ==='tabAccountAccount' ? 'active' : ''}}">
                                        Tài khoản
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="#tabAccountFamily" data-toggle="tab" aria-expanded="true"
                                       class="nav-link {{$activeTab ==='tabAccountFamily' ? 'active' : ''}}">
                                        Gia đình
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#tabAccountEdu" data-toggle="tab" aria-expanded="true"
                                       class="nav-link {{$activeTab ==='tabAccountEdu' ? 'active' : ''}}">
                                        Đào tạo
                                    </a>
                                </li>
                                {{--<li class="nav-item">
                                    <a href="#tabAccountChamCong" data-toggle="tab" aria-expanded="true"
                                       class="nav-link {{$activeTab ==='tabAccountChamCong' ? 'active' : ''}}">
                                        Chấm công
                                    </a>
                                </li>--}}
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </article>

    </div>
</div>


<div class="tab-content pt-0">
    @if (isset($options['tabs']) && in_array('chi-tiet-ho-so',$options['tabs']))
        <div class="tab-pane active pl-lg-1 pt-0" id="chi-tiet-ho-so">
            @include('components/right_bar_tab/tab-chi-tiet-ho-so',['disabled'=>false])
        </div>
    @endif

    <div class="tab-pane  pl-lg-1 pt-0 {{!$activeTab?'active':''}}" id="tabNote">
        <div class="col-12x">
            <div class="timeline timeline-one">
                <article class="timeline-item ">
                    <div class="timeline-desk">
                        <div class="timeline-box mt-0" style="border-radius: 0">
                            <form method="post" id="jNoteForm">
                                <input type="hidden" name="object_id" value="{{@$obj['_id']}}"/>
                                <input type="hidden" name="table_name" value="{{$table_name}}"/>
                                <input type="hidden" name="token" value="{{build_token(@$obj['_id'].$table_name)}}"/>
                                <textarea name="description" id="note-description" class="form-control"
                                          style="min-height: 60px" placeholder="Nhập nội dung ghi chú..."></textarea>
                                <div id="documentFileRegion" class="mt-2"></div>
                                <div class="text-right mt-2">
                                    <button id="pickfiles"
                                            {{--onclick="_SHOW_FORM_REMOTE('{!! admin_link('/file/upload-form') !!}')"--}} type="button"
                                            class="btn btn-outline-secondary btn-sm waves-effect waves-light"><i
                                                class="fe-file-plus mr-1"></i> Đính kèm file
                                    </button>
                                    <button onclick="return _SUBMIT_FORM('#jNoteForm','{{admin_link('/public_api/global/save-note')}}')"
                                            type="button" class="btn btn-primary btn-sm waves-effect waves-light"><i
                                                class="fe-save mr-1"></i> Lưu lại
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </article>

            </div>

            <div class="timeline timeline-one">
                @foreach($notes as $key=>$val)
                    <article class="timeline-item ">
                        <div class="timeline-desk">
                            <div class="timeline-box">
                                <span class="arrow"></span>
                                <span class="timeline-icon"><i class="mdi mdi-adjust"></i></span>
                                <h4 class="mt-0 font-14">{{value_show($val['created_by']['name'],$val['created_by']['account'])}}
                                    <span class="text-muted">
                                    <small>{{date_time_show($val['created_at'],'d/m/Y H:i:s')}}</small>
                                </span></h4>
                                <p class="mb-0">
                                    {!! $val['description'] !!}
                                </p>
                                @if(isset($val['files']) && is_array($val['files']) && $val['files'])
                                    <div class="btn-group mt-2">
                                        <button class="btn btn-outline-primary btn-xs dropdown-toggle" type="button"
                                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fe-paperclip"></i> {{count($val['files'])}} đính kèm <i
                                                    class="mdi mdi-chevron-down"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            @foreach($val['files'] as $key=>$f)
                                                @if(isset($f['src']))
                                                    <a target="_blank" class="dropdown-item" title="Xem file"
                                                       href="{!! \App\Http\Models\Media::getFileLink($f['src']) !!}">{{value_show(@$f['name'],$f['src'])}}</a>
                                                @else
                                                    <a target="_blank" class="dropdown-item" title="Xem file"
                                                       href="{!! \App\Http\Models\Media::getFileLink($f) !!}">{{$f}}</a>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
            <!-- end timeline -->
        </div> <!-- end col -->
    </div>
    <div class="tab-pane  pl-lg-1 pt-0 {{$activeTab ==='tabHistory' ? 'active' : ''}}" id="tabHistory">
        <div class="col-12x">
            <div class="timeline timeline-one">

                <article class="timeline-item ">
                    <div class="timeline-desk">
                        <div class="timeline-box mt-0" style="border-radius: 0">
                            <p class="text-muted mb-0">
                                Dưới đây là lịch sử các lần cập nhật dữ liệu
                                <br/>
                                Giúp bạn xem được ai đã cập nhật thông tin? cập nhật vào lúc nào? và cập nhật những gì?
                            </p>
                        </div>
                    </div>
                </article>
            </div>

            <div class="timeline timeline-one scroll4 scroll-colorx">

                @foreach($histories as $key=>$value)
                    <article class="timeline-item ">
                        <div class="timeline-desk">
                            <div class="timeline-box">
                                <span class="arrow"></span>
                                <span class="timeline-icon"><i class="mdi mdi-adjust"></i></span>
                                <h4 class="mt-0 font-14">{{value_show($value['created_by']['name'],$value['created_by']['account'])}}
                                    <span class="text-muted">
                                    <small>{{date_time_show($value['created_at'],'d/m/Y H:i:s')}}</small>
                                </span></h4>

                                <p class="mb-0">
                                    {!! $value['note'] !!}
                                    <br/>

                                <ul>
                                    {{--                                    <b>Một số thông tin lưu ý: </b>--}}
                                    @if(@$value['after']['result'] && @$value['table']=='be_task')
                                        <li class="text-{{@\App\Http\Models\Task::$listResult[@$value['after']['result']]['style']?:"black"}}">
                                            <b>Kết
                                                quả</b>: {{@\App\Http\Models\Task::$listResult[@$value['after']['result']]['label']}}
                                        </li>
                                    @endif
                                    @if(request('stab')==='theo-doi-thi-cong' ||
                                    request('stab') ==='nghiem-thu-cong-viec' ||
                                    request('stab') ==='giam-sat-vat-lieu'
                                    )
                                        @if(isset($value['after']['result']) && @$value['table']=='report')
                                            <li class="text-{{@$value['after']['result'] =='dat'?'success':'danger'}}">
                                                <b>Kết
                                                    quả</b>: {{@$value['after']['result'] =='dat'?'Đạt':'Không đạt'}}
                                            </li>
                                        @endif
                                        @if(isset($value['after']['result']) && @$value['after']['result'] !=='dat' && @$value['table']=='report')
                                            <li class="text-{{@$value['after']['result'] =='dat'?'success':'danger'}}">
                                                <b>Lý do không
                                                    đạt</b>: {{@$value['after']['result'] =='dat'?'Đạt':@$value['after']['cause']?:'...'}}
                                            </li>
                                        @endif
                                    @endif
                                    @if(request('stab')==='giam-sat-thi-nghiem')
                                        @if(isset($value['after']['ket_qua_thi_nghiem']) && @$value['table']=='report')
                                            <li class="text-{{@$value['after']['ket_qua_thi_nghiem'] =='dat'?'success':'danger'}}">
                                                <b>Kết quả thí nghiệm:</b> {{@$value['after']['ket_qua_thi_nghiem'] =='dat'?'Đạt':'Không đạt'}}
                                            </li>
                                        @endif

                                    @endif


                                    @if(isset($value['after']['result']) && @$value['table']=='profile')
                                        <li class="text-{{@$value['after']['result'] =='dat'?'success':'danger'}}">
                                            <b>Kết
                                                quả</b>: {{@$value['after']['result'] =='dat'?'Đạt':'Không đạt'}}
                                        </li>
                                    @endif
                                    @if(isset($value['after']['result']) && @$value['after']['result'] !=='dat' && @$value['table']=='profile')
                                        <li class="text-{{@$value['after']['result'] =='dat'?'success':'danger'}}">
                                            <b>Lý do không
                                                đạt</b>: {{@$value['after']['result'] =='dat'?'Đạt':@$value['after']['cause']?:'...'}}
                                        </li>
                                    @endif
                                </ul>

                                <a href="javascript:void(0)" onclick="showHistoriesDiff('{{$value['_id']}}')"
                                   title="Xem chi tiết dữ liệu thay đổi">
                                    Xem thông tin
                                </a>
                                </p>
                            </div>
                        </div>
                    </article>
                @endforeach

            </div>
        </div>
    </div>

    @if ($table_name ===\App\Http\Models\Staff::table_name)
        @include('components.right_bar_tab.tab-account-family', ['activeTab'=>$activeTab])
        @include('components.right_bar_tab.tab-account-working', ['activeTab'=>$activeTab])
        @include('components.right_bar_tab.tab-account-account', ['activeTab'=>$activeTab])
        @include('components.right_bar_tab.tab-account-project', ['activeTab'=>$activeTab])
        @include('components.right_bar_tab.tab-account-edu', ['activeTab'=>$activeTab])
        {{--@include('components.right_bar_tab.tab-account-cham-cong', ['activeTab'=>$activeTab])--}}

    @endif
    @if ($table_name ===\App\Http\Models\Contract::table_name)
        @if(@$extend_id)
            @include('components.right_bar_tab.tab-contract-quy-trinh', ['activeTab'=>$activeTab])
        @endif
        @include('components.right_bar_tab.tab-contract-lich-su-hop-dong', ['activeTab'=>$activeTab])
    @endif

</div>

<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/jsondiffpatch/dist/jsondiffpatch.umd.min.js"></script>
<script type="text/javascript">
    function showHistoriesDiff(id) {
        return _SHOW_FORM_REMOTE('{!! admin_link('/public_api/global/form-preview-history?id=') !!}' + id)
    }

    function initUploadNotes() {
        _UPLOAD_INIT();
    }


</script>
