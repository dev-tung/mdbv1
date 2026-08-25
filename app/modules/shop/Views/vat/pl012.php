<div class="container py-4 mt-5">

    <?php include __DIR__ . '/navbar.php'; ?>


    <!-- ========================= -->
    <!-- TIÊU ĐỀ -->
    <!-- ========================= -->

    <div class="text-center mb-3">

        <h5 class="fw-bold mb-1">
            BẢNG KÊ HÓA ĐƠN, CHỨNG TỪ HÀNG HÓA, DỊCH VỤ MUA VÀO
        </h5>

    </div>


    <!-- ========================= -->
    <!-- BẢNG KÊ -->
    <!-- ========================= -->

    <div class="table-responsive">

        <table class="table table-sm table-bordered align-middle mb-1">

            <thead>

                <!-- DÒNG TIÊU ĐỀ 1 -->

                <tr class="text-center fw-bold">

                    <th rowspan="2" class="align-middle">
                        STT
                    </th>

                    <th colspan="4" class="align-middle">
                        Hóa đơn, chứng từ
                    </th>

                    <th rowspan="2" class="align-middle">
                        Tên người bán
                    </th>

                    <th rowspan="2" class="align-middle">
                        Mã số thuế người bán
                    </th>

                    <th rowspan="2" class="align-middle">
                        Giá trị HHDV mua vào chưa có thuế
                    </th>

                    <th rowspan="2" class="align-middle">
                        Thuế GTGT
                    </th>

                    <th rowspan="2" class="align-middle">
                        Ghi chú
                    </th>

                    <th rowspan="2" class="align-middle">
                    </th>

                </tr>

                <!-- DÒNG TIÊU ĐỀ 2 -->

                <tr class="text-center fw-bold small">

                    <th>
                        Mẫu số HĐ
                    </th>

                    <th>
                        Ký hiệu HĐ
                    </th>

                    <th>
                        Số hóa đơn
                    </th>

                    <th>
                        Ngày, tháng, năm lập hóa đơn
                    </th>

                </tr>

                <!-- DÒNG SỐ THỨ TỰ -->

                <tr class="text-center small">

                    <th>(1)</th>
                    <th>(2)</th>
                    <th>(3)</th>
                    <th>(4)</th>
                    <th>(5)</th>
                    <th>(6)</th>
                    <th>(7)</th>
                    <th>(8)</th>
                    <th>(9)</th>
                    <th>(10)</th>
                    <th>(11)</th>

                </tr>

            </thead>


            <tbody>


                <!-- ===================================================== -->
                <!-- 1. HÀNG HÓA, DỊCH VỤ DÙNG CHO SXKD -->
                <!-- ===================================================== -->

                <tr class="fw-bold">

                    <td colspan="11">

                        1. Hàng hóa, dịch vụ dùng cho các hoạt động sản xuất,
                        kinh doanh hàng hóa, dịch vụ chịu thuế GTGT đủ điều kiện khấu trừ thuế:

                    </td>

                </tr>


                <!-- ===================================================== -->
                <!-- DANH SÁCH HÓA ĐƠN -->
                <!-- ===================================================== -->

                <?php foreach (($vatPl204[0] ?? []) as $index => $row): ?>

                    <tr>

                        <!-- STT -->

                        <td class="text-center">
                            <?= $index + 1 ?>
                        </td>


                        <!-- Mẫu số HĐ -->

                        <td class="text-center">
                            <?= htmlspecialchars($row['invoice_form'] ?? '') ?>
                        </td>


                        <!-- Ký hiệu HĐ -->

                        <td class="text-center">
                            <?= htmlspecialchars($row['invoice_symbol'] ?? '') ?>
                        </td>


                        <!-- Số hóa đơn -->

                        <td>

                            <a href="#" class="text-decoration-none">
                                <?= htmlspecialchars($row['invoice_number'] ?? '') ?>
                            </a>

                        </td>


                        <!-- Ngày hóa đơn -->

                        <td>

                            <input
                                type="date"
                                class="form-control form-control-sm"
                                value="<?= htmlspecialchars($row['invoice_date'] ?? '') ?>"
                            >

                        </td>


                        <!-- Tên người bán -->

                        <td>

                            <input
                                type="text"
                                class="form-control form-control-sm"
                                value="<?= htmlspecialchars($row['supplier_name'] ?? '') ?>"
                            >

                        </td>


                        <!-- MST người bán -->

                        <td>

                            <input
                                type="text"
                                class="form-control form-control-sm"
                                value="<?= htmlspecialchars($row['supplier_tax_code'] ?? '') ?>"
                            >

                        </td>


                        <!-- Giá trị chưa VAT -->

                        <td>

                            <input
                                type="text"
                                class="form-control form-control-sm text-end"
                                value="<?= number_format((float)($row['value'] ?? 0), 0, ',', '.') ?>"
                            >

                        </td>


                        <!-- VAT -->

                        <td>

                            <input
                                type="text"
                                class="form-control form-control-sm text-end"
                                value="<?= number_format((float)($row['vat'] ?? 0), 0, ',', '.') ?>"
                            >

                        </td>


                        <!-- Ghi chú -->

                        <td>

                            <input
                                type="text"
                                class="form-control form-control-sm"
                                value="<?= htmlspecialchars($row['note'] ?? '') ?>"
                            >

                        </td>


                        <!-- Cột cuối -->

                        <td></td>

                    </tr>

                <?php endforeach; ?>


                <!-- ===================================================== -->
                <!-- TỔNG -->
                <!-- ===================================================== -->

                <?php

                $total = $vatPl204[1][0] ?? [
                    'total'       => 0,
                    'total_value' => 0,
                    'total_vat'   => 0,
                ];

                ?>

                <tr class="fw-bold">

                    <td colspan="7">
                        Tổng
                    </td>

                    <td>

                        <input
                            type="text"
                            class="form-control form-control-sm text-end fw-bold"
                            value="<?= number_format((float)$total['total_value'], 0, ',', '.') ?>"
                            readonly
                        >

                    </td>

                    <td>

                        <input
                            type="text"
                            class="form-control form-control-sm text-end fw-bold"
                            value="<?= number_format((float)$total['total_vat'], 0, ',', '.') ?>"
                            readonly
                        >

                    </td>

                    <td colspan="2"></td>

                </tr>


                <!-- ===================================================== -->
                <!-- TỔNG CỘNG -->
                <!-- ===================================================== -->

                <tr class="fw-bold">

                    <td colspan="7">
                        Tổng cộng
                    </td>

                    <td>

                        <input
                            type="text"
                            class="form-control form-control-sm text-end fw-bold"
                            value="<?= number_format((float)$total['total_value'], 0, ',', '.') ?>"
                            readonly
                        >

                    </td>

                    <td>

                        <input
                            type="text"
                            class="form-control form-control-sm text-end fw-bold"
                            value="<?= number_format((float)$total['total_vat'], 0, ',', '.') ?>"
                            readonly
                        >

                    </td>

                    <td colspan="2"></td>

                </tr>


                <!-- ===================================================== -->
                <!-- 2. HÀNG HÓA DÙNG CHUNG -->
                <!-- ===================================================== -->

                <tr class="fw-bold">

                    <td colspan="11">

                        2. Hàng hóa, dịch vụ dùng chung cho SXKD chịu thuế và không chịu thuế đủ điều kiện khấu trừ thuế:

                    </td>

                </tr>


                <tr>

                    <td colspan="7">
                        Tổng
                    </td>

                    <td>

                        <input
                            type="text"
                            class="form-control form-control-sm text-end"
                            value="0"
                            readonly
                        >

                    </td>

                    <td>

                        <input
                            type="text"
                            class="form-control form-control-sm text-end"
                            value="0"
                            readonly
                        >

                    </td>

                    <td colspan="2"></td>

                </tr>


                <tr>

                    <td colspan="7">
                        Tổng cộng
                    </td>

                    <td>

                        <input
                            type="text"
                            class="form-control form-control-sm text-end"
                            value="0"
                            readonly
                        >

                    </td>

                    <td>

                        <input
                            type="text"
                            class="form-control form-control-sm text-end"
                            value="0"
                            readonly
                        >

                    </td>

                    <td colspan="2"></td>

                </tr>


                <!-- ===================================================== -->
                <!-- 3. DỰ ÁN ĐẦU TƯ -->
                <!-- ===================================================== -->

                <tr class="fw-bold">

                    <td colspan="11">

                        3. Hàng hóa, dịch vụ dùng cho dự án đầu tư đủ điều kiện khấu trừ thuế:

                    </td>

                </tr>


                <tr>

                    <td colspan="7">
                        Tổng
                    </td>

                    <td>

                        <input
                            type="text"
                            class="form-control form-control-sm text-end"
                            value="0"
                            readonly
                        >

                    </td>

                    <td>

                        <input
                            type="text"
                            class="form-control form-control-sm text-end"
                            value="0"
                            readonly
                        >

                    </td>

                    <td colspan="2"></td>

                </tr>


                <tr>

                    <td colspan="7">
                        Tổng cộng
                    </td>

                    <td>

                        <input
                            type="text"
                            class="form-control form-control-sm text-end"
                            value="0"
                            readonly
                        >

                    </td>

                    <td>

                        <input
                            type="text"
                            class="form-control form-control-sm text-end"
                            value="0"
                            readonly
                        >

                    </td>

                    <td colspan="2"></td>

                </tr>


                <!-- ===================================================== -->
                <!-- 4. KHÔNG ĐỦ ĐIỀU KIỆN KHẤU TRỪ -->
                <!-- ===================================================== -->

                <tr class="fw-bold">

                    <td colspan="11">

                        4. Hàng hóa, dịch vụ không đủ điều kiện khấu trừ:

                    </td>

                </tr>


                <tr>

                    <td colspan="7">
                        Tổng
                    </td>

                    <td>

                        <input
                            type="text"
                            class="form-control form-control-sm text-end"
                            value="0"
                            readonly
                        >

                    </td>

                    <td>

                        <input
                            type="text"
                            class="form-control form-control-sm text-end"
                            value="0"
                            readonly
                        >

                    </td>

                    <td colspan="2"></td>

                </tr>


                <tr>

                    <td colspan="7">
                        Tổng cộng
                    </td>

                    <td>

                        <input
                            type="text"
                            class="form-control form-control-sm text-end"
                            value="0"
                            readonly
                        >

                    </td>

                    <td>

                        <input
                            type="text"
                            class="form-control form-control-sm text-end"
                            value="0"
                            readonly
                        >

                    </td>

                    <td colspan="2"></td>

                </tr>

            </tbody>

        </table>

    </div>


    <!-- ========================= -->
    <!-- TỔNG HỢP -->
    <!-- ========================= -->

    <div class="row g-2 mb-2">

        <div class="col-md-5">

            <div class="row g-2 align-items-center">

                <div class="col">

                    Tổng giá trị HHDV mua vào phục vụ SXKD được khấu trừ thuế GTGT:

                </div>

                <div class="col-auto">

                    <input
                        type="text"
                        class="form-control form-control-sm text-end"
                        value="<?= number_format((float)$total['total_value'], 0, ',', '.') ?>"
                        readonly
                    >

                </div>

            </div>

        </div>

    </div>


    <div class="row g-2 mb-3">

        <div class="col-md-5">

            <div class="row g-2 align-items-center">

                <div class="col">

                    Tổng thuế GTGT của HHDV mua vào đủ điều kiện được khấu trừ thuế:

                </div>

                <div class="col-auto">

                    <input
                        type="text"
                        class="form-control form-control-sm text-end"
                        value="<?= number_format((float)$total['total_vat'], 0, ',', '.') ?>"
                        readonly
                    >

                </div>

            </div>

        </div>

    </div>


    <!-- ========================= -->
    <!-- CẢNH BÁO -->
    <!-- ========================= -->

    <div class="small text-danger mb-1">

        Có một số chứng từ không lên bảng kê do thiếu một trong các thông tin:
        Số hóa đơn, ngày hóa đơn, nhóm HHDV mua vào

    </div>


    <div class="small">

        <a href="#" class="text-primary fst-italic">
            Bấm vào đây để xem chi tiết
        </a>

    </div>

</div>