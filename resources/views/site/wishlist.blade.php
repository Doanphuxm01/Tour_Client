<div class="wishlist-wrap scrollbar-inner novis_wishlist">
    <div class="box-widget-content">
        <div class="widget-posts fl-wrap">
            <ul id="wishlist_otoke">
            </ul>

            <a href="{{ route('FeBookingCart', ['action' => 'cart']) }}" class="btn-more text-success float-left">Đặt ngay</a>
            <a onclick="return confirm('Bạn có chắc chắn muốn làm trống giỏ hàng?');" href="{{ public_link('booking/checkout/destroyCart') }}" class="btn-more text-danger">Làm trống</a>
        </div>
    </div>
</div>