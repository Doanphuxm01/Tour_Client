<div class="dasboard-sidebar">
    <div class="dasboard-sidebar-content fl-wrap">
        <div class="dasboard-avatar">
            <img src="//graph.facebook.com/{{ @$MEMBER['provider_user_id'] }}/picture?type=large" alt="{{ $MEMBER['name'] }}">
        </div>
        <div class="dasboard-sidebar-item fl-wrap">
            <h3>
                <span>Xin chào </span>
                {{ $MEMBER['name'] }}
            </h3>
        </div>
        <div class="user-stats fl-wrap">
            <ul>
                <li>
                    Bookings
                    <span>{{ $NUMBOOKING }}</span>
                </li>
            </ul>
        </div>
        <a onclick="return confirm('Bạn có chắc chắn muốn đăng xuất khỏi phiên đăng nhập này?');" href="{{ route('AuthGate', ['action_name' => 'logout']) }}" class="log-out-btn color-bg">Đăng xuất<i class="far fa-sign-out"></i></a>
    </div>
</div>