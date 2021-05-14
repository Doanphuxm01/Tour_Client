<div class="modal-dialog modal-full" style="width: 100%" role="document">
    <div class="modal-content">
        <div class="modal-header bg-teal">
            <h5 class="modal-title">Chi tiết lịch sử thay đổi thông tin của đối tượng
            </h5>
            <button type="button" onclick='_CLOSE_MODAL()' class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">


            @switch($obj['type'])
                @case (\App\Http\Models\Logs::TYPE_LOGIN)
                @include('components.history.login')
                @break
                @case (\App\Http\Models\Logs::TYPE_UPDATED)

                @break
                @case (\App\Http\Models\Logs::TYPE_LOGIN)

                @break
                @case (\App\Http\Models\Logs::TYPE_LOGIN)

                @break

                @default


                @if(false)
                    <div class="row ">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="mb-3 header-title">
                                        Những thông tin có sự thay đổi
                                    </h4>
                                    <div id="dff"></div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-sm-12 col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="mb-3 header-title">
                                        Trước khi sửa thông tin
                                    </h4>
                                    {!! \App\Elibs\eBug::show($obj['before']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="mb-3 header-title">
                                        Sau khi sửa thông tin
                                    </h4>
                                    {!! \App\Elibs\eBug::show($obj['after']) !!}

                                </div>
                            </div>
                        </div>

                    </div>
                @endif
            @endswitch

            <div class="row mt-3">
                <div class="col-sm-12 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="mb-3 header-title">
                                Trước khi sửa thông tin
                            </h4>
                            @foreach($obj['before'] as $key=> $value)
                                @if($key !=='updated_at')

                                    <div>
                                        <b>{{$key}}:</b>
                                        <p>
                                            {{print_r($value,true)}}
                                        </p>
                                    </div>@endif
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="mb-3 header-title">
                                Sau khi sửa thông tin
                            </h4>
                            @foreach($obj['after'] as $key=> $value)
                                @if($key !=='updated_at')
                                    <div
                                            @if(print_r(@$obj['before'][$key], true) !== print_r(@$obj['after'][$key], true))
                                            style="background-color: lightgrey"
                                            @endif
                                    >
                                        <b>{{$key}}:</b>
                                        <p>
                                            {{print_r($value,true)}}
                                        </p>
                                    </div>
                                @endif
                            @endforeach

                        </div>
                    </div>
                </div>

        {{--{{\App\Elibs\Debug::show($obj)}}--}}
            </div>
        </div>
    </div>

    <script type="text/javascript">
        var dtBefore = {!! json_encode($obj['before']) !!};
        var dtAfter = {!! json_encode($obj['after']) !!};
        var delta = jsondiffpatch.diff(dtBefore, dtAfter);
        console.log(delta)

        document.getElementById('dff').innerHTML = jsondiffpatch.formatters.html.format(delta, delta);

    </script>
