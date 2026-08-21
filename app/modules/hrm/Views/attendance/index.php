<div class="container-fluid py-4 mt-5">

  <div class="d-flex justify-content-between align-items-center mb-3">

    <div class="row g-2">

      <div class="col-auto">
        <input
          type="text"
          id="filter-keyword"
          class="form-control form-control-sm"
          placeholder="Tìm nhân viên">
      </div>

      <div class="col-auto">
        <input
          type="date"
          id="filter-date-from"
          class="form-control form-control-sm">
      </div>

      <div class="col-auto">
        <input
          type="date"
          id="filter-date-to"
          class="form-control form-control-sm">
      </div>

    </div>


    <div class="d-flex gap-2">

      <button
        type="button"
        class="btn btn-sm btn-outline-success"
        onclick="checkIn()">
        Check-in
      </button>


      <button
        type="button"
        class="btn btn-sm btn-outline-danger"
        onclick="checkOut()">
        Check-out
      </button>

    </div>

  </div>



  <!-- SUMMARY -->

  <div class="d-flex gap-3 mb-3">

    <div>
      <strong> Tổng nhân viên </strong>
      <span id="sum-total-attendance">0</span>
    </div>


    <div>
      <strong> Đã check-in </strong>
      <span id="sum-in">0</span>
    </div>


    <div>
      <strong> Đã check-out </strong>
      <span id="sum-out">0</span>
    </div>

  </div>



  <!-- TABLE -->

  <div class="table-responsive">

    <table class="table table-sm align-middle">

      <thead>

        <tr>
          <th>#</th>
          <th>Nhân viên</th>
          <th>Check-in</th>
          <th>Check-out</th>
          <th>Thời gian làm</th>
          <th>Trạng thái</th>
        </tr>

      </thead>


      <tbody id="attendance-table-body">

        <tr>

          <td colspan="6" class="text-center text-muted">
            Đang tải dữ liệu...
          </td>

        </tr>

      </tbody>


    </table>

  </div>



  <!-- PAGINATION -->

  <nav aria-label="Phân trang" class="mt-3">

    <ul
      class="pagination pagination-sm"
      id="pagination">
    </ul>

  </nav>


</div>



<script>


let attendanceData = [];

let currentPage = 1;

const itemsPerPage = 100;



loadAttendance();



document
  .getElementById("filter-keyword")
  .addEventListener(
    "input",
    function(){

      currentPage = 1;

      loadAttendance();

    }
  );


document
  .getElementById("filter-date-from")
  .addEventListener(
    "change",
    function(){

      currentPage = 1;

      loadAttendance();

    }
  );


document
  .getElementById("filter-date-to")
  .addEventListener(
    "change",
    function(){

      currentPage = 1;

      loadAttendance();

    }
  );





async function loadAttendance()
{

  const keyword =
    document.getElementById(
      "filter-keyword"
    ).value;


  const dateFrom =
    document.getElementById(
      "filter-date-from"
    ).value;


  const dateTo =
    document.getElementById(
      "filter-date-to"
    ).value;



  const params = new URLSearchParams({

    keyword,

    date_from: dateFrom,

    date_to: dateTo

  });



  const response = await fetch(
    "/api/attendance?" + params.toString()
  );


  const result = await response.json();



  if (!result.success) {

    alert(
      result.message || "Không tải được dữ liệu"
    );

    return;

  }



  attendanceData =
    result.data[0] ?? [];



  const summary =
    result.data[1]?.[0] ?? {};



  document.getElementById(
    "sum-total-attendance"
  ).innerText =
    summary.total ?? 0;



  document.getElementById(
    "sum-in"
  ).innerText =
    summary.total_in ?? 0;



  document.getElementById(
    "sum-out"
  ).innerText =
    summary.total_out ?? 0;



  renderAttendance();

}





function renderAttendance()
{

  const tbody =
    document.getElementById(
      "attendance-table-body"
    );


  tbody.innerHTML = "";



  const start =
    (currentPage - 1) * itemsPerPage;



  const rows =
    attendanceData.slice(
      start,
      start + itemsPerPage
    );



  if (!rows.length) {

    tbody.innerHTML = `

      <tr>

        <td
          colspan="6"
          class="text-center text-muted">

          Không có dữ liệu

        </td>

      </tr>

    `;


    renderPagination(0);

    return;

  }



  rows.forEach((item,index)=>{


    tbody.innerHTML += `

      <tr>


        <td>
          ${start + index + 1}
        </td>


        <td>
          ${item.employee_name ?? ''}
        </td>


        <td>
          ${item.check_in_at ?? '-'}
        </td>


        <td>
          ${item.check_out_at ?? '-'}
        </td>


        <td>

          ${
            item.working_minutes
            ? item.working_minutes + ' phút'
            : '0 phút'
          }

        </td>


        <td>
          ${item.status ?? ''}
        </td>


      </tr>

    `;


  });



  renderPagination(
    attendanceData.length
  );

}





function renderPagination(totalItems)
{

  const pagination =
    document.getElementById(
      "pagination"
    );


  pagination.innerHTML = "";



  const totalPages =
    Math.ceil(
      totalItems / itemsPerPage
    );



  for(
    let i = 1;
    i <= totalPages;
    i++
  )
  {


    const li =
      document.createElement(
        "li"
      );


    li.className =
      `page-item ${
        i === currentPage
        ? "active"
        : ""
      }`;



    li.innerHTML = `
      <a class="page-link text-secondary ${
        i === currentPage ? "bg-light border-secondary" : ""
      }" href="#">
        ${i}
      </a>
    `;



    li.addEventListener(
      "click",
      function(e)
      {

        e.preventDefault();


        currentPage = i;


        renderAttendance();

      }
    );



    pagination.appendChild(li);

  }

}





async function checkIn()
{

  const response =
    await fetch(
      "/api/attendance/in",
      {
        method: "POST"
      }
    );


  const result =
    await response.json();



  alert(
    result.message
  );



  loadAttendance();

}





async function checkOut()
{

  const response =
    await fetch(
      "/api/attendance/out",
      {
        method: "POST"
      }
    );


  const result =
    await response.json();



  alert(
    result.message
  );



  loadAttendance();

}


</script>