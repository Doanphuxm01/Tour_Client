@if ($paginator->hasPages())
<div class="pagination">
    @if ($paginator->onFirstPage())
        <a href="javascript:void(0)" class="prevposts-link"><i class="fa fa-caret-left"></i></a>
    @else
        <a href="javascript:void(0)" onclick="{{isset($funcAlias) ? $funcAlias : 'changePage'}}({{ $paginator->currentPage()-1 }} @if(isset($is_ajax)) ,'{{$selector_id_to_fill}}','{{$selector_form_for_get}}','{{$url_get_data}}' @endif  )" class="prevposts-link"><i class="fa fa-caret-left"></i></a>
    @endif
    @foreach ($elements as $element)
        @if (is_string($element))
            <a disabled="" href="javascript:void(0)">{{ $element }}</a>
        @endif
        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                    <a href="javascript:void(0);" data-dt-idx="{{ $page }}" tabindex="0" class="current-page">{{ $page }}</a>
                @else
                    <a href="javascript:void(0);" onclick="{{isset($funcAlias) ? $funcAlias : 'changePage'}}({{ $page }})">{{ $page }}</a>
                @endif
            @endforeach
        @endif
    @endforeach
    @if ($paginator->hasMorePages())
        <a href="javascript:void(0);" onclick="{{isset($funcAlias) ? $funcAlias : 'changePage'}}({{ $paginator->currentPage()+1 }})" class="nextposts-link"><i class="fa fa-caret-right"></i></a>
    @else
        <a href="javascript:void(0);" class="nextposts-link"><i class="fa fa-caret-right"></i></a>
    @endif
</div>
@endif

<script>
    function {{isset($funcAlias) ? $funcAlias : 'changePage'}}(page,selector_id_to_fill,selector_form_for_get,url) {
        var formData = document.getElementById(selector_form_for_get);
        formData = formData != null ? toJSONString(formData) : {};
        console.log(formData)
        if(typeof formData == 'object') {
            formData.page = page;
            _GET_URL(url, {
                callback: function (json) {
                    if (json.error == 0) {
                        document.getElementById(selector_id_to_fill).innerHTML = json.data;
                    } else {
                        console.log(json);
                    }
                }
            })
        }
    }
    function toJSONString( form ) {
        var obj = {};
        var elements = form.querySelectorAll("input, select, textarea");
        for (var i = 0; i < elements.length; ++i) {
            var element = elements[i];
            var name = element.name;
            var value = element.value;

            if (name) {
                obj[name] = value;
            }
        }

        return obj;
    }
</script>