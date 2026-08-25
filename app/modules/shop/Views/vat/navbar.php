    <div class="d-flex justify-content-end align-items-center mb-5">

        <div class="d-flex gap-2">


            <div class="btn-group btn-group-sm">


                <a 
                    href="<?= route('/admin/vat') ?>"
                    class="btn btn-outline-secondary <?= active_menu('/admin/vat') ?>"
                >
                    Tờ khai chính
                </a>


                <a 
                    href="<?= route('/admin/vat/pl011') ?>"
                    class="btn btn-outline-secondary <?= active_menu('/admin/vat/pl011') ?>"
                >
                    PL01-1/GTGT
                </a>


                <a 
                    href="<?= route('/admin/vat/pl012') ?>"
                    class="btn btn-outline-secondary <?= active_menu('/admin/vat/pl012') ?>"
                >
                    PL01-2/GTGT
                </a>

                <a 
                    href="<?= route('/admin/vat/pl204') ?>"
                    class="btn btn-outline-secondary <?= active_menu('/admin/vat/pl204') ?>"
                >
                    PL 204/2025/QH15
                </a>

            </div>


            <div class="d-flex gap-2">

                <a 
                    href="<?= route('/job/ducan/update') ?>"
                    class="btn btn-sm btn-outline-danger"
                    onclick="return confirm('Bạn có muốn cập nhật giá từ Đức An?')"
                >
                    Xuất XML
                </a>

            </div>


        </div>

    </div>