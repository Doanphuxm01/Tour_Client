@if(!isset($preview))
    @php($preview = false)
@endif
<div class="form-group {{@$group['class']}}">
    @if(@$field['label'])
    <label class="col-form-label {{@$field['label_class']}}">{{$field['label']}} @if(@$note['label'])
            <span class="{{@$note['class']}}">{{@$note['label']}}</span>
        @endif</label>
    @endif
    @if(!$preview)
        @if(is_array(@$obj[$field['key']]))
            <input
                    @if(@$field['name'])
                    name="{{@$field['name']}}"
                    @else name="obj[{{$field['key']}}]" @endif

        {{@$field['disabled']}}
            type="{{@$field['type']?@$field['type']:'text'}}"
                    class="form-control {{@$field['class']}}" id="obj-{{$field['key']}}"
                    value="{{isset($field['value']) ?$field['value']:@$obj[$field['key']]}}"
                    placeholder="{{@$placeholder}}">

        @else
            <div class="input-group {{@$field['input_group_class']}}">

            @if(@$prepend)
                <span class="input-group-prepend">
                    <span class="input-group-text"><i class="{{ @$prepend['icons'] }}"></i></span>
                </span>
            @endif
            <input   @if(@$field['name'])
                     name="{{@$field['name']}}"
                     @else name="obj[{{$field['key']}}]" @endif
                     type="{{@$field['type']?@$field['type']:'text'}}"
                     @if(@$field['data']) data-{{$field['data']}}="{{$field['data']}}" @endif
                   {{@$field['disabled']}}
                   {{ @$field['readonly'] ? 'readonly="readonly"' : '' }}
                   autocomplete="{{@$field['autocomplte']}}"
                   class="form-control {{@$field['class']}}" id="obj-{{@$field['id_prefix']}}{{@$field['key']}}"
                   value="{{isset($field['value']) ?$field['value']:@$obj[$field['key']]}}"
                   placeholder="{{@$placeholder}}">
            </div>
        @endif

    @else
        <div class="text-primary"  @if(@$field['name'])
        name="{{@$field['name']}}"
             @else name="obj[{{$field['key']}}]" @endif>
            @if(isset($field['value_preview']))
                {!! $field['value_preview'] !!}
            @else
                {{isset($field['value'])?value_show($field['value'],'Chưa cập nhật'):value_show(@$obj[$field['key']],'Chưa cập nhật')}}
            @endif
        </div>

    @endif
</div>