@php
    $grFieldLink = ['account', '_id'];
    $lsFormatDate = ['created_at', 'actived_at', 'updated_at', 'ngay_khoi_hanh', 'ngay_ket_thuc', 'thoi_gian_tap_trung'];
    $lsStatus = ['status', 'type', 'type_giaodich', 'type_vi', 'level_doanhthu'];
    $lsFormatMoney = ['so_diem_duoc_nhan', 'so_diem_giao_dich', 'so_diem_can_mua', 'so_tien_giao_dich', 'so_du_cuoi', 'so_tien_muon_rut', 'phi_giao_dich', 'diem_da_nhan'];
    $lsFormatPercent = ['percent_level'];
    if(!isset($lsStatusRegister)) {
        $lsStatusRegister = \App\Http\Models\Orders::getListStatus();
    }
@endphp
<table class="table datatable-responsive">
    <thead>
    <tr>
        <th>STT</th>
        {{--<th>
            <div class="custom-control custom-checkbox ml-1">
                <input type="checkbox" class="custom-control-input" id="checkAll">
                <label class="custom-control-label" for="checkAll">&nbsp;</label>
            </div>
        </th>--}}
        @foreach($lsTh as $k => $th)
            <th class="text-truncate {{ @$th['class'] }}"
                @if(@$th['style']) style="{{ $th['style'] }}" @endif>{{ \App\Elibs\Helper::showContent(@$th['name']) }}</th>
        @endforeach
        @if(isset($action['hide']) && $action['hide'] !== true)
            <th class="text-right" style="width: 150px;">CHỨC NĂNG</th>
        @endif
    </tr>
    </thead>
    <tbody>
    @foreach($lsObj as $k => $obj)

        <tr>
            <td>{{$k+1}}</td>
            {{--<td>
                @if(@$obj['status'] != 'done')
                    <div class="custom-control custom-checkbox ml-1">
                        <input type="checkbox" name="checkbox-item-input[]" value="{{$obj['_id']}}#{{build_token(strval($obj['_id']))}}" class="custom-control-input item-check" id="customCheck{{$k}}">
                        <label class="custom-control-label" for="customCheck{{$k}}">&nbsp;</label>
                    </div>
                @endif
            </td>--}}
            @foreach($lsTh as $th)
{{--                {{ dd( $th,$obj) }}--}}
                <td class="{{ @$th['td']['class'] }}">
                    @if(@$th['td']['link']) <a href="{{ $th['td']['link'].(in_array(@$th['key'], $grFieldLink) ? @$obj[$th['key']] : (in_array(@$th['ksub'], $grFieldLink) ? @$obj[$th['key']][$th['ksub']] : $obj['_id'])) }}">
                    @endif
                        @if(is_array(@$th['key']))
                            @foreach($th['key'] as $k)

                                @if(in_array($k, $lsFormatMoney))
                                    @if(isset($obj[$k]))
                                        <span>{{ \App\Elibs\Helper::formatMoney($obj[$k]) }}</span>
                                        @break;
                                    @endif
                                @elseif(in_array($k, $lsFormatPercent))
                                    @if(isset($obj[$k]))
                                        <span>{{ \App\Elibs\Helper::formatPercent($obj[$k], true) }}</span>
                                        @break;
                                    @endif
                                @else
                                    @if(isset($obj[$k]))
                                        {{ \App\Elibs\Helper::showContent($obj[$k]) }}
                                        @break;
                                    @endif
                                @endif
                            @endforeach
                        @elseif(in_array(@$th['key'], $lsFormatDate))
                            {{ \App\Elibs\Helper::showMongoDate(@$obj[$th['key']], 'd/m/Y H:i:s') }}
                        @elseif(in_array(@$th['key'], $lsStatus))
                            <span id="status-{{ $obj['_id'] }}" class="badge badge-{{ @$lsStatusRegister[@$obj[@$th['key']]]['style'] }}">{{ @$lsStatusRegister[@$obj[@$th['key']]]['text'] }}</span>
                            @if(@$lsStatusRegister[@$obj['kichhoatbycode']])
                                <span id="status-{{ $obj['_id'] }}-kichhoatbycode" class="badge badge-{{ @$lsStatusRegister[@$obj['kichhoatbycode']]['style'] }}">{{ @$lsStatusRegister[@$obj['kichhoatbycode']]['text'] }}</span>
                            @endif
                        @elseif(in_array(@$th['key'], $lsFormatMoney))
                            <span>{{ \App\Elibs\Helper::formatMoney(@$obj[$th['key']]) }}</span>
                        @elseif(in_array(@$th['key'], $lsFormatPercent))
                            <span>{{ \App\Elibs\Helper::formatPercent(@$obj[$th['key']], true) }}</span>
                        @else
                            @if(is_array(@$obj[@$th['key']]))
                                @if(in_array(@$th['ksub'], $lsStatus))
                                    <span id="status-{{ $obj['_id'] }}" class="badge badge-{{ @$lsStatusRegister[@$obj[$th['key']][$th['ksub']]]['style'] }}">{{ @$lsStatusRegister[@$obj[$th['key']][$th['ksub']]]['text'] }}</span>
                                @else
                                {{ \App\Elibs\Helper::showContent(@$obj[$th['key']][$th['ksub']]) }}
                                @endif
                            @else
                                {{ \App\Elibs\Helper::showContent(@$obj[$th['key']]) }}
                            @endif
                        @endif
                        @if(@$th['td']['link']) </a>@endif
                </td>
            @endforeach
            @if(isset($action['hide']) && $action['hide'] !== true)
                <td class="text-right">
                    <ul class="row mb-0 justify-content-start">
                        <li class="text-primary-600">
                            <a class="badge badge-flat font-size-sm badge-icon cursor-pointer" @if(@$action['_SHOW_FORM_REMOTE']) onclick="_SHOW_FORM_REMOTE('{!! @$action['edit']['link'].@$obj['_id'].'&preview=true&view=popup' !!}')"
                               @else href="{{@$action['edit']['link'] ? @$action['edit']['link'].@$obj['_id'] : admin_link('news/input?id='.@$obj['_id'])}}"  @endif title="Xem chi tiết"><i class="icon-pencil7"></i>
                            </a>
                        </li>
                    </ul>

                    @if(!empty(@$obj['files']) && is_array(@$obj['files']))
                        @if(count(@$obj['files'])>1)
                            <div class="btn-group">
                                <a href="#" class="label bg-teal-400 dropdown-toggle"
                                   data-toggle="dropdown">{{count(@$obj->files)}} File đính kèm<span
                                            class="caret"> </span></a>

                                <ul class="dropdown-menu dropdown-menu-right">
                                    @foreach(@$obj->files as $item)
                                        <li>
                                            <a style="max-width: 250px; overflow: hidden;text-overflow:ellipsis;"
                                               target="_blank"
                                               href="{{\App\Http\Models\Media::getFileLink($item)}}"> <i
                                                        class="icon-link"> </i> {{$item}}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @else
                            <a class="label label-info mt-1" title="Xem files"
                               href="{{\App\Http\Models\Media::getFileLink(@$obj['files'][0])}}"
                               target="_blank">
                                <i class="icon-share2"> </i> Xem files
                            </a>
                        @endif
                    @else

                    @endif

                </td>
            @endif
        </tr>
    @endforeach
    </tbody>
</table>
@if(!$lsObj->total())
    <div class="mt-3 alert alert-danger alert-styled-left alert-bordered">
        Không tìm thấy dữ liệu nào ở trang này. (Hãy kiểm tra lại các điều kiện tìm kiếm hoặc phân
        trang...)
    </div>
@endif
<div class="text-center pagination-rounded-all">{{ $lsObj->render() }}</div>
<script>
    $('#checkAll').click(function() {
        $('.item-check').prop('checked', this.checked);
        if($('.item-check').filter(":checked").length) {
            $('#delete-all-checked').show();
        }else {
            $('#delete-all-checked').hide();
        }
    });

    $('.item-check').change(function () {
        var check = ($('.item-check').filter(":checked").length == $('.item-check').length);
        $('#checkAll').prop("checked", check);
        if($('.item-check').filter(":checked").length) {
            $('#delete-all-checked').show();
        }else {
            $('#delete-all-checked').hide();
        }
    });

</script>