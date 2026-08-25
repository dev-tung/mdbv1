<div class="container py-4 mt-5">

    <?php include __DIR__ . '/navbar.php'; ?>

    <!-- ========================= -->
    <!-- TIÊU ĐỀ -->
    <!-- ========================= -->

    <div class="text-center mb-3">

        <h5 class="fw-bold mb-1">
            BẢNG KÊ HÓA ĐƠN, CHỨNG TỪ HÀNG HÓA, DỊCH VỤ BÁN RA
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
                        Hóa đơn, chứng từ bán ra
                    </th>

                    <th rowspan="2" class="align-middle">
                        Tên người mua
                    </th>

                    <th rowspan="2" class="align-middle">
                        Mã số thuế người mua
                    </th>

                    <th rowspan="2" class="align-middle">
                        Doanh thu chưa có thuế GTGT
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
                <!-- 1. KHÔNG CHỊU THUẾ -->
                <!-- ===================================================== -->

                <tr class="fw-bold">

                    <td colspan="11">
                        1. Hàng hóa, dịch vụ không chịu thuế giá trị gia tăng (GTGT):
                    </td>

                </tr>


                <tr>

                    <td colspan="7">
                        <span class="fw-bold">
                            Tổng
                        </span>
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
                        <span class="fw-bold">
                            Tổng cộng
                        </span>
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
                <!-- 2. THUẾ SUẤT 0% -->
                <!-- ===================================================== -->

                <tr class="fw-bold">

                    <td colspan="11">
                        2. Hàng hóa, dịch vụ chịu thuế suất thuế GTGT 0%:
                    </td>

                </tr>


                <?php
                $totalVat0Value = 0;
                $totalVat0 = 0;
                ?>

                <?php foreach (($vatPl011[0] ?? []) as $index => $row): ?>

                    <?php if ((float) ($row['vat_rate'] ?? 0) === 0.0): ?>

                        <?php
                        $totalVat0Value += (float) ($row['value'] ?? 0);
                        $totalVat0 += (float) ($row['vat'] ?? 0);
                        ?>

                        <tr>

                            <td class="text-center">
                                <?= $index + 1 ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($row['invoice_form'] ?? '') ?>
                            </td>

                            <td class="text-center text-primary">
                                <?= htmlspecialchars($row['invoice_symbol'] ?? '') ?>
                            </td>

                            <td>
                                <a
                                    href="#"
                                    class="text-decoration-none"
                                >
                                    <?= htmlspecialchars($row['invoice_number'] ?? '') ?>
                                </a>
                            </td>

                            <td>

                                <input
                                    type="date"
                                    class="form-control form-control-sm"
                                    value="<?= htmlspecialchars($row['invoice_date'] ?? '') ?>"
                                    readonly
                                >

                            </td>

                            <td>

                                <input
                                    type="text"
                                    class="form-control form-control-sm"
                                    value="<?= htmlspecialchars($row['customer_name'] ?? '') ?>"
                                    readonly
                                >

                            </td>

                            <td>

                                <input
                                    type="text"
                                    class="form-control form-control-sm"
                                    value="<?= htmlspecialchars($row['customer_tax_code'] ?? '') ?>"
                                    readonly
                                >

                            </td>

                            <td>

                                <input
                                    type="text"
                                    class="form-control form-control-sm text-end"
                                    value="<?= number_format((float) ($row['value'] ?? 0), 0, ',', '.') ?>"
                                    readonly
                                >

                            </td>

                            <td>

                                <input
                                    type="text"
                                    class="form-control form-control-sm text-end"
                                    value="<?= number_format((float) ($row['vat'] ?? 0), 0, ',', '.') ?>"
                                    readonly
                                >

                            </td>

                            <td>

                                <input
                                    type="text"
                                    class="form-control form-control-sm"
                                    value="<?= htmlspecialchars($row['note'] ?? '') ?>"
                                    readonly
                                >

                            </td>

                            <td></td>

                        </tr>

                    <?php endif; ?>

                <?php endforeach; ?>


                <!-- TỔNG 0% -->

                <tr class="fw-bold">

                    <td colspan="7">
                        Tổng
                    </td>

                    <td>

                        <input
                            type="text"
                            class="form-control form-control-sm text-end fw-bold"
                            value="<?= number_format($totalVat0Value, 0, ',', '.') ?>"
                            readonly
                        >

                    </td>

                    <td>

                        <input
                            type="text"
                            class="form-control form-control-sm text-end fw-bold"
                            value="<?= number_format($totalVat0, 0, ',', '.') ?>"
                            readonly
                        >

                    </td>

                    <td colspan="2"></td>

                </tr>


                <tr class="fw-bold">

                    <td colspan="7">
                        Tổng cộng
                    </td>

                    <td>

                        <input
                            type="text"
                            class="form-control form-control-sm text-end fw-bold"
                            value="<?= number_format($totalVat0Value, 0, ',', '.') ?>"
                            readonly
                        >

                    </td>

                    <td>

                        <input
                            type="text"
                            class="form-control form-control-sm text-end fw-bold"
                            value="<?= number_format($totalVat0, 0, ',', '.') ?>"
                            readonly
                        >

                    </td>

                    <td colspan="2"></td>

                </tr>


                <!-- ===================================================== -->
                <!-- 3. THUẾ SUẤT 5% -->
                <!-- ===================================================== -->

                <tr class="fw-bold">

                    <td colspan="11">
                        3. Hàng hóa, dịch vụ chịu thuế suất thuế GTGT 5%:
                    </td>

                </tr>


                <?php
                $totalVat5Value = 0;
                $totalVat5 = 0;
                ?>

                <?php foreach (($vatPl011[0] ?? []) as $index => $row): ?>

                    <?php if ((float) ($row['vat_rate'] ?? 0) === 5.0): ?>

                        <?php
                        $totalVat5Value += (float) ($row['value'] ?? 0);
                        $totalVat5 += (float) ($row['vat'] ?? 0);
                        ?>

                        <tr>

                            <td class="text-center">
                                <?= $index + 1 ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($row['invoice_form'] ?? '') ?>
                            </td>

                            <td class="text-center text-primary">
                                <?= htmlspecialchars($row['invoice_symbol'] ?? '') ?>
                            </td>

                            <td>
                                <a href="#" class="text-decoration-none">
                                    <?= htmlspecialchars($row['invoice_number'] ?? '') ?>
                                </a>
                            </td>

                            <td>

                                <input
                                    type="date"
                                    class="form-control form-control-sm"
                                    value="<?= htmlspecialchars($row['invoice_date'] ?? '') ?>"
                                    readonly
                                >

                            </td>

                            <td>

                                <input
                                    type="text"
                                    class="form-control form-control-sm"
                                    value="<?= htmlspecialchars($row['customer_name'] ?? '') ?>"
                                    readonly
                                >

                            </td>

                            <td>

                                <input
                                    type="text"
                                    class="form-control form-control-sm"
                                    value="<?= htmlspecialchars($row['customer_tax_code'] ?? '') ?>"
                                    readonly
                                >

                            </td>

                            <td>

                                <input
                                    type="text"
                                    class="form-control form-control-sm text-end"
                                    value="<?= number_format((float) ($row['value'] ?? 0), 0, ',', '.') ?>"
                                    readonly
                                >

                            </td>

                            <td>

                                <input
                                    type="text"
                                    class="form-control form-control-sm text-end"
                                    value="<?= number_format((float) ($row['vat'] ?? 0), 0, ',', '.') ?>"
                                    readonly
                                >

                            </td>

                            <td>

                                <input
                                    type="text"
                                    class="form-control form-control-sm"
                                    value="<?= htmlspecialchars($row['note'] ?? '') ?>"
                                    readonly
                                >

                            </td>

                            <td></td>

                        </tr>

                    <?php endif; ?>

                <?php endforeach; ?>


                <tr class="fw-bold">

                    <td colspan="7">
                        Tổng
                    </td>

                    <td>

                        <input
                            type="text"
                            class="form-control form-control-sm text-end fw-bold"
                            value="<?= number_format($totalVat5Value, 0, ',', '.') ?>"
                            readonly
                        >

                    </td>

                    <td>

                        <input
                            type="text"
                            class="form-control form-control-sm text-end fw-bold"
                            value="<?= number_format($totalVat5, 0, ',', '.') ?>"
                            readonly
                        >

                    </td>

                    <td colspan="2"></td>

                </tr>


                <tr class="fw-bold">

                    <td colspan="7">
                        Tổng cộng
                    </td>

                    <td>

                        <input
                            type="text"
                            class="form-control form-control-sm text-end fw-bold"
                            value="<?= number_format($totalVat5Value, 0, ',', '.') ?>"
                            readonly
                        >

                    </td>

                    <td>

                        <input
                            type="text"
                            class="form-control form-control-sm text-end fw-bold"
                            value="<?= number_format($totalVat5, 0, ',', '.') ?>"
                            readonly
                        >

                    </td>

                    <td colspan="2"></td>

                </tr>


                <!-- ===================================================== -->
                <!-- 4. THUẾ SUẤT 10% -->
                <!-- ===================================================== -->

                <tr class="fw-bold">

                    <td colspan="11">
                        4. Hàng hóa, dịch vụ chịu thuế suất thuế GTGT 10%:
                    </td>

                </tr>


                <?php
                $totalVat10Value = 0;
                $totalVat10 = 0;
                ?>

                <?php foreach (($vatPl011[0] ?? []) as $index => $row): ?>

                    <?php if ((float) ($row['vat_rate'] ?? 0) === 10.0): ?>

                        <?php
                        $totalVat10Value += (float) ($row['value'] ?? 0);
                        $totalVat10 += (float) ($row['vat'] ?? 0);
                        ?>

                        <tr>

                            <td class="text-center">
                                <?= $index + 1 ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($row['invoice_form'] ?? '') ?>
                            </td>

                            <td class="text-center text-primary">
                                <?= htmlspecialchars($row['invoice_symbol'] ?? '') ?>
                            </td>

                            <td>
                                <a href="#" class="text-decoration-none">
                                    <?= htmlspecialchars($row['invoice_number'] ?? '') ?>
                                </a>
                            </td>

                            <td>

                                <input
                                    type="date"
                                    class="form-control form-control-sm"
                                    value="<?= htmlspecialchars($row['invoice_date'] ?? '') ?>"
                                    readonly
                                >

                            </td>

                            <td>

                                <input
                                    type="text"
                                    class="form-control form-control-sm"
                                    value="<?= htmlspecialchars($row['customer_name'] ?? '') ?>"
                                    readonly
                                >

                            </td>

                            <td>

                                <input
                                    type="text"
                                    class="form-control form-control-sm"
                                    value="<?= htmlspecialchars($row['customer_tax_code'] ?? '') ?>"
                                    readonly
                                >

                            </td>

                            <td>

                                <input
                                    type="text"
                                    class="form-control form-control-sm text-end"
                                    value="<?= number_format((float) ($row['value'] ?? 0), 0, ',', '.') ?>"
                                    readonly
                                >

                            </td>

                            <td>

                                <input
                                    type="text"
                                    class="form-control form-control-sm text-end"
                                    value="<?= number_format((float) ($row['vat'] ?? 0), 0, ',', '.') ?>"
                                    readonly
                                >

                            </td>

                            <td>

                                <input
                                    type="text"
                                    class="form-control form-control-sm"
                                    value="<?= htmlspecialchars($row['note'] ?? '') ?>"
                                    readonly
                                >

                            </td>

                            <td></td>

                        </tr>

                    <?php endif; ?>

                <?php endforeach; ?>


                <!-- TỔNG 10% -->

                <tr class="fw-bold">

                    <td colspan="7">
                        Tổng
                    </td>

                    <td>

                        <input
                            type="text"
                            class="form-control form-control-sm text-end fw-bold"
                            value="<?= number_format($totalVat10Value, 0, ',', '.') ?>"
                            readonly
                        >

                    </td>

                    <td>

                        <input
                            type="text"
                            class="form-control form-control-sm text-end fw-bold"
                            value="<?= number_format($totalVat10, 0, ',', '.') ?>"
                            readonly
                        >

                    </td>

                    <td colspan="2"></td>

                </tr>


                <!-- TỔNG CỘNG -->

                <?php
                $grandValue = $totalVat0Value
                            + $totalVat5Value
                            + $totalVat10Value;

                $grandVat = $totalVat0
                        + $totalVat5
                        + $totalVat10;
                ?>

                <tr class="fw-bold">

                    <td colspan="7">
                        Tổng cộng
                    </td>

                    <td>

                        <input
                            type="text"
                            class="form-control form-control-sm text-end fw-bold"
                            value="<?= number_format($grandValue, 0, ',', '.') ?>"
                            readonly
                        >

                    </td>

                    <td>

                        <input
                            type="text"
                            class="form-control form-control-sm text-end fw-bold"
                            value="<?= number_format($grandVat, 0, ',', '.') ?>"
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
                    Tổng doanh thu hàng hóa, dịch vụ bán ra chịu thuế GTGT:
                </div>

                <div class="col-auto">

                    <input
                        type="text"
                        class="form-control form-control-sm text-end"
                        value="<?= number_format($grandValue, 0, ',', '.') ?>"
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
                    Tổng số thuế GTGT của hàng hóa, dịch vụ bán ra:
                </div>

                <div class="col-auto">

                    <input
                        type="text"
                        class="form-control form-control-sm text-end"
                        value="<?= number_format($grandVat, 0, ',', '.') ?>"
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
        Số hóa đơn, ngày hóa đơn, % thuế GTGT

    </div>


    <div class="small">

        <a href="#" class="text-primary fst-italic">
            Bấm vào đây để xem chi tiết
        </a>

    </div>


</div>