@extends('backend_gate')
@push('JS_BOTTOM_REGION')
    <script>
        (function () {
            console.log($('.modal-open'))
            setTimeout(function () {
                $('.modal-open').trigger('click')
            }, 1000)
        })();
    </script>
@endpush