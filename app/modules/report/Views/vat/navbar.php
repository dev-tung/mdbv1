    <div class="d-flex justify-content-between align-items-center mb-3">

        <div class="d-flex gap-2">


            <div class="btn-group btn-group-sm">


                <a 
                    href="<?= route('/vat/index') ?>"
                    class="btn btn-outline-secondary active<?= active_menu('/vat/index') ?>"
                >
                    Tờ khai chính
                </a>


                <a 
                    href="<?= route('/vat/011') ?>"
                    class="btn btn-outline-secondary <?= active_menu('/vat/011') ?>"
                >
                    PL01-1/GTGT
                </a>


                <a 
                    href="<?= route('/vat/012') ?>"
                    class="btn btn-outline-secondary <?= active_menu('/vat/012') ?>"
                >
                    PL01-2/GTGT
                </a>

                <a 
                    href="<?= route('/vat/204') ?>"
                    class="btn btn-outline-secondary <?= active_menu('/vat/204') ?>"
                >
                    PL 204/2025/QH15
                </a>

            </div>


            <div class="d-flex gap-2">

                <a 
                    href="<?= route('/job/ducan/update') ?>"
                    class="btn btn-sm btn-outline-warning"
                    onclick="return confirm('Bạn có muốn cập nhật giá từ Đức An?')"
                >
                    Xuất XML
                </a>

            </div>


        </div>

    </div>