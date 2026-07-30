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

            <strong>Đã MAP</strong>

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

                    <th>Đức An</th>

                    <th>Sản phẩm</th>

                    <th>Giá</th>

                    <th>Sale</th>

                    <th>Link</th>

                </tr>


            </thead>




            <tbody id="map-table-body">



            <?php if(empty($matched)): ?>


                <tr>

                    <td 
                        colspan="6"
                        class="text-center text-muted"
                    >
                        Không có sản phẩm đã MAP
                    </td>

                </tr>



            <?php else: ?>



                <?php foreach($matched as $index => $item): ?>


                    <tr
                        data-name="<?= strtolower(
                            htmlspecialchars(
                                $item['crawl_name'] . ' ' .
                                $item['product_name'] . ' ' .
                                $item['product_id']
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


                            <div>

                                <?= htmlspecialchars($item['product_name']) ?>

                            </div>

                        </td>




                        <td>

                            <?= number_format($item['ducan_price']) ?>

                        </td>




                        <td>

                            <?= number_format($item['ducan_sale_price']) ?>

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
        '#map-table-body tr[data-name]'
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