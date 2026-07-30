<div class="container-fluid py-4 mt-5">


    <div class="d-flex justify-content-between align-items-center mb-3">


        <div class="row g-2">


            <div class="col-auto">

                <input 
                    type="text" 
                    id="filter-keyword" 
                    class="form-control form-control-sm" 
                    placeholder="Tìm sản phẩm"
                >

            </div>


        </div>





        <?php include __DIR__ . '/navbar.php'; ?>


    </div>





    <!-- SUMMARY -->


    <div class="d-flex gap-3 mb-3">


        <div>

            <strong>Chưa MAP</strong>

            <span>
                <?= $total ?>
            </span>

        </div>


    </div>





    <!-- TABLE -->


    <div class="table-responsive">


        <table class="table table-sm align-middle">


            <thead>

                <tr>

                    <th>#</th>

                    <th>Sản phẩm Đức An</th>

                    <th>Giá</th>

                    <th>Sale</th>

                    <th>Link</th>

                </tr>

            </thead>





            <tbody id="unmatched-table-body">



            <?php if(empty($unmatched)): ?>


                <tr>

                    <td 
                        colspan="5"
                        class="text-center text-muted"
                    >
                        Không có sản phẩm chưa MAP
                    </td>

                </tr>



            <?php else: ?>



                <?php foreach($unmatched as $index => $item): ?>


                    <tr
                        data-name="<?= strtolower(
                            htmlspecialchars(
                                $item['crawl_name']
                            )
                        ) ?>"
                    >


                        <td>

                            <?= $index + 1 ?>

                        </td>




                        <td>

                            <?= htmlspecialchars($item['crawl_name']) ?>

                        </td>




                        <td>

                            <?= number_format($item['price']) ?>

                        </td>




                        <td>

                            <?= number_format($item['sale_price']) ?>

                        </td>




                        <td>


                            <a 
                                href="<?= $item['crawl_url'] ?>"
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

document.addEventListener('DOMContentLoaded', function(){


    const input = document.getElementById('filter-keyword');

    const rows = document.querySelectorAll(
        '#unmatched-table-body tr[data-name]'
    );


    if(!input) return;



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