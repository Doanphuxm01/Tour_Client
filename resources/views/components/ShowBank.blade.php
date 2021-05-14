<div class="table-responsive">
    <table class="table">
        <thead>
        <tr>
            <th width="300">Tài khoản ngân hàng</th>
            <th width="200">Số</th>
            <th width="1" class="text-right"><a onclick="clone_tk_ngan_hang()"class="btn btn-link text-primary"><i class="icon-add-to-list"></i></a></th>
        </tr>
        </thead>
        <tbody id="obj_tk_ngan_hang">
        @isset($obj['tk_ngan_hang'])
            @foreach(@$obj['tk_ngan_hang'] as $key=>$value)
                <tr>
                    <td>
                        <select name="obj[tk_ngan_hang][{{$key}}][id]" class="select-search select-md"
                        >
                            <option value="">Chưa lựa chọn</option>
                            @foreach($allBankDataList as $val)
                                <option @if(isset($value['id'])  && $value['id']==$val['_id']))
                                        selected
                                        @endif value="{{$val['_id']}}">{{$val['name']}}</option>

                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="text" class="form-control input-type-number"
                               name="obj[tk_ngan_hang][{{$key}}][so]"
                               value="{{@$value['so']}}"
                               placeholder="Nhập số">

                    </td>
                    <td><i class="icon-trash text-danger"
                           onclick="confirm('Bạn có muốn xoá không? ') &&$(this).parents('tr').remove()"></i>
                    </td>
                </tr>

            @endforeach
        @endisset

        </tbody>
    </table>
</div>
<script>
    function clone_tk_ngan_hang() {
        let index = $('#obj_tk_ngan_hang tr').length
        let temp_select_class = "select-search-" + Number(new Date())
        let tmp = `<tr>
                                <td>
                                   <select name="obj[tk_ngan_hang][${index}][id]" class="${temp_select_class} select-md">
                                        <option value="">Chưa lựa chọn</option>
                                        @foreach($allBankDataList as $val)
        <option  value="{{$val['_id']}}">{{$val['name']}}</option>
                                        @endforeach
        </select>
    </td>
    <td>
        <input type="text" class="form-control input-type-number" name="obj[tk_ngan_hang][${index}][so]"placeholder="Nhập số">
                                </td>
                                <td class='text-right'><i class="icon-trash text-danger"
                                       onclick="confirm('Bạn có muốn xoá không? ') &&$(this).parents('tr').remove()"></i>
                                </td>
                       </tr>
        `
        $('#obj_tk_ngan_hang').append(tmp)
        DATE_PICKER_INIT()
        INPUT_NUMBER()
        $(`.${temp_select_class}`).select2()
    }
</script>