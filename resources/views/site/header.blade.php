<header class="main-header">
	<!-- header-top-->
	<div class="header-top fl-wrap">
		<div class="container">
			<div class="logo-holder">
				<a href="{{ public_link() }}"><img
							src="{{ public_link('vietrantour/images/logoMain.png') }}"
							alt="Logo Vietrantour"></a>
			</div>

			@php($member = \App\Http\Models\Member::getCurent())

			<div class="info">
				<div class="header-info-email" style="width:auto;">
					<a class="general-info" href="mailto:{{@$IO_CONFIG_WEBSITE['email']}}"><i class="fal fa-envelope"></i> {{@$IO_CONFIG_WEBSITE['email']}}</a>
				</div>
				<div class="header-info-email" style="width:auto;">
					<a class="general-info" href="tel:{{@$IO_CONFIG_WEBSITE['hotline']}}"><i class="fas fa-headset"></i> {{@$IO_CONFIG_WEBSITE['hotline']}}</a>
				</div>

				@if(!isset($member['_id']))
				<div class="show-reg-form modal-open" style="width:auto;">
					<i class="fa fa-user-circle"></i>Đăng nhập
				</div>
				@endif
			</div>

		</div>
	</div>
	<!-- header-top end-->
	<!-- header-inner-->
	<div class="header-inner fl-wrap">
		<div class="container">
			<div class="show-search-button">
				<span>Tìm kiếm</span> <i class="fas fa-search"></i>
			</div>
			<div class="wishlist-link">
				<i class="fal fa-shopping-cart"></i><span class="wl_counter">0</span>
			</div>
			@if(isset($member['_id']))
				<div class="header-user-menu">
					<div class="header-user-name">
					<span>@if(@$member['provider_user_id'])<img
								src="//graph.facebook.com/{{ $member['provider_user_id'] }}/picture"
								alt="{{ $member['name'] }}">@endif</span> {{ $member['name'] }}
					</div>
					<ul>
						<li><a href="{{ route('AdminSystem') }}">Thông tin cá nhân</a></li>
						<li><a href="{{ route('AdminBooking') }}"> Bookings </a></li>
						<li><a onclick="return confirm('Bạn có chắc chắn muốn đăng xuất khỏi phiên đăng nhập này?');" href="{{ route('AuthGate', ['action_name' => 'logout']) }}">Đăng xuất</a></li>
					</ul>
				</div>
			@endif
			<div class="home-btn">
				<a href="{{ public_link() }}"><i class="fas fa-home"></i></a>
			</div>
			<!-- nav-button-wrap-->
			<div class="nav-button-wrap color-bg">
				<div class="nav-button">
					<span></span><span></span><span></span>
				</div>
			</div>
			<!-- nav-button-wrap end-->
			<!--  navigation -->

			<div class="nav-holder main-menu">
				{!! @$VAR['HEADER'] !!}
			</div>
			<!-- navigation  end -->
			<!-- wishlist-wrap-->
			@include('site.wishlist')
			<!-- wishlist-wrap end-->
		</div>
	</div>
	<!-- header-inner end-->
	<!-- header-search -->
	@if(isset($IO_LOCATION))
		<form  name="SearchInputForm" action="{{ route('FeHome.Search') }}"  id="SearchInputForm" method="get">
			<div class="header-search vis-search">
				<div class="container">
					<div class="row">
						<!-- header-search-input-item -->
						<div class="col-sm-6">
							<div class="header-search-input-item fl-wrap location autocomplete-container">
								<label style="color: aliceblue">Địa điểm đến</label>
								<div class="listsearch-input-item">
									<select name="diaDiemDen" data-placeholder="Hà Nội, Phú Quốc, Buôn Ma Thuột,..." class="chosen-select" >
										<option value="">Hà Nội, Phú Quốc, Buôn Ma Thuột,...</option>
										@foreach($IO_LOCATION as $location)
											<option value="{{ $location['alias'] }}" @if(isset($q['diaDiemDen']) && $q['diaDiemDen'] == $location['alias']) selected @endif>{{ $location['name'] }}</option>
										@endforeach
									</select>
								</div>
							</div>
						</div>
						<!-- header-search-input-item end -->
						<!-- header-search-input-item -->
						<div class="col-sm-6">
							<div class="header-search-input-item fl-wrap date-parent">
								<label style="color: aliceblue">Thời gian </label> <span
										class="header-search-input-item-icon"><i
											class="fal fa-calendar-check"></i></span>
								<input type="text" placeholder="Tất cả" autocomplete="off" class="header-search-input" name="thoiGian"/>
							</div>
						</div>
						<div class="col-md-6 mt-2">
							<div class="col-list-search-input-item fl-wrap location autocomplete-container">
								<label style="color: aliceblue">Tìm kiếm chuyến đi</label>
								<span class="header-search-input-item-icon"><i class="fal fa-map-marker-alt"></i></span>
								<input type="text" name="q" placeholder="Nha Trang - Đà Lạt" class=""  value=""/>
							</div>
						</div>
						<!-- header-search-input-item end -->
						<!-- header-search-input-item -->
						<div class="col-sm-2 mt-2">
							<div class="header-search-input-item fl-wrap">
								<button class="header-search-button"   type="submit">
									Tìm kiếm <i class="far fa-search"></i>
								</button>
							</div>
						</div>
						<!-- header-search-input-item end -->
					</div>
				</div>
				<div class="close-header-search">
					<i class="fal fa-angle-double-up"></i>
				</div>
			</div>
		</form>

@endif
<!-- header-search  end -->
</header>
