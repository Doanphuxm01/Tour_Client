<div class="dasboard-menu">
    <div class="dasboard-menu-btn color3-bg">Dashboard Menu <i class="fal fa-bars"></i></div>
    <ul class="dasboard-menu-wrap">
        <li>
            <a href="{{ route('AdminSystem') }}"@if(\Request::route()->getName() == 'AdminSystem')class="user-profile-act"@endif><i class="far fa-user"></i>Thông tin cá nhân</a>
            <ul>
                <li><a href="{{ route('AdminSystem', ['action'=>'info']) }}">Cập nhật thông tin</a></li>
                <li><a href="{{ route('AdminSystem', ['action'=>'changepass']) }}">Đổi mật khẩu</a></li>
            </ul>
        </li>
        <li><a href="{{ route('AdminBooking') }}" @if(\Request::route()->getName() == 'AdminBooking')class="user-profile-act"@endif> <i class="far fa-calendar-check"></i> Booking <span>{{ $NUMBOOKING }}</span></a></li>
    </ul>
</div>
