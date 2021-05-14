<div class="form-group">
    @if(!isset($lsStatus))
    @php($lsStatus = \App\Http\Models\BaseModel::getListStatus(@$obj['status']))
    @endif
    <label for="" class="control-label">Trạng thái</label>
    <select style="width: 100%;height: 35px" class="select-search select-md" name="obj[status]">
        <option value="">Chưa lựa chọn</option>
        @foreach ($lsStatus as $s)
            <option value='{{ $s['id'] }}' @isset($s['checked']) selected @endisset >{{ $s['text'] }}</option>
        @endforeach
    </select>
</div>