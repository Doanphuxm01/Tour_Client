<div class="modal-dialog  modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header bg-teal">
            <h5 class="modal-title">Xem thông tin văn bản</h5>
            <button type="button" onclick='_CLOSE_MODAL()' class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form method="post" id="mainFormInput">
                <input type="hidden" name="_token" value="{{csrf_token()}}"/>
                <input type="hidden" name="id" value="{{@$obj['_id']}}"/>
                <div class="card">
                    <div class="card-body">
                        <h4 class="mb-3 header-title">
                            @if($preview)
                                Thông tin về văn bản
                            @else
                                Cập nhật thông tin văn bản
                            @endif
                        </h4>
                        @if(is_deleted(@$obj,false))
                            <div class="row mt-2">
                                <div class="col-12">
                                    <div class="alert alert-danger " role="alert">
                                        <i class="mdi mdi-alert-outline mr-2"></i> Bản ghi này đã <b>bị xóa!</b>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if($isNew)
                            <div id="showLastestDocumentCodeRegion" style="display: none">
                                <div class="row mt-2">
                                    <div class="col-12">
                                        <div class="alert alert-info " role="alert">
                                            <i class="mdi mdi-alert-outline mr-2"></i>
                                            <span id="showLastestDocumentString"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="form-row">
                            @if(!$isNew)
                                @php ($field = \App\Http\Models\Document::$BeaconDocumentField['code'])
                                <div class="col-md-12">
                                    @php ($field['name'] = 'obj['.$field['key'].']')
                                    @php ($field['value'] = @$obj[$field['key']])
                                    @include('forms/input-base',['field'=>$field])
                                </div>
                            @endif
                            @foreach(\App\Http\Models\Document::$BeaconDocumentField as $field)
                                @if($field['key'] !=='code' &&$field['key'] !=='nguoi_duyet_ngoai_beacon' && $field['key'] !=='nguoi_lap_ngoai_beacon')
                                    @if($field['key'] =='nguoi_duyet' )

                                        @php ($field['name'] = 'obj['.$field['key'].']')
                                        @php ($field['value'] = @$obj[$field['key']])
                                        <div class="col-md-12" id="nguoi-duyet-trong-beacon">

                                            @include('forms/input-base',['field'=>$field])
                                        </div>
                                        <div class="col-md-12" id="nguoi-duyet-ngoai-beacon" style="display:none">
                                            <div class="form-group ">
                                                <label>Người duyệt</label>
                                                <input name="obj[nguoi_duyet_ngoai_beacon]" type="text" class="form-control"
                                                       id="obj-nguoi_duyet_ngoai_beacon"
                                                       value="{{@$obj['nguoi_duyet_ngoai_beacon']}}" placeholder="">

                                            </div>
                                        </div>
                                    @elseif($field['key'] =='nguoi_lap' )

                                        @php ($field['name'] = 'obj['.$field['key'].']')
                                        @php ($field['value'] = @$obj[$field['key']])
                                        <div class="col-md-12" id="nguoi-lap-trong-beacon">

                                            @include('forms/input-base',['field'=>$field])
                                        </div>
                                        <div class="col-md-12" id="nguoi-lap-ngoai-beacon" style="display:none">
                                            <div class="form-group ">
                                                <label>Người lập</label>
                                                <input name="obj[nguoi_lap_ngoai_beacon]" type="text" class="form-control"
                                                       id="obj-nguoi_lap_ngoai_beacon"
                                                       value="{{@$obj['nguoi_lap_ngoai_beacon']}}" placeholder="">

                                            </div>
                                        </div>
                                    @else
                                        @php ($field['name'] = 'obj['.$field['key'].']')
                                        @php ($field['value'] = @$obj[$field['key']])
                                        <div class="col-md-12" >

                                            @include('forms/input-base',['field'=>$field])
                                        </div>
                                    @endif
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>  <!-- end card -->


                <div class="card">
                    <div class="card-body">
                        <h4 class="mb-3 header-title">File và tài liệu liên quan</h4>
                        @include('forms/input-group-files')

                    </div>
                </div><!-- end card -->
                <div class="bottom-control">
                    <div class="container-fluid control-item">
                        @if(@$obj['_id'])
                            @if(is_deleted($obj,false))
                                <a href="javascript:void(0)"
                                   onclick="return _revertTask('{!! admin_link('/document/_delete?id='.$obj['id'].'&revert=true&token='.build_token($obj['id'])) !!}')"
                                   class="btn btn-warning waves-effect btn-xs mr-3"><i class="fe-refresh-cw"></i> Khôi phục</a>
                            @else
                                <a href="javascript:void(0)"
                                   onclick="return _removeDocument('{!! admin_link('/document/_delete?id='.$obj['id'].'&token='.build_token($obj['id'])) !!}')"
                                   class="btn btn-danger waves-effect btn-xs mr-3"><i class="fe-delete"></i> Xóa</a>
                            @endif
                        @endif

                        @if($view=='popup')
                            <a onclick="_CLOSE_MODAL()" class="btn btn-light waves-effect btn-xs mr-3">Bỏ qua</a>

                            @if( @$obj['_id'])
                                <a href="{!! admin_link('/document/input?id='.$obj['_id'].'&preview=1') !!}"
                                   class="btn btn-light waves-effect mr-3 btn-xs"><i class="fe-eye"></i> Xem chi tiết </a>
                            @endif

                        @else
                            <a href="{!! admin_link('/document') !!}" class="btn btn-light waves-effect btn-xs mr-3">Bỏ qua</a>
                        @endif

                        {{--            @if( @$obj['_id'])--}}
                        {{--                <a title="Xem bản in" target="_blank"--}}
                        {{--                   href="{!! admin_link('/document/input?id='.$obj['_id']).'&output=print' !!}"--}}
                        {{--                   class="btn btn-light waves-effect mr-3 btn-xs"><i class="mdi mdi-printer"></i> Xem bản in</a>--}}
                        {{--            @endif--}}

                        @if($preview && @$obj['_id'])
                            <a href="{!! admin_link('/document/input?id='.$obj['_id']) !!}"
                               class="btn btn-warning waves-effect mr-3"><i class="fe-edit"></i> Sửa thông tin</a>
                        @else
                            @if( @$obj['_id'])
                                <a href="{!! admin_link('/document/input?id='.$obj['_id'].'&preview=1') !!}"
                                   class="btn btn-light waves-effect mr-3 btn-xs"><i class="fe-eye"></i> Xem chi tiết </a>
                            @endif

                            <a href="javascript:void(0)" onclick="return _submit_document()"
                               class="btn btn-primary waves-effect waves-light mr-3"><i class="fe-save"></i> Cập nhật</a>
                        @endif

                        @include('views.include._approve_report_button')


                    </div>
                </div>
            </form>
            @push('JS_REGION')
                <script type="text/javascript">
                    _SAVE_HISTORY({
                        id: '{{@$obj['_id']}}',
                        name: '{{@$obj['name']}}',
                        link: '{!! admin_link('/document/input?id='.@$obj['_id'].'&preview=true&view=popup') !!}',
                        popup: true,
                        object_name: 'Thông tin văn bản',
                        time: '{{date('H:i:s d/m/Y')}}'
                    })
                </script>
                <script type="text/javascript">
                    $(document).ready(function () {
                        jQuery('[name="obj[type][id]"]').change(function (e) {
                            getLastestDocument(e.target)
                        });
                    });

                    function _submitForm() {
                        return _POST_FORM('#mainFormInput', '{!! admin_link('/document/_save') !!}')
                    }

                    function _submit_document() {
                        let options = {}
                        let {callback, type = 'json'} = options;
                        if (typeof callback !== 'function') {

                        }
                        let url = '{!! admin_link('/document/_save') !!}';
                        let data = $('#mainFormInput').serializeArray();
                        let _token = jQuery('meta[name=_token]').attr("content");
                        if (_token) {
                            let _data = {'name': '_token', 'value': _token};
                            data.push(_data);
                        }
                        jQuery.ajax({
                            url: url,
                            type: "POST",
                            data: data,
                            dataType: type,
                            success: function (res) {
                                return eval(__callback(res));
                            },
                            error: function (xhr, ajaxOptions, thrownError) {
                                alert(thrownError);
                            }
                        });
                        return false;

                    }

                    function getLastestDocument(obj) {
                        jQuery('#showLastestDocumentCodeRegion').hide();
                        var type = jQuery(obj).val();
                        if (!type) {
                            return false;
                        }
                        var link = '{!! admin_link('document/get_lastest_document?type=') !!}' + type;
                        _GET_URL(link, {
                            callback: function (json) {

                                console.log(json)
                                if (typeof json.data !== undefined) {
                                    if (typeof json.data.link !== "undefined") {
                                        jQuery('#showLastestDocumentCodeRegion').show();
                                        let html = 'Văn bản gần nhất có mã <a href="javascript:void (0)" onclick="_SHOW_FORM_REMOTE(\'' + json.data.link + '\')"><b>' + json.data.code + '</b></a>';
                                        jQuery('#showLastestDocumentString').html(html);
                                    }
                                }
                            }
                        })
                    }
                </script>
                <script>


                    $('select[name="obj[type][id]"]').on('change' , function (){
                        let $this = $(this);
                        if($this.val() ==='5d27ec37d6a2d907da42c8c8'){
                            $('#nguoi-duyet-ngoai-beacon').show();
                            $('#nguoi-duyet-trong-beacon').hide();

                        }else{
                            $('#nguoi-duyet-ngoai-beacon').hide();
                            $('#nguoi-duyet-trong-beacon').show();
                        }
                        if($this.val() ==='5d27ec37d6a2d907da42c8c8'){
                            $('#nguoi-lap-ngoai-beacon').show();
                            $('#nguoi-lap-trong-beacon').hide();

                        }else{
                            $('#nguoi-lap-ngoai-beacon').hide();
                            $('#nguoi-lap-trong-beacon').show();
                        }
                    }).change()


                </script>
                {{--{{\App\Elibs\Debug::show($obj->toArray())}}--}}
            @endpush
            <script>
                function _removeDocument(link) {
                    Swal.fire(
                        {
                            title: "Bạn có chắc chắn muốn xóa bản ghi này?",
                            text: "Lưu ý: dữ liệu bị xóa sẽ không thể phục hồi lại được!",
                            type: "warning",
                            showCancelButton: !0,
                            confirmButtonColor: "#3085d6",
                            cancelButtonColor: "#d33",
                            confirmButtonClass: "btn btn-success mt-2 btn-sm",
                            cancelButtonClass: "btn btn-danger ml-2 mt-2 btn-sm",
                            buttonsStyling: !1,
                            confirmButtonText: "Vâng, Tôi muốn xóa!"
                        }).then(function (t) {
                        if (t.value) {
                            return _GET_URL(link);
                        }
                    });
                }

            </script>
        </div>
    </div>
</div>