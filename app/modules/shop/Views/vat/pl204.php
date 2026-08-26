<div class="container py-4 mt-5">

    <?php include __DIR__ . '/navbar.php'; ?>

    <!-- ===================================================== -->
    <!-- TIÊU ĐỀ -->
    <!-- ===================================================== -->

    <div class="text-center small fw-bold mb-2">

        <div>
            GIẢM THUẾ GIÁ TRỊ GIA TĂNG THEO NGHỊ QUYẾT SỐ 204/2025/QH15
        </div>

        <div>
            (Kèm theo Tờ khai thuế GTGT kỳ tính thuế quý IV năm 2025)
        </div>

    </div>


    <!-- ========================= -->
    <!-- THÔNG TIN -->
    <!-- ========================= -->

    <div class="card mb-3">

        <div class="card-body p-3">

            <div class="row g-2 align-items-center">

                <div class="col-12">

                    <div class="row g-2 align-items-center">

                        <label class="col-md-2 col-form-label col-form-label-sm">
                            [01] Tên người nộp thuế
                        </label>

                        <div class="col-md-10">
                            <input
                                type="text"
                                class="form-control form-control-sm"
                                value="CÔNG TY TNHH MẠNH DŨNG SPORTS"
                                readonly
                            >
                        </div>

                    </div>

                </div>


                <div class="col-md-12">

                    <div class="row g-2 align-items-center">

                        <label class="col-md-2 col-form-label col-form-label-sm">
                            [02] Mã số thuế
                        </label>

                        <div class="col-md-10">
                            <input
                                type="text"
                                class="form-control form-control-sm"
                                value="0109166122"
                                readonly
                            >
                        </div>

                    </div>

                </div>


                <div class="col-md-12"></div>


                <div class="col-md-12">

                    <div class="row g-2 align-items-center">

                        <label class="col-md-2 col-form-label col-form-label-sm">
                            [03] Tên đại lý thuế
                        </label>

                        <div class="col-md-10">
                            <input
                                type="text"
                                class="form-control form-control-sm"
                            >
                        </div>

                    </div>

                </div>


                <div class="col-md-12">

                    <div class="row g-2 align-items-center">

                        <label class="col-md-2 col-form-label col-form-label-sm">
                            [04] Mã số thuế
                        </label>

                        <div class="col-md-10">
                            <input
                                type="text"
                                class="form-control form-control-sm"
                            >
                        </div>

                    </div>

                </div>


                <div class="col-12 text-end">

                    <div class="small">
                        Đơn vị tiền: Đồng Việt Nam
                    </div>

                </div>

            </div>

        </div>

    </div>


<!-- ===================================================== -->
<!-- BẢNG 1 -->
<!-- ===================================================== -->

<div class="small fw-bold mb-1">
    I. Hàng hóa, dịch vụ mua vào trong kỳ được áp dụng mức thuế suất thuế giá trị gia tăng thấp hơn theo phương pháp khấu trừ thuế:
</div>


<div class="table-responsive">

    <table class="table table-sm table-bordered border-dark align-middle mb-1 small">

        <thead class="text-center fw-bold">

            <tr>

                <th rowspan="2" class="align-middle">
                    STT
                </th>

                <th rowspan="2" class="align-middle">
                    Tên hàng hóa, dịch vụ
                </th>

                <th rowspan="2" class="align-middle">
                    Giá trị hàng hóa, dịch vụ chưa có thuế GTGT<br>
                    được khấu trừ
                </th>

                <th rowspan="2" class="align-middle">
                    Thuế GTGT của hàng hóa, dịch vụ mua vào được<br>
                    khấu trừ
                </th>

            </tr>

            <tr>
            </tr>

            <tr>

                <th>(1)</th>

                <th>(2)</th>

                <th>(3)</th>

                <th>(4)</th>

            </tr>

        </thead>


        <tbody>

            <?php foreach (($vatPl204[0] ?? []) as $index => $item): ?>

                <tr>

                    <td class="text-center">
                        <?= $index + 1 ?>
                    </td>

                    <td class="p-1">

                        <input
                            type="text"
                            class="form-control form-control-sm"
                            value="<?= htmlspecialchars($item['name'] ?? '') ?>"
                        >

                    </td>

                    <td class="p-1">

                        <input
                            type="text"
                            class="form-control form-control-sm text-end"
                            value="<?= number_format(
                                (float)($item['value'] ?? 0),
                                0,
                                ',',
                                '.'
                            ) ?>"
                        >

                    </td>

                    <td class="p-1">

                        <input
                            type="text"
                            class="form-control form-control-sm text-end"
                            value="<?= number_format(
                                (float)($item['vat'] ?? 0),
                                0,
                                ',',
                                '.'
                            ) ?>"
                        >

                    </td>

                </tr>

            <?php endforeach; ?>


            <!-- TỔNG -->

            <tr class="fw-bold">

                <td colspan="2" class="text-end">
                    Tổng cộng
                </td>

                <td class="p-1">

                    <input
                        type="text"
                        class="form-control form-control-sm text-end fw-bold"
                        value="<?= number_format(
                            (float)($vatPl204[1][0]['total_value'] ?? 0),
                            0,
                            ',',
                            '.'
                        ) ?>"
                        readonly
                    >

                </td>

                <td class="p-1">

                    <input
                        type="text"
                        class="form-control form-control-sm text-end fw-bold"
                        value="<?= number_format(
                            (float)($vatPl204[1][0]['total_vat'] ?? 0),
                            0,
                            ',',
                            '.'
                        ) ?>"
                        readonly
                    >

                </td>

            </tr>

        </tbody>

    </table>

</div>


<!-- ===================================================== -->
<!-- BẢNG 2 -->
<!-- ===================================================== -->

<div class="small fw-bold mb-1">
    II. Hàng hóa, dịch vụ bị bán ra trong kỳ:
</div>


<div class="table-responsive">

    <table class="table table-sm table-bordered border-dark align-middle mb-1 small">

        <thead class="text-center fw-bold">

            <tr>

                <th rowspan="2" class="align-middle">
                    STT
                </th>

                <th rowspan="2" class="align-middle">
                    Tên hàng hóa, dịch vụ
                </th>

                <th rowspan="2" class="align-middle">
                    Giá trị hàng hóa, dịch vụ<br>
                    chưa có thuế GTGT
                </th>

                <th rowspan="2" class="align-middle">
                    Thuế suất thuế GTGT<br>
                    (%)
                </th>

                <th rowspan="2" class="align-middle">
                    Thuế suất thuế GTGT<br>
                    sau giảm
                </th>

                <th rowspan="2" class="align-middle">
                    Thuế GTGT của hàng<br>
                    hóa, dịch vụ bán ra<br>
                    được giảm
                </th>

            </tr>

            <tr>
            </tr>

            <tr>

                <th>(1)</th>

                <th>(2)</th>

                <th>(3)</th>

                <th>(4)</th>

                <th>(5) = (4) × 80%</th>

                <th>(6) = (3) × [(4) - (5)]</th>

            </tr>

        </thead>


        <tbody>

            <?php foreach (($vatPl204[2] ?? []) as $index => $item): ?>

                <tr>

                    <td class="text-center">
                        <?= $index + 1 ?>
                    </td>

                    <td class="p-1">

                        <input
                            type="text"
                            class="form-control form-control-sm"
                            value="<?= htmlspecialchars($item['name'] ?? '') ?>"
                        >

                    </td>

                    <td class="p-1">

                        <input
                            type="text"
                            class="form-control form-control-sm text-end"
                            value="<?= number_format(
                                (float)($item['value'] ?? 0),
                                0,
                                ',',
                                '.'
                            ) ?>"
                        >

                    </td>

                    <td class="text-end">

                        <?= htmlspecialchars(
                            $item['vat_rate'] ?? 0
                        ) ?>

                    </td>

                    <td class="text-end">

                        <?= number_format(
                            (float)($item['vat_rate_after_reduction'] ?? 0),
                            0,
                            ',',
                            '.'
                        ) ?>

                    </td>

                    <td class="p-1">

                        <input
                            type="text"
                            class="form-control form-control-sm text-end"
                            value="<?= number_format(
                                (float)($item['vat_reduced'] ?? 0),
                                0,
                                ',',
                                '.'
                            ) ?>"
                            readonly
                        >

                    </td>

                </tr>

            <?php endforeach; ?>


            <!-- TỔNG -->

            <tr class="fw-bold">

                <td colspan="2" class="text-end">
                    Tổng cộng
                </td>

                <td class="p-1">

                    <input
                        type="text"
                        class="form-control form-control-sm text-end fw-bold"
                        value="<?= number_format(
                            (float)($vatPl204[3][0]['total_value'] ?? 0),
                            0,
                            ',',
                            '.'
                        ) ?>"
                        readonly
                    >

                </td>

                <td></td>

                <td></td>

                <td class="p-1">

                    <input
                        type="text"
                        class="form-control form-control-sm text-end fw-bold"
                        value="<?= number_format(
                            (float)($vatPl204[3][0]['total_vat_reduced'] ?? 0),
                            0,
                            ',',
                            '.'
                        ) ?>"
                        readonly
                    >

                </td>

            </tr>

        </tbody>

    </table>

</div>


<!-- ===================================================== -->
<!-- GHI CHÚ -->
<!-- ===================================================== -->

<div class="small fw-bold">

    I) Chi tiết thuế GTGT của hàng hóa, dịch vụ mua vào trong kỳ được áp dụng mức thuế suất thuế GTGT thấp hơn theo phương pháp khấu trừ thuế giá trị gia tăng

</div>

<div class="small mt-1 d-flex align-items-center gap-1">

    <span class="text-nowrap">
        Tổng tiền thuế GTGT:
    </span>

    <input
        type="text"
        class="form-control form-control-sm text-end"
        value="5.226.124"
        readonly
    >

    <span class="text-nowrap">
        đồng
    </span>

</div>
</div>