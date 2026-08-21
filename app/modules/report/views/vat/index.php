<div class="container-fluid py-4 mt-5">
    // TEST_VSCODE
    <style>
        .vat-report {
            background: #d7e6f5;
            border: 1px solid #999;
            padding: 8px;
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #111;
        }

        .vat-title {
            text-align: center;
            font-weight: bold;
            line-height: 1.3;
            margin-bottom: 5px;
        }

        .vat-title .title {
            font-size: 13px;
        }

        .vat-title .sub {
            font-size: 10px;
            font-weight: normal;
        }

        .vat-info {
            margin-bottom: 5px;
        }

        .vat-info table {
            width: 100%;
            border-collapse: collapse;
        }

        .vat-info td {
            padding: 1px 3px;
            vertical-align: middle;
        }

        .vat-input {
            width: 100%;
            height: 20px;
            border: 1px solid #999;
            background: #fff;
            font-size: 11px;
            padding: 1px 4px;
            box-sizing: border-box;
        }

        .vat-select {
            width: 100%;
            height: 20px;
            border: 1px solid #999;
            background: #fff;
            font-size: 11px;
        }

        .vat-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .vat-table th,
        .vat-table td {
            border: 1px solid #555;
            padding: 2px 3px;
            height: 19px;
            line-height: 1.15;
        }

        .vat-table th {
            text-align: center;
            font-weight: bold;
            background: #c7d9ea;
        }

        .vat-table .stt {
            width: 35px;
            text-align: center;
        }

        .vat-table .content {
            width: auto;
        }

        .vat-table .value {
            width: 180px;
        }

        .vat-table .tax {
            width: 110px;
        }

        .vat-table .code {
            width: 35px;
            text-align: center;
            font-size: 9px;
        }

        .vat-table .number {
            width: 125px;
            text-align: right;
        }

        .vat-number {
            width: 100%;
            height: 19px;
            border: 1px solid #999;
            background: #f4f4f4;
            text-align: right;
            padding: 1px 4px;
            font-size: 11px;
            box-sizing: border-box;
        }

        .vat-check {
            text-align: center;
        }

        .vat-section {
            font-weight: bold;
        }

        .vat-sign {
            margin-top: 5px;
        }

        .vat-sign table {
            width: 100%;
            border-collapse: collapse;
        }

        .vat-sign td {
            padding: 1px 3px;
        }

        .vat-date {
            text-align: right;
        }
    </style>


    <div class="vat-report">

        <!-- ========================= -->
        <!-- TIÊU ĐỀ -->
        <!-- ========================= -->

        <div class="vat-title">

            <div class="title">
                TỜ KHAI THUẾ GIÁ TRỊ GIA TĂNG
            </div>

            <div class="sub">
                (Mẫu số 01/GTGT)
            </div>

            <div class="sub">
                (Áp dụng đối với người nộp thuế tính thuế theo phương pháp khấu trừ
                có hoạt động sản xuất kinh doanh)
            </div>

            <div class="sub">
                [01]: Tờ khai có thời kỳ tính thuế: Hoạt động sản xuất kinh doanh
            </div>

        </div>


        <!-- ========================= -->
        <!-- THÔNG TIN -->
        <!-- ========================= -->

        <div class="vat-info">

            <table>

                <tr>
                    <td style="width:55px;">[02]</td>

                    <td style="width:80px;">
                        Kỳ tính thuế:
                    </td>

                    <td style="width:180px;">
                        Quý IV năm 2025
                    </td>

                    <td>
                        [03] Lần đầu
                    </td>
                </tr>

                <tr>
                    <td>[04]</td>

                    <td>
                        Tên người nộp thuế:
                    </td>

                    <td colspan="2">
                        <b>CÔNG TY TNHH MẠNH DŨNG SPORTS</b>
                    </td>
                </tr>

                <tr>
                    <td>[05]</td>

                    <td>
                        Mã số thuế:
                    </td>

                    <td>
                        <b>0901190162</b>
                    </td>

                    <td></td>
                </tr>

                <tr>
                    <td>[06]</td>

                    <td>
                        Tên đại lý thuế:
                    </td>

                    <td colspan="2">
                        <input class="vat-input">
                    </td>
                </tr>

                <tr>
                    <td>[07]</td>

                    <td>
                        Mã số thuế đại lý:
                    </td>

                    <td colspan="2">
                        <input class="vat-input">
                    </td>
                </tr>

                <tr>
                    <td>[08]</td>

                    <td>
                        Hợp đồng đại lý:
                    </td>

                    <td colspan="2">
                        <input class="vat-input">
                    </td>
                </tr>

                <tr>
                    <td>[09]</td>

                    <td colspan="3">
                        Tên đơn vị phụ thuộc/Địa điểm kinh doanh của hoạt động sản xuất kinh doanh khác tỉnh nơi đóng trụ sở chính:
                    </td>
                </tr>

                <tr>
                    <td>[10]</td>

                    <td>
                        Mã số thuế đơn vị phụ thuộc:
                    </td>

                    <td colspan="2">
                        <input class="vat-input">
                    </td>
                </tr>

                <tr>
                    <td>[11]</td>

                    <td colspan="3">
                        Địa chỉ nơi có hoạt động sản xuất kinh doanh khác tỉnh đóng trụ sở chính:
                    </td>
                </tr>

                <tr>
                    <td>[11a]</td>

                    <td>
                        Tỉnh/ Thành phố:
                    </td>

                    <td>
                        <select class="vat-select">
                            <option></option>
                        </select>
                    </td>

                    <td></td>
                </tr>

                <tr>
                    <td>[11b]</td>

                    <td>
                        Quận/Huyện:
                    </td>

                    <td>
                        <select class="vat-select">
                            <option></option>
                        </select>
                    </td>

                    <td></td>
                </tr>

                <tr>
                    <td>[11c]</td>

                    <td>
                        Phường/Xã:
                    </td>

                    <td>
                        <select class="vat-select">
                            <option></option>
                        </select>
                    </td>

                    <td></td>
                </tr>

            </table>

        </div>


        <!-- ========================= -->
        <!-- BẢNG CHÍNH -->
        <!-- ========================= -->

        <table class="vat-table">

            <thead>

                <tr>

                    <th class="stt">
                        STT
                    </th>

                    <th class="content">
                        Chỉ tiêu
                    </th>

                    <th class="value">
                        Giá trị HHDV<br>
                        (chưa có thuế GTGT)
                    </th>

                    <th class="tax">
                        Thuế GTGT
                    </th>

                </tr>

            </thead>


            <tbody>

                <!-- A -->

                <tr>
                    <td class="stt">
                        A
                    </td>

                    <td>
                        Không phát sinh hoạt động mua, bán trong kỳ (đánh dấu "X")
                    </td>

                    <td>
                        <span style="float:right;">[21]</span>

                        <div class="vat-check">
                            <input type="checkbox">
                        </div>
                    </td>

                    <td></td>
                </tr>


                <!-- B -->

                <tr>
                    <td class="stt">
                        B
                    </td>

                    <td>
                        Thuế GTGT còn được khấu trừ kỳ trước chuyển sang
                    </td>

                    <td></td>

                    <td>
                        <span class="code">[22]</span>

                        <input
                            class="vat-number"
                            value="1.399.854"
                            readonly
                        >
                    </td>
                </tr>


                <!-- C -->

                <tr>
                    <td class="stt">
                        C
                    </td>

                    <td>
                        Kê khai thuế GTGT phải nộp Ngân sách nhà nước
                    </td>

                    <td></td>

                    <td></td>
                </tr>


                <!-- I -->

                <tr>
                    <td class="stt">
                        I
                    </td>

                    <td class="vat-section">
                        Hàng hóa, dịch vụ mua vào trong kỳ
                    </td>

                    <td></td>

                    <td></td>
                </tr>


                <!-- 1 -->

                <tr>
                    <td class="stt">
                        1
                    </td>

                    <td>
                        Giá trị và thuế GTGT của hàng hóa, dịch vụ mua vào
                    </td>

                    <td>

                        <span class="code">[23]</span>

                        <input
                            class="vat-number"
                            value="65.326.572"
                            readonly
                        >

                    </td>

                    <td>

                        <span class="code">[24]</span>

                        <input
                            class="vat-number"
                            value="5.226.124"
                            readonly
                        >

                    </td>

                </tr>


                <!-- 1a -->

                <tr>
                    <td></td>

                    <td>
                        Trong đó: hàng hóa, dịch vụ nhập khẩu
                    </td>

                    <td>

                        <span class="code">[23a]</span>

                        <input
                            class="vat-number"
                            value="0"
                            readonly
                        >

                    </td>

                    <td></td>

                </tr>


                <!-- 2 -->

                <tr>
                    <td class="stt">
                        2
                    </td>

                    <td>
                        Tổng số thuế GTGT được khấu trừ kỳ này
                    </td>

                    <td></td>

                    <td>

                        <span class="code">[25]</span>

                        <input
                            class="vat-number"
                            value="1.946.568"
                            readonly
                        >

                    </td>

                </tr>


                <!-- II -->

                <tr>
                    <td class="stt">
                        II
                    </td>

                    <td class="vat-section">
                        Hàng hóa, dịch vụ bán ra trong kỳ
                    </td>

                    <td></td>

                    <td></td>

                </tr>


                <!-- 1 -->

                <tr>
                    <td>
                        1
                    </td>

                    <td>
                        Hàng hóa, dịch vụ bán ra không chịu thuế GTGT
                    </td>

                    <td>

                        <span class="code">[26]</span>

                        <input
                            class="vat-number"
                            value="0"
                            readonly
                        >

                    </td>

                    <td></td>

                </tr>


                <!-- 2 -->

                <tr>
                    <td>
                        2
                    </td>

                    <td>
                        Hàng hóa, dịch vụ bán ra chịu thuế GTGT ([27] = [29] + [30] + [32a]; [28] = [31] + [33])
                    </td>

                    <td>

                        <span class="code">[27]</span>

                        <input
                            class="vat-number"
                            value="15.960.000"
                            readonly
                        >

                    </td>

                    <td>

                        <span class="code">[28]</span>

                        <input
                            class="vat-number"
                            value="1.276.800"
                            readonly
                        >

                    </td>

                </tr>


                <!-- a -->

                <tr>
                    <td>
                        a
                    </td>

                    <td>
                        Hàng hóa, dịch vụ bán ra chịu thuế suất 0%
                    </td>

                    <td>

                        <span class="code">[29]</span>

                        <input
                            class="vat-number"
                            value="0"
                            readonly
                        >

                    </td>

                    <td>

                        <span class="code">[30]</span>

                        <input
                            class="vat-number"
                            value="0"
                            readonly
                        >

                    </td>

                </tr>


                <!-- b -->

                <tr>
                    <td>
                        b
                    </td>

                    <td>
                        Hàng hóa, dịch vụ bán ra chịu thuế suất 5%
                    </td>

                    <td>

                        <span class="code">[30]</span>

                        <input
                            class="vat-number"
                            value="0"
                            readonly
                        >

                    </td>

                    <td>

                        <span class="code">[31]</span>

                        <input
                            class="vat-number"
                            value="0"
                            readonly
                        >

                    </td>

                </tr>


                <!-- c -->

                <tr>
                    <td>
                        c
                    </td>

                    <td>
                        Hàng hóa, dịch vụ bán ra chịu thuế suất 10%
                    </td>

                    <td>

                        <span class="code">[32]</span>

                        <input
                            class="vat-number"
                            value="15.960.000"
                            readonly
                        >

                    </td>

                    <td>

                        <span class="code">[33]</span>

                        <input
                            class="vat-number"
                            value="1.276.800"
                            readonly
                        >

                    </td>

                </tr>


                <!-- 3 -->

                <tr>
                    <td>
                        3
                    </td>

                    <td>
                        Tổng doanh thu và thuế GTGT của HHDV bán ra ([34] = [26] + [27]; [35] = [28])
                    </td>

                    <td>

                        <span class="code">[34]</span>

                        <input
                            class="vat-number"
                            value="15.960.000"
                            readonly
                        >

                    </td>

                    <td>

                        <span class="code">[35]</span>

                        <input
                            class="vat-number"
                            value="1.276.800"
                            readonly
                        >

                    </td>

                </tr>


                <!-- 4 -->

                <tr>
                    <td>
                        4
                    </td>

                    <td>
                        Thuế GTGT phát sinh trong kỳ ([36] = [35] - [25])
                    </td>

                    <td></td>

                    <td>

                        <span class="code">[36]</span>

                        <input
                            class="vat-number"
                            value="(669.768)"
                            readonly
                        >

                    </td>

                </tr>


                <!-- III -->

                <tr>
                    <td>
                        III
                    </td>

                    <td class="vat-section">
                        Điều chỉnh tăng, giảm thuế GTGT còn được khấu trừ của các kỳ trước
                    </td>

                    <td></td>

                    <td></td>

                </tr>


                <!-- III.1 -->

                <tr>
                    <td>
                        1
                    </td>

                    <td>
                        Điều chỉnh giảm
                    </td>

                    <td></td>

                    <td>

                        <span class="code">[37]</span>

                        <input
                            class="vat-number"
                            value="0"
                            readonly
                        >

                    </td>

                </tr>


                <!-- III.2 -->

                <tr>
                    <td>
                        2
                    </td>

                    <td>
                        Điều chỉnh tăng
                    </td>

                    <td></td>

                    <td>

                        <span class="code">[38]</span>

                        <input
                            class="vat-number"
                            value="0"
                            readonly
                        >

                    </td>

                </tr>


                <!-- IV -->

                <tr>
                    <td>
                        IV
                    </td>

                    <td class="vat-section">
                        Thuế GTGT đã trả theo bản giao được khấu trừ trong kỳ
                    </td>

                    <td></td>

                    <td></td>

                </tr>


                <!-- IV.1 -->

                <tr>
                    <td>
                        1
                    </td>

                    <td>
                        Xác định nghĩa vụ thuế GTGT phải nộp trong kỳ
                    </td>

                    <td></td>

                    <td>

                        <span class="code">[39a]</span>

                        <input
                            class="vat-number"
                            value="0"
                            readonly
                        >

                    </td>

                </tr>


                <!-- V -->

                <tr>
                    <td>
                        V
                    </td>

                    <td class="vat-section">
                        Thuế GTGT nhận bàn giao đầu vào được khấu trừ trong kỳ
                    </td>

                    <td></td>

                    <td></td>

                </tr>


                <!-- V.1 -->

                <tr>
                    <td>
                        1
                    </td>

                    <td>
                        Thuế GTGT phải nộp của hoạt động sản xuất kinh doanh trong kỳ
                        ([40a] = [36] - [22] + [37] - [38] - [39a] &gt; 0)
                    </td>

                    <td></td>

                    <td>

                        <span class="code">[40a]</span>

                        <input
                            class="vat-number"
                            value="0"
                            readonly
                        >

                    </td>

                </tr>


                <!-- V.2 -->

                <tr>
                    <td>
                        2
                    </td>

                    <td>
                        Thuế GTGT mua vào của dự án đầu tư được khấu trừ vào phân bổ của hoạt động sản xuất kinh doanh trong kỳ
                    </td>

                    <td></td>

                    <td>

                        <span class="code">[40b]</span>

                        <input
                            class="vat-number"
                            value="0"
                            readonly
                        >

                    </td>

                </tr>


                <!-- V.3 -->

                <tr>
                    <td>
                        3
                    </td>

                    <td>
                        Thuế GTGT còn phải nộp trong kỳ ([40] = [40a] - [40b])
                    </td>

                    <td></td>

                    <td>

                        <span class="code">[40]</span>

                        <input
                            class="vat-number"
                            value="0"
                            readonly
                        >

                    </td>

                </tr>


                <!-- V.4 -->

                <tr>
                    <td>
                        4
                    </td>

                    <td>
                        Thuế GTGT chưa khấu trừ hết kỳ này ([41] = [36] - [22] + [37] - [38] - [39a] &lt; 0)
                    </td>

                    <td></td>

                    <td>

                        <span class="code">[41]</span>

                        <input
                            class="vat-number"
                            value="2.069.622"
                            readonly
                        >

                    </td>

                </tr>


                <!-- V.4.1 -->

                <tr>
                    <td>
                        4.1
                    </td>

                    <td>
                        Thuế GTGT đề nghị hoàn
                    </td>

                    <td></td>

                    <td>

                        <span class="code">[42]</span>

                        <input
                            class="vat-number"
                            value="0"
                            readonly
                        >

                    </td>

                </tr>


                <!-- V.4.2 -->

                <tr>
                    <td>
                        4.2
                    </td>

                    <td>
                        Thuế GTGT còn được khấu trừ chuyển kỳ sau ([43] = [41] - [42])
                    </td>

                    <td></td>

                    <td>

                        <span class="code">[43]</span>

                        <input
                            class="vat-number"
                            value="2.069.622"
                            readonly
                        >

                    </td>

                </tr>

            </tbody>

        </table>


        <!-- ========================= -->
        <!-- CHỮ KÝ -->
        <!-- ========================= -->

        <div class="vat-sign">

            <table>

                <tr>

                    <td style="width:50%;">

                        <b>NGƯỜI NỘP THUẾ</b>

                        <br>

                        Họ và tên:

                        <input
                            class="vat-input"
                            style="width:160px; display:inline-block;"
                        >

                        <br>

                        Chức danh:

                        <input
                            class="vat-input"
                            style="width:160px; display:inline-block;"
                        >

                    </td>


                    <td style="width:50%;">

                        <div class="vat-date">

                            <b>Người ký:</b>

                            <input
                                class="vat-input"
                                style="width:160px; display:inline-block;"
                            >

                            <br>

                            <b>Ngày ký:</b>

                            <input
                                class="vat-input"
                                style="width:160px; display:inline-block;"
                                value="17/08/2026"
                            >

                        </div>

                    </td>

                </tr>

            </table>

        </div>

    </div>

</div>