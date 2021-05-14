<style>
    #pickAvatar {
        display: none;
        margin: auto;
        position: relative;
        z-index: 1;
        /* margin-top: 50px; */
        margin-top: 50%;

    }

    @if(!$preview)
    .avatar-container:hover #pickAvatar {
        display: block;
    }
    @endif

</style>
<div class="avatar-container"
     style="max-height:248px;height: 100%;padding: 10px; width: 100%;text-align: center;display: flex;align-items: center;justify-content: center;border:1px solid #80808024;">
    @if(!@$obj['avatar']['relative_link'])
        <div
                id="avatar_bg"
        >
            <div id="no_avatar_text">
                Chưa có ảnh đại diện
            </div>
            <button class="btn btn-info" id="pickAvatar" type="button">Thay avatar</button>

        </div>
    @else
        <div
                id="avatar_bg"
                style="background: url('{!! \App\Http\Models\Media::getFileLink(@$obj['avatar']['relative_link']) !!}');width: 100%;height:100%;background-size:cover;background-position:center;">
            <button class="btn btn-info" id="pickAvatar" type="button">Thay avatar</button>
        </div>
    @endif
</div>
<input type="hidden" id="avatar_brief" value="{{@$obj['avatar']['brief']}}"
       name="obj[avatar][brief]">
<input type="hidden" id="avatar_full_size_link"
       value="{{@$obj['avatar']['full_size_link']}}"
       name="obj[avatar][full_size_link]">
<input type="hidden" id="avatar_id" value="{{@$obj['avatar']['id']}}"
       name="obj[avatar][id]">
<input type="hidden" id="avatar_name" value="{{@$obj['avatar']['name']}}"
       name="obj[avatar][name]">
<input type="hidden" id="avatar_relative_link"
       value="{{@$obj['avatar']['relative_link']}}"
       name="obj[avatar][relative_link]">

@push("JS_BOTTOM_REGION")
    <script>
        _UPLOAD_INIT('pickAvatar', '#fileGlobalContainer', null, __save_change_avatar);

        function __save_change_avatar(res, file) {
            let $_element = $('#' + file.id);
            $_element.remove();
            let filetype = String(file.type).split('/');
            if (filetype.length === 0 || filetype[0] !== 'image') {
                alert('Bạn phải chọn một file ảnh');
                return false
            }
            let {data = {}} = res;

            let avatar = {
                brief: data.brief,
                full_size_link: data.full_size_link,
                id: data.id,
                name: data.name,
                relative_link: data.relative_link,
            };
            $("#avatar_brief").val(avatar.brief);
            $("#avatar_full_size_link").val(avatar.full_size_link);
            $("#avatar_id").val(avatar.id);
            $("#avatar_name").val(avatar.name);
            $("#avatar_relative_link").val(avatar.relative_link);
            $('#no_avatar_text').hide()
            $('#avatar_bg').css('background', `url(${avatar.full_size_link})`)
            $('#avatar_bg').css('background-position', 'center')
            $('#avatar_bg').css('background-size', 'cover')
            $('#avatar_bg').css('height', `100%`)
            $('#avatar_bg').css('width', `100%`)
            {{--_POST_FORM('#obj_avatar', '{!! admin_link('/staff/_change_avatar') !!}',)--}}
        }
    </script>
@endpush