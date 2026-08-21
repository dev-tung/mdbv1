<div class="container-fluid py-4 mt-5">


    <div class="d-flex justify-content-between align-items-center mb-3">


        <input 
            id="filter-keyword"
            class="form-control form-control-sm w-auto"
            placeholder="Tìm sản phẩm"
        >

        <?php include __DIR__ . '/navbar.php'; ?>

    </div>



    <div class="mb-3">

        <strong>Tổng sản phẩm</strong>
        <?= $total ?>

    </div>




    <div class="table-responsive">


        <table class="table table-sm align-middle">


            <thead>

                <tr>
                    <th>#</th>
                    <th>Sản phẩm</th>
                    <th>Giá</th>
                    <th>Sale</th>
                    <th>Link</th>
                </tr>

            </thead>



            <tbody id="list-table-body">


            <?php if(empty($products)): ?>


                <tr>
                    <td colspan="5" class="text-center text-muted">
                        Không có sản phẩm
                    </td>
                </tr>


            <?php else: ?>


                <?php foreach($products as $i => $item): ?>


                    <tr data-name="<?= strtolower(htmlspecialchars($item['name'])) ?>">


                        <td>
                            <?= $i + 1 ?>
                        </td>


                        <td>
                            <?= htmlspecialchars($item['name']) ?>
                        </td>


                        <td>
                            <?= number_format($item['price']) ?>
                        </td>


                        <td>
                            <?= number_format($item['sale_price']) ?>
                        </td>


                        <td>

                            <a 
                                href="<?= $item['url'] ?>"
                                target="_blank"
                                class="btn btn-sm btn-outline-secondary"
                            >
                                Mở
                            </a>

                        </td>


                    </tr>


                <?php endforeach; ?>


            <?php endif; ?>


            </tbody>


        </table>


    </div>


</div>




<script>

document.addEventListener('DOMContentLoaded', () => {


    const input = document.getElementById('filter-keyword');

    const rows = document.querySelectorAll('#list-table-body tr[data-name]');



    input.addEventListener('input', function(){


        const keyword = this.value
            .toLowerCase()
            .trim();



        rows.forEach(row => {


            const name = row.dataset.name;


            row.style.display = name.includes(keyword)
                ? ''
                : 'none';


        });


    });


});


</script>