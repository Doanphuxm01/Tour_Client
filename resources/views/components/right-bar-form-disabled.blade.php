<div class="pl-lg-1 pt-0">
    <div class="timeline timeline-one timeline-one-tab">
        <article class="timeline-item ">
            <div class="timeline-desk">
                <div class="timeline-box mb-0 mt-0 p-0" style="border-radius: 0">
                    <span class="arrow"></span>
                    <span class="timeline-icon"><i class="mdi mdi-adjust"></i></span>
                    <div class="col-12x">

                        <ul class="nav nav-tabs nav-tabs-highlight nav-tabs-bottom">
                            <?php
                            if (@$table_name === \App\Http\Models\Contract::table_name) {
                                $activeTab = request('tab', 'tabContractQuyTrinhThucHien');

                            } else {
                                $activeTab = request('tab', false);

                            }
                            ?>

                            @if ($table_name ===\App\Http\Models\Contract::table_name)
                                @if(@$extend_id)
                                    <li class="nav-item">
                                        <a href="#tabContractQuyTrinhThucHien" data-toggle="tab" aria-expanded="true"
                                           class="nav-link {{$activeTab ==='tabContractQuyTrinhThucHien' ? 'active' : ''}}">
                                            Quy trình thực hiện
                                        </a>
                                    </li>
                                @endif
                                <li class="nav-item">
                                    <a href="#tabContractLichSuHopDong" data-toggle="tab" aria-expanded="true"
                                       class="nav-link {{$activeTab ==='tabContractLichSuHopDong' ? 'active' : ''}}">
                                        Lịch sử hợp đồng
                                    </a>
                                </li>
                            @endif



                            @if (isset($options['tabs']) && in_array('chi-tiet-ho-so',$options['tabs']))
                                <?php
                                $activeTab = true;
                                ?>
                                <li class="nav-item">
                                    <a href="#chi-tiet-ho-so" data-toggle="tab" aria-expanded="true"
                                       class="nav-link active">
                                        Chi tiết hồ sơ
                                    </a>
                                </li>
                            @endif
                            <li class="nav-item">
                                <a href="#tabNote" data-toggle="tab" aria-expanded="false"
                                   class="nav-link {{!$activeTab?'active':''}}">
                                    Ghi chú
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#tabHistory" data-toggle="tab" aria-expanded="true" class="nav-link ">
                                    Lịch sử
                                </a>
                            </li>
                            {{--<li class="nav-item">
                                <a href="#tabFiles" data-toggle="tab" aria-expanded="true" class="nav-link ">
                                    Files, tài liệu
                                </a>
                            </li>--}}
                        </ul>
                    </div>
                </div>
            </div>
        </article>

    </div>
</div>
<div class="tab-content pt-0">
    @if (isset($options['tabs']) && in_array('chi-tiet-ho-so',$options['tabs']))
        <div class="tab-pane active pl-lg-1 pt-0" id="chi-tiet-ho-so">
            @include('components/right_bar_tab/tab-chi-tiet-ho-so',['disabled'=>true])
        </div>
    @endif

    <div class="tab-pane {{!$activeTab?'active':''}} pl-lg-1 pt-0" id="tabNote">
        <div class="col-12x">
            <div class="timeline timeline-one">

                <article class="timeline-item ">
                    <div class="timeline-desk">
                        <div class="timeline-box mt-0" style="border-radius: 0">
                            <p class="text-muted mb-0">
                                <span class="text-primary"> Sau khi thêm bản ghi </span> bạn có thể sử dụng chức năng
                                ghi chú
                                <br/>
                                Giúp bạn ghi lại những gì liên quan đến bản ghi này theo từng giai đoạn, thời gian khác
                                nhau
                                <br/>
                                Nội dung sẽ được hiển thị theo format mẫu bên dưới.
                            </p>
                        </div>
                    </div>
                </article>

            </div>

            <div class="timeline timeline-one">
                <article class="timeline-item ">
                    <div class="timeline-desk">
                        <div class="timeline-box">
                            <span class="arrow"></span>
                            <span class="timeline-icon"><i class="mdi mdi-adjust"></i></span>
                            <h4 class="mt-0 font-14 text-muted">Tên nhân viên ghi chú <span class="text-muted">
                                    <small>Thời điểm ghi chú</small>
                                </span></h4>

                            <p class="mb-0 text-muted">Đây là mẫu format ghi chú. Nội dung ghi chú sẽ được hiển thị ở
                                đây </p>
                        </div>
                    </div>
                </article>
            </div>
            <!-- end timeline -->
        </div> <!-- end col -->
    </div>
    <div class="tab-pane  pl-lg-1 pt-0" id="tabHistory">
        <div class="col-12x">
            <div class="timeline timeline-one">

                <article class="timeline-item ">
                    <div class="timeline-desk">
                        <div class="timeline-box mt-0" style="border-radius: 0">
                            <p class="text-muted mb-0">
                                Dưới đây là lịch sử các lần cập nhật dữ liệu
                                <br/>
                                Giúp bạn xem được ai đã cập nhật thông tin? cập nhật vào lúc nào? và cập nhật những gì?
                                <br/>
                                Nội dung sẽ được hiển thị theo format mẫu bên dưới.
                            </p>
                        </div>
                    </div>
                </article>
            </div>

            <div class="timeline timeline-one">
                <article class="timeline-item ">
                    <div class="timeline-desk">
                        <div class="timeline-box">
                            <span class="arrow"></span>
                            <span class="timeline-icon"><i class="mdi mdi-adjust"></i></span>
                            <h4 class="mt-0 font-14 text-muted">Tên nhân viên cập nhật dữ liệu <span class="text-muted">
                                    <small>Thời điểm cập nhật</small>
                                </span></h4>

                            <p class="mb-0 text-muted">Nội dung cập nhật được ghi ở đây. Và bạn có thể xem chi tiết nội
                                dung thay đổi là gì </p>
                        </div>
                    </div>
                </article>
            </div>
        </div>
    </div>

    <div class="tab-pane  pl-lg-1 pt-0" id="tabFiles">
        <div class="col-12x">
            <div class="timeline timeline-one">

                <article class="timeline-item ">
                    <div class="timeline-desk">
                        <div class="timeline-box mt-0" style="border-radius: 0">
                            <p class="text-muted mb-0">
                                Dưới đây là danh sách các file, tài liệu liên quan đến bản ghi
                                <br/>
                                Bạn có thể sử dụng up file, xem các file liên quan đến bản ghi này đã được upload lên.
                                <br/>
                                Nội dung sẽ được hiển thị theo format mẫu bên dưới.
                            </p>
                        </div>
                    </div>
                </article>
            </div>

            <div class="timeline timeline-one">
                <article class="timeline-item ">
                    <div class="timeline-desk">
                        <div class="timeline-box">
                            <span class="arrow"></span>
                            <span class="timeline-icon"><i class="mdi mdi-adjust"></i></span>
                            <h4 class="mt-0 font-14 text-muted">Tên nhân viên upload <span class="text-muted">
                                    <small>Thời điểm upload</small>
                                </span></h4>

                            <p class="mb-0 text-muted">Nội dung mô tả và link các file đính kèm trong mỗi lần upload
                                được hiển thị ở đây </p>
                        </div>
                    </div>
                </article>
            </div>
        </div>
    </div>
    @if ($table_name ===\App\Http\Models\Contract::table_name)
        @if(@$extend_id)
            @include('components.right_bar_tab.tab-contract-quy-trinh', ['activeTab'=>$activeTab])
        @endif
        @include('components.right_bar_tab.tab-contract-lich-su-hop-dong', ['activeTab'=>$activeTab])


    @endif

</div>