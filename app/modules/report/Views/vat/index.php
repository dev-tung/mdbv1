<div class="container py-4 mt-5">
  
  <?php include __DIR__ . '/navbar.php'; ?>

  <!-- ========================= -->
  <!-- TIÊU ĐỀ -->
  <!-- ========================= -->

  <div class="text-center mb-3">

    <h5 class="fw-bold mb-1">
      TỜ KHAI THUẾ GIÁ TRỊ GIA TĂNG
    </h5>

    <div class="small text-muted">
      (Mẫu số 01/GTGT)
    </div>

    <div class="small text-muted">
      (Áp dụng đối với người nộp thuế tính thuế theo phương pháp khấu trừ
      có hoạt động sản xuất kinh doanh)
    </div>

    <div class="small text-muted">
      [01]: Tờ khai có thời kỳ tính thuế: Hoạt động sản xuất kinh doanh
    </div>

  </div>


  <!-- ========================= -->
  <!-- THÔNG TIN -->
  <!-- ========================= -->

  <div class="card mb-3">

    <div class="card-body p-3">

      <div class="row g-2 align-items-center">

        <div class="col-md-6">
          <div class="row g-2 align-items-center">
            <label class="col-md-4 col-form-label col-form-label-sm">
              [02] Kỳ tính thuế
            </label>

            <div class="col-md-8">
              <input
                type="text"
                class="form-control form-control-sm"
                value="Quý IV năm 2025"
                readonly
              >
            </div>
          </div>
        </div>

        <div class="col-md-6">
          <div class="form-check form-check-inline">
            <input
              class="form-check-input"
              type="checkbox"
              id="tax-first-declaration"
            >

            <label
              class="form-check-label small"
              for="tax-first-declaration"
            >
              [03] Lần đầu
            </label>
          </div>
        </div>


        <div class="col-12">
          <div class="row g-2 align-items-center">

            <label class="col-md-2 col-form-label col-form-label-sm">
              [04] Tên người nộp thuế
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


        <div class="col-md-6">

          <div class="row g-2 align-items-center">

            <label class="col-md-4 col-form-label col-form-label-sm">
              [05] Mã số thuế
            </label>

            <div class="col-md-8">

              <input
                type="text"
                class="form-control form-control-sm"
                value="0901190162"
                readonly
              >

            </div>

          </div>

        </div>


        <div class="col-md-6"></div>


        <div class="col-12">

          <div class="row g-2 align-items-center">

            <label class="col-md-2 col-form-label col-form-label-sm">
              [06] Tên đại lý thuế
            </label>

            <div class="col-md-10">

              <input
                type="text"
                class="form-control form-control-sm"
              >

            </div>

          </div>

        </div>


        <div class="col-12">

          <div class="row g-2 align-items-center">

            <label class="col-md-2 col-form-label col-form-label-sm">
              [07] Mã số thuế đại lý
            </label>

            <div class="col-md-10">

              <input
                type="text"
                class="form-control form-control-sm"
              >

            </div>

          </div>

        </div>


        <div class="col-12">

          <div class="row g-2 align-items-center">

            <label class="col-md-2 col-form-label col-form-label-sm">
              [08] Hợp đồng đại lý
            </label>

            <div class="col-md-10">

              <input
                type="text"
                class="form-control form-control-sm"
              >

            </div>

          </div>

        </div>


        <div class="col-12">

          <div class="small fw-bold">
            [09] Tên đơn vị phụ thuộc/Địa điểm kinh doanh của hoạt động sản xuất
            kinh doanh khác tỉnh nơi đóng trụ sở chính
          </div>

        </div>


        <div class="col-12">

          <div class="row g-2 align-items-center">

            <label class="col-md-2 col-form-label col-form-label-sm">
              [10] Mã số thuế đơn vị phụ thuộc
            </label>

            <div class="col-md-10">

              <input
                type="text"
                class="form-control form-control-sm"
              >

            </div>

          </div>

        </div>


        <div class="col-12">

          <div class="small fw-bold">
            [11] Địa chỉ nơi có hoạt động sản xuất kinh doanh khác tỉnh
            đóng trụ sở chính
          </div>

        </div>


        <div class="col-md-4">

          <label class="form-label form-label-sm">
            [11a] Tỉnh/ Thành phố
          </label>

          <select class="form-select form-select-sm">
            <option></option>
          </select>

        </div>


        <div class="col-md-4">

          <label class="form-label form-label-sm">
            [11b] Quận/Huyện
          </label>

          <select class="form-select form-select-sm">
            <option></option>
          </select>

        </div>


        <div class="col-md-4">

          <label class="form-label form-label-sm">
            [11c] Phường/Xã
          </label>

          <select class="form-select form-select-sm">
            <option></option>
          </select>

        </div>

      </div>

    </div>

  </div>


  <!-- ========================= -->
  <!-- BẢNG CHÍNH -->
  <!-- ========================= -->

  <div class="table-responsive">

    <table class="table table-sm table-bordered align-middle">

      <thead class="table-light">

        <tr>

          <th class="text-center">
            STT
          </th>

          <th>
            Chỉ tiêu
          </th>

          <th class="text-end">
            Giá trị HHDV<br>
            (chưa có thuế GTGT)
          </th>

          <th class="text-end">
            Thuế GTGT
          </th>

        </tr>

      </thead>


      <tbody>

        <!-- A -->

        <tr>

          <td class="text-center">
            A
          </td>

          <td>
            Không phát sinh hoạt động mua, bán trong kỳ
            (đánh dấu "X")
          </td>

          <td>

            <div class="d-flex justify-content-between align-items-center">

              <span class="small">
                [21]
              </span>

              <input
                type="checkbox"
                class="form-check-input m-0"
              >

            </div>

          </td>

          <td></td>

        </tr>


        <!-- B -->

        <tr>

          <td class="text-center">
            B
          </td>

          <td>
            Thuế GTGT còn được khấu trừ kỳ trước chuyển sang
          </td>

          <td></td>

          <td>

            <div class="d-flex gap-2 align-items-center">

              <span class="small text-nowrap">
                [22]
              </span>

              <input
                type="text"
                class="form-control form-control-sm text-end"
                value="1.399.854"
                readonly
              >

            </div>

          </td>

        </tr>


        <!-- C -->

        <tr>

          <td class="text-center">
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

          <td class="text-center fw-bold">
            I
          </td>

          <td class="fw-bold">
            Hàng hóa, dịch vụ mua vào trong kỳ
          </td>

          <td></td>

          <td></td>

        </tr>


        <!-- 1 -->

        <tr>

          <td class="text-center">
            1
          </td>

          <td>
            Giá trị và thuế GTGT của hàng hóa, dịch vụ mua vào
          </td>

          <td>

            <div class="d-flex gap-2 align-items-center">

              <span class="small text-nowrap">
                [23]
              </span>

              <input
                type="text"
                class="form-control form-control-sm text-end"
                value="65.326.572"
                readonly
              >

            </div>

          </td>

          <td>

            <div class="d-flex gap-2 align-items-center">

              <span class="small text-nowrap">
                [24]
              </span>

              <input
                type="text"
                class="form-control form-control-sm text-end"
                value="5.226.124"
                readonly
              >

            </div>

          </td>

        </tr>


        <!-- 1a -->

        <tr>

          <td></td>

          <td>
            Trong đó: hàng hóa, dịch vụ nhập khẩu
          </td>

          <td>

            <div class="d-flex gap-2 align-items-center">

              <span class="small text-nowrap">
                [23a]
              </span>

              <input
                type="text"
                class="form-control form-control-sm text-end"
                value="0"
                readonly
              >

            </div>

          </td>

          <td></td>

        </tr>


        <!-- 2 -->

        <tr>

          <td class="text-center">
            2
          </td>

          <td>
            Tổng số thuế GTGT được khấu trừ kỳ này
          </td>

          <td></td>

          <td>

            <div class="d-flex gap-2 align-items-center">

              <span class="small text-nowrap">
                [25]
              </span>

              <input
                type="text"
                class="form-control form-control-sm text-end"
                value="1.946.568"
                readonly
              >

            </div>

          </td>

        </tr>


        <!-- II -->

        <tr>

          <td class="text-center fw-bold">
            II
          </td>

          <td class="fw-bold">
            Hàng hóa, dịch vụ bán ra trong kỳ
          </td>

          <td></td>

          <td></td>

        </tr>


        <!-- 1 -->

        <tr>

          <td class="text-center">
            1
          </td>

          <td>
            Hàng hóa, dịch vụ bán ra không chịu thuế GTGT
          </td>

          <td>

            <div class="d-flex gap-2 align-items-center">

              <span class="small text-nowrap">
                [26]
              </span>

              <input
                type="text"
                class="form-control form-control-sm text-end"
                value="0"
                readonly
              >

            </div>

          </td>

          <td></td>

        </tr>


        <!-- 2 -->

        <tr>

          <td class="text-center">
            2
          </td>

          <td>
            Hàng hóa, dịch vụ bán ra chịu thuế GTGT
            ([27] = [29] + [30] + [32a]; [28] = [31] + [33])
          </td>

          <td>

            <div class="d-flex gap-2 align-items-center">

              <span class="small text-nowrap">
                [27]
              </span>

              <input
                type="text"
                class="form-control form-control-sm text-end"
                value="15.960.000"
                readonly
              >

            </div>

          </td>

          <td>

            <div class="d-flex gap-2 align-items-center">

              <span class="small text-nowrap">
                [28]
              </span>

              <input
                type="text"
                class="form-control form-control-sm text-end"
                value="1.276.800"
                readonly
              >

            </div>

          </td>

        </tr>


        <!-- a -->

        <tr>

          <td class="text-center">
            a
          </td>

          <td>
            Hàng hóa, dịch vụ bán ra chịu thuế suất 0%
          </td>

          <td>

            <div class="d-flex gap-2 align-items-center">

              <span class="small text-nowrap">
                [29]
              </span>

              <input
                type="text"
                class="form-control form-control-sm text-end"
                value="0"
                readonly
              >

            </div>

          </td>

          <td>

            <div class="d-flex gap-2 align-items-center">

              <span class="small text-nowrap">
                [30]
              </span>

              <input
                type="text"
                class="form-control form-control-sm text-end"
                value="0"
                readonly
              >

            </div>

          </td>

        </tr>


        <!-- b -->

        <tr>

          <td class="text-center">
            b
          </td>

          <td>
            Hàng hóa, dịch vụ bán ra chịu thuế suất 5%
          </td>

          <td>

            <div class="d-flex gap-2 align-items-center">

              <span class="small text-nowrap">
                [30]
              </span>

              <input
                type="text"
                class="form-control form-control-sm text-end"
                value="0"
                readonly
              >

            </div>

          </td>

          <td>

            <div class="d-flex gap-2 align-items-center">

              <span class="small text-nowrap">
                [31]
              </span>

              <input
                type="text"
                class="form-control form-control-sm text-end"
                value="0"
                readonly
              >

            </div>

          </td>

        </tr>


        <!-- c -->

        <tr>

          <td class="text-center">
            c
          </td>

          <td>
            Hàng hóa, dịch vụ bán ra chịu thuế suất 10%
          </td>

          <td>

            <div class="d-flex gap-2 align-items-center">

              <span class="small text-nowrap">
                [32]
              </span>

              <input
                type="text"
                class="form-control form-control-sm text-end"
                value="15.960.000"
                readonly
              >

            </div>

          </td>

          <td>

            <div class="d-flex gap-2 align-items-center">

              <span class="small text-nowrap">
                [33]
              </span>

              <input
                type="text"
                class="form-control form-control-sm text-end"
                value="1.276.800"
                readonly
              >

            </div>

          </td>

        </tr>


        <!-- 3 -->

        <tr>

          <td class="text-center">
            3
          </td>

          <td>
            Tổng doanh thu và thuế GTGT của HHDV bán ra
            ([34] = [26] + [27]; [35] = [28])
          </td>

          <td>

            <div class="d-flex gap-2 align-items-center">

              <span class="small text-nowrap">
                [34]
              </span>

              <input
                type="text"
                class="form-control form-control-sm text-end"
                value="15.960.000"
                readonly
              >

            </div>

          </td>

          <td>

            <div class="d-flex gap-2 align-items-center">

              <span class="small text-nowrap">
                [35]
              </span>

              <input
                type="text"
                class="form-control form-control-sm text-end"
                value="1.276.800"
                readonly
              >

            </div>

          </td>

        </tr>


        <!-- 4 -->

        <tr>

          <td class="text-center">
            4
          </td>

          <td>
            Thuế GTGT phát sinh trong kỳ
            ([36] = [35] - [25])
          </td>

          <td></td>

          <td>

            <div class="d-flex gap-2 align-items-center">

              <span class="small text-nowrap">
                [36]
              </span>

              <input
                type="text"
                class="form-control form-control-sm text-end"
                value="(669.768)"
                readonly
              >

            </div>

          </td>

        </tr>


        <!-- III -->

        <tr>

          <td class="text-center fw-bold">
            III
          </td>

          <td class="fw-bold">
            Điều chỉnh tăng, giảm thuế GTGT còn được khấu trừ
            của các kỳ trước
          </td>

          <td></td>

          <td></td>

        </tr>


        <!-- III.1 -->

        <tr>

          <td class="text-center">
            1
          </td>

          <td>
            Điều chỉnh giảm
          </td>

          <td></td>

          <td>

            <div class="d-flex gap-2 align-items-center">

              <span class="small text-nowrap">
                [37]
              </span>

              <input
                type="text"
                class="form-control form-control-sm text-end"
                value="0"
                readonly
              >

            </div>

          </td>

        </tr>


        <!-- III.2 -->

        <tr>

          <td class="text-center">
            2
          </td>

          <td>
            Điều chỉnh tăng
          </td>

          <td></td>

          <td>

            <div class="d-flex gap-2 align-items-center">

              <span class="small text-nowrap">
                [38]
              </span>

              <input
                type="text"
                class="form-control form-control-sm text-end"
                value="0"
                readonly
              >

            </div>

          </td>

        </tr>


        <!-- IV -->

        <tr>

          <td class="text-center fw-bold">
            IV
          </td>

          <td class="fw-bold">
            Thuế GTGT đã trả theo bản giao được khấu trừ trong kỳ
          </td>

          <td></td>

          <td></td>

        </tr>


        <!-- IV.1 -->

        <tr>

          <td class="text-center">
            1
          </td>

          <td>
            Xác định nghĩa vụ thuế GTGT phải nộp trong kỳ
          </td>

          <td></td>

          <td>

            <div class="d-flex gap-2 align-items-center">

              <span class="small text-nowrap">
                [39a]
              </span>

              <input
                type="text"
                class="form-control form-control-sm text-end"
                value="0"
                readonly
              >

            </div>

          </td>

        </tr>


        <!-- V -->

        <tr>

          <td class="text-center fw-bold">
            V
          </td>

          <td class="fw-bold">
            Thuế GTGT nhận bàn giao đầu vào được khấu trừ trong kỳ
          </td>

          <td></td>

          <td></td>

        </tr>


        <!-- V.1 -->

        <tr>

          <td class="text-center">
            1
          </td>

          <td>
            Thuế GTGT phải nộp của hoạt động sản xuất kinh doanh trong kỳ
            ([40a] = [36] - [22] + [37] - [38] - [39a] &gt; 0)
          </td>

          <td></td>

          <td>

            <div class="d-flex gap-2 align-items-center">

              <span class="small text-nowrap">
                [40a]
              </span>

              <input
                type="text"
                class="form-control form-control-sm text-end"
                value="0"
                readonly
              >

            </div>

          </td>

        </tr>


        <!-- V.2 -->

        <tr>

          <td class="text-center">
            2
          </td>

          <td>
            Thuế GTGT mua vào của dự án đầu tư được khấu trừ vào phân bổ
            của hoạt động sản xuất kinh doanh trong kỳ
          </td>

          <td></td>

          <td>

            <div class="d-flex gap-2 align-items-center">

              <span class="small text-nowrap">
                [40b]
              </span>

              <input
                type="text"
                class="form-control form-control-sm text-end"
                value="0"
                readonly
              >

            </div>

          </td>

        </tr>


        <!-- V.3 -->

        <tr>

          <td class="text-center">
            3
          </td>

          <td>
            Thuế GTGT còn phải nộp trong kỳ
            ([40] = [40a] - [40b])
          </td>

          <td></td>

          <td>

            <div class="d-flex gap-2 align-items-center">

              <span class="small text-nowrap">
                [40]
              </span>

              <input
                type="text"
                class="form-control form-control-sm text-end"
                value="0"
                readonly
              >

            </div>

          </td>

        </tr>


        <!-- V.4 -->

        <tr>

          <td class="text-center">
            4
          </td>

          <td>
            Thuế GTGT chưa khấu trừ hết kỳ này
            ([41] = [36] - [22] + [37] - [38] - [39a] &lt; 0)
          </td>

          <td></td>

          <td>

            <div class="d-flex gap-2 align-items-center">

              <span class="small text-nowrap">
                [41]
              </span>

              <input
                type="text"
                class="form-control form-control-sm text-end"
                value="2.069.622"
                readonly
              >

            </div>

          </td>

        </tr>


        <!-- V.4.1 -->

        <tr>

          <td class="text-center">
            4.1
          </td>

          <td>
            Thuế GTGT đề nghị hoàn
          </td>

          <td></td>

          <td>

            <div class="d-flex gap-2 align-items-center">

              <span class="small text-nowrap">
                [42]
              </span>

              <input
                type="text"
                class="form-control form-control-sm text-end"
                value="0"
                readonly
              >

            </div>

          </td>

        </tr>


        <!-- V.4.2 -->

        <tr>

          <td class="text-center">
            4.2
          </td>

          <td>
            Thuế GTGT còn được khấu trừ chuyển kỳ sau
            ([43] = [41] - [42])
          </td>

          <td></td>

          <td>

            <div class="d-flex gap-2 align-items-center">

              <span class="small text-nowrap">
                [43]
              </span>

              <input
                type="text"
                class="form-control form-control-sm text-end"
                value="2.069.622"
                readonly
              >

            </div>

          </td>

        </tr>

      </tbody>

    </table>

  </div>


  <!-- ========================= -->
  <!-- CHỮ KÝ -->
  <!-- ========================= -->

  <div class="row g-3 mt-2">

    <div class="col-md-6">

      <div class="fw-bold mb-2">
        NGƯỜI NỘP THUẾ
      </div>

      <div class="row g-2 align-items-center">

        <label class="col-auto col-form-label col-form-label-sm">
          Họ và tên:
        </label>

        <div class="col">
          <input
            type="text"
            class="form-control form-control-sm"
          >
        </div>

      </div>

      <div class="row g-2 align-items-center mt-1">

        <label class="col-auto col-form-label col-form-label-sm">
          Chức danh:
        </label>

        <div class="col">
          <input
            type="text"
            class="form-control form-control-sm"
          >
        </div>

      </div>

    </div>


    <div class="col-md-6">

      <div class="text-end">

        <div class="row g-2 align-items-center mb-1">

          <label class="col-auto col-form-label col-form-label-sm">
            <strong>Người ký:</strong>
          </label>

          <div class="col">
            <input
              type="text"
              class="form-control form-control-sm"
            >
          </div>

        </div>


        <div class="row g-2 align-items-center">

          <label class="col-auto col-form-label col-form-label-sm">
            <strong>Ngày ký:</strong>
          </label>

          <div class="col">
            <input
              type="text"
              class="form-control form-control-sm"
              value="17/08/2026"
            >
          </div>

        </div>

      </div>

    </div>

  </div>

</div>