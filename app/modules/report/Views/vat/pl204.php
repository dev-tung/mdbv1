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

                <?php

                $items_1 = [

                    [1,  'Áo Thun Nam Yonex cổ tròn TM2B9WZ...', '600.000',   '76.000'],
                    [2,  'Áo Thun Nam Yonex cổ tròn TM2B9WZ...', '296.110',   '16.620'],
                    [3,  'Áo Thun Nam Yonex cổ tròn TM2B9WZ...', '1.752.392', '140.191'],
                    [4,  'Mũ Yonex WA...',                         '511.181',   '40.911'],
                    [5,  'Mũ Yonex WA...',                         '478.991',   '38.319'],
                    [6,  'VỢT CƯỚC KUNAI 20F',                    '256.444',   '20.516'],
                    [7,  'Dây cước đan vợt Yonex BG...',          '2.400.000', '230.000'],
                    [8,  'Dây cước đan vợt Yonex BG...',          '1.612.500', '145.625'],
                    [9,  'Dây cước đan vợt Yonex BG...',          '1.090.000', '87.200'],
                    [10, 'Dây cước đan vợt Yonex BG...',          '2.507.950', '200.000'],
                    [11, 'Dây cước đan vợt Yonex BG 66',          '735.000',   '58.800'],
                    [12, 'Dịch vụ cầu lông 3 Nam',                 '1.111.111', '88.889'],
                    [13, 'Phụ Cầu Lông Yonex AC...',               '453.056',   '36.244'],
                    [14, 'P-AVT1475 - Dùng vợt cầu lông Win...',  '2.072.000', '165.760'],
                    [15, 'P-AVT1001 - Giày cầu lông',             '2.225.625', '178.050'],
                    [16, 'Quần cầu lông Yonex...',                '102.816',   '8.225'],
                    [17, 'Túi đựng vợt NP Dm 2/8',               '42.000',    '3.360'],
                    [18, 'Vợt Nam/Nữ Yonex...',                   '323.416',   '25.873'],
                    [19, 'Vợt Nam/Nữ Yonex...',                   '191.232',   '15.298'],
                    [20, 'Vợt Cầu Lông Yonex...',                 '1.999.910', '159.993'],
                    [21, 'Vợt Cầu Lông Yonex...',                 '1.720.000', '137.600'],
                    [22, 'Vợt Cầu Lông Yonex...',                 '4.049.167', '323.933'],
                    [23, 'Vợt Cầu Lông Yonex...',                 '1.483.637', '118.691'],
                    [24, 'Vợt Cầu Lông Yonex...',                 '1.483.637', '118.691'],
                    [25, 'Vợt pickleball Hyperion N 16mm...',     '9.072.000', '725.760'],
                    [26, 'Vợt pickleball Agassi Pro...',           '4.300.000', '344.000'],
                    [27, 'Vợt pickleball Recurve 13...',           '7.100.000', '568.000'],
                    [28, 'Vợt pickleball Recurve N...',            '8.072.000', '645.760'],

                ];

                foreach ($items_1 as $item):
                ?>

                    <tr>

                        <td class="text-center">
                            <?= $item[0] ?>
                        </td>

                        <td class="p-1">
                            <input
                                type="text"
                                class="form-control form-control-sm"
                                value="<?= htmlspecialchars($item[1]) ?>"
                            >
                        </td>

                        <td class="p-1">
                            <input
                                type="text"
                                class="form-control form-control-sm text-end"
                                value="<?= $item[2] ?>"
                            >
                        </td>

                        <td class="p-1">
                            <input
                                type="text"
                                class="form-control form-control-sm text-end"
                                value="<?= $item[3] ?>"
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
                            value="65.326.572"
                            readonly
                        >

                    </td>

                    <td class="p-1">

                        <input
                            type="text"
                            class="form-control form-control-sm text-end fw-bold"
                            value="5.226.124"
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
                        của hàng hóa, dịch vụ bán ra
                    </th>

                </tr>

                <tr>
                </tr>

                <tr>

                    <th>(1)</th>

                    <th>(2)</th>

                    <th>(3)</th>

                    <th>(4)</th>

                    <th>(5) = (3) × (4)</th>

                </tr>

            </thead>


            <tbody>

                <?php

                $items_2 = [

                    [1,  'Vợt Cầu Lông Yonex...',                    '4.500.000', '10', '0', '450.000'],
                    [2,  'Dây cước đan vợt Yonex...',              '2.700.000', '10', '0', '270.000'],
                    [3,  'Vợt Nam/Nữ Yonex...',                    '110.000',   '10', '0', '11.000'],
                    [4,  'Quần cầu lông...',                         '130.000',   '10', '0', '13.000'],
                    [5,  'P-AVT... - Giày cầu lông',                '700.000',   '10', '0', '70.000'],
                    [6,  'Vợt cầu lông cho chàng trai...',           '1.350.000', '10', '0', '135.000'],
                    [7,  'P-AVT... - Dùng vợt cầu lông...',         '1.000.000', '10', '0', '100.000'],
                    [8,  'P-AVT1001 - Giày cầu lông',               '2.460.000', '10', '0', '246.000'],
                    [9,  'Vợt Nam/Nữ Yonex...',                    '100.000',   '10', '0', '10.000'],
                    [10, 'Áo Thun Nam Yonex cổ tròn...',            '1.950.000', '10', '0', '195.000'],

                ];

                foreach ($items_2 as $item):
                ?>

                    <tr>

                        <td class="text-center">
                            <?= $item[0] ?>
                        </td>

                        <td class="p-1">

                            <input
                                type="text"
                                class="form-control form-control-sm"
                                value="<?= htmlspecialchars($item[1]) ?>"
                            >

                        </td>

                        <td class="p-1">

                            <input
                                type="text"
                                class="form-control form-control-sm text-end"
                                value="<?= $item[2] ?>"
                            >

                        </td>

                        <td class="text-end">
                            <?= $item[3] ?>
                        </td>

                        <td class="p-1">

                            <input
                                type="text"
                                class="form-control form-control-sm text-end"
                                value="<?= $item[5] ?>"
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
                            value="15.958.000"
                            readonly
                        >

                    </td>

                    <td></td>

                    <td class="p-1">

                        <input
                            type="text"
                            class="form-control form-control-sm text-end fw-bold"
                            value="219.320"
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

    <div class="small mt-1">

        <span>
            Tổng tiền thuế GTGT:
        </span>

        <input
            type="text"
            class="form-control form-control-sm d-inline-block text-end"
            value="5.226.124"
            readonly
        >

        <span>
            đồng
        </span>

    </div>

</div>