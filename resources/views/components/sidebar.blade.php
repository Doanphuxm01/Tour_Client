@php
    $lsFormatDate = ['created_at', 'actived_at', 'updated_at']
@endphp
<style>
    .daterangepicker.opensleft .calendars {
        display: none;
    }
</style>
<!-- Sidebar content -->
<div class="sidebar-content">

    <!-- Sidebar search -->
    <form action="" id="form-filter">
        @if(@$FILTER)
        @foreach($FILTER as $filter)
        <div class="card">
            <div class="card-header bg-transparent header-elements-inline">
                <span class="text-uppercase font-size-sm font-weight-semibold">{{ @$filter['label'] }}</span>
                <div class="header-elements">
                    <div class="list-icons">
                        <a class="list-icons-item" data-action="collapse"></a>
                    </div>
                </div>
            </div>
            @if(!empty($filter['group']))
            <div class="card-body">
                @foreach($filter['group'] as $obj)
                <div class="form-group-feedback form-group-feedback-right">
                    <input name="q[{{ @$obj['field']['key'] }}]" {{@$obj['field']['disabled']}} {{ @$obj['field']['readonly'] ? 'readonly="readonly"' : '' }} autocomplete="{{@$obj['field']['autocomplte']}}"
                           type="search" value="{{@$q[@$obj['field']['key']]}}" class="form-control {{ @$obj['field']['class'] }}"
                           placeholder="{{ @$obj['placeholder'] }}">
                    <div class="form-control-feedback">
                        <i class="{{ @$obj['icons']['class'] }} font-size-base text-muted"></i>
                    </div>
                </div>
                @endforeach

            </div>
            @endif
        </div>
        @endforeach
        @endif
        <div class="card text-center">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <a href="{{ request()->url() }}" onclick="return confirm('Bạn muốn reset bộ lọc?')">
                            <button type="button" class="btn btn-danger btn-block">Reset</button>
                        </a>
                    </div>
                    <div class="col">
                        <button type="submit" class="btn btn-info btn-block">Lọc</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- /sidebar search -->

</div>
<!-- /sidebar content -->
