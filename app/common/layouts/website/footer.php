    <footer class="bg-light border-top mt-5">

        <div class="container py-5">

            <div class="row g-4 justify-content-between">

                <!-- ABOUT -->
                <div class="col-12 col-md-6 col-lg-4"
                    itemscope
                    itemtype="https://schema.org/Organization">

                    <h5 class="fw-bold mb-3 fs-6" itemprop="name">
                        CÔNG TY TNHH MẠNH DŨNG SPORTS
                    </h5>

                    <p class="text-muted small mb-2 lh-lg">
                        Chuyên cung cấp vợt cầu lông, giày cầu lông và phụ kiện chính hãng.
                        Cam kết chất lượng và giá tốt nhất.
                    </p>

                    <div class="d-flex align-items-center">

                        <img
                            width="130"
                            src="<?= asset('image/bocongthuong.png') ?>"
                            alt="Đã thông báo Bộ Công Thương với Mạnh Dũng Sports"
                        >

                    </div>

                </div>

                <!-- PRODUCTS -->
                <div class="col-6 col-lg-auto">

                    <h6 class="fw-bold mb-3">Sản phẩm</h6>

                    <ul class="list-unstyled small">

                        <li class="mb-2">
                            <a
                                class="text-decoration-none text-muted"
                                href="<?= route('product') ?>?category=2"
                            >
                                Cước cầu lông
                            </a>
                        </li>

                        <li class="mb-2">
                            <a
                                class="text-decoration-none text-muted"
                                href="<?= route('product') ?>?category=6"
                            >
                                Giày cầu lông
                            </a>
                        </li>

                        <li class="mb-2">
                            <a
                                class="text-decoration-none text-muted"
                                href="<?= route('product') ?>?category=3"
                            >
                                Máy đan vợt
                            </a>
                        </li>

                        <li class="mb-2">
                            <a
                                class="text-decoration-none text-muted"
                                href="<?= route('product') ?>?category=8"
                            >
                                Phụ kiện cầu lông
                            </a>
                        </li>

                        <li class="mb-2">
                            <a
                                class="text-decoration-none text-muted"
                                href="<?= route('product') ?>?category=4"
                            >
                                Quả cầu lông
                            </a>
                        </li>

                        <li class="mb-2">
                            <a
                                class="text-decoration-none text-muted"
                                href="<?= route('product') ?>?category=5"
                            >
                                Quần áo cầu lông
                            </a>
                        </li>

                        <li class="mb-2">
                            <a
                                class="text-decoration-none text-muted"
                                href="<?= route('product') ?>?category=7"
                            >
                                Túi cầu lông
                            </a>
                        </li>

                        <li class="mb-2">
                            <a
                                class="text-decoration-none text-muted"
                                href="<?= route('product') ?>?category=1"
                            >
                                Vợt cầu lông
                            </a>
                        </li>

                    </ul>

                </div>

                <!-- SUPPORT -->
                <div class="col-6 col-lg-auto">

                    <h6 class="fw-bold mb-3">Hỗ trợ</h6>

                    <ul class="list-unstyled small">

                        <li class="mb-2">
                            <a
                                class="text-decoration-none text-muted"
                                href="<?= route('lien-he') ?>"
                            >
                                Liên hệ
                            </a>
                        </li>

                        <li class="mb-2">
                            <a
                                class="text-decoration-none text-muted"
                                href="<?= route('chinh-sach-bao-hanh') ?>"
                            >
                                Bảo hành
                            </a>
                        </li>

                        <li class="mb-2">
                            <a
                                class="text-decoration-none text-muted"
                                href="<?= route('chinh-sach-van-chuyen') ?>"
                            >
                                Vận chuyển
                            </a>
                        </li>

                        <li class="mb-2">
                            <a
                                class="text-decoration-none text-muted"
                                href="<?= route('chinh-sach-doi-tra') ?>"
                            >
                                Đổi trả
                            </a>
                        </li>

                    </ul>

                </div>

                <!-- CONTACT -->
                <div class="col-12 col-lg-auto"
                    itemscope
                    itemtype="https://schema.org/Organization">

                    <h6 class="fw-bold mb-3">Liên hệ</h6>

                    <ul class="list-unstyled small text-muted mb-0">

                        <li class="mb-2 d-flex align-items-center gap-2">

                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M6.6 10.8c1.5 3 3.6 5.1 6.6 6.6l2.2-2.2c.3-.3.8-.4 1.2-.3 1.3.4 2.7.7 4 .7.7 0 1.4.6 1.4 1.4V21c0 .8-.6 1.4-1.4 1.4C11.6 22.4 1.6 12.4 1.6 2.4 1.6 1.6 2.2 1 3 1h3.6C7.4 1 8 1.6 8 2.4c0 1.3.2 2.7.7 4 .1.4 0 .9-.3 1.2L6.6 10.8z"/>
                            </svg>

                            <a
                                href="tel:0973359165"
                                class="text-decoration-none text-muted"
                                itemprop="telephone"
                            >
                                0973 359 165
                            </a>

                        </li>

                        <li class="mb-2 d-flex align-items-center gap-2">

                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4-8 5-8-5V6l8 5 8-5v2z"/>
                            </svg>

                            <a
                                href="mailto:manhdungsports@gmail.com"
                                class="text-decoration-none text-muted"
                                itemprop="email"
                            >
                                manhdungsports@gmail.com
                            </a>

                        </li>

                        <li class="mb-2 d-flex align-items-center gap-2">

                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2C8.1 2 5 5.1 5 9c0 5.3 7 13 7 13s7-7.7 7-13c0-3.9-3.1-7-7-7zm0 9.5c-1.4 0-2.5-1.1-2.5-2.5S10.6 6.5 12 6.5s2.5 1.1 2.5 2.5S13.4 11.5 12 11.5z"/>
                            </svg>

                            <span itemprop="addressLocality">
                                Hà Nội
                            </span>

                        </li>

                    </ul>

                </div>

                <!-- SOCIAL -->
                <div class="col-12 col-lg-auto">

                    <h6 class="fw-bold mb-3">Kết nối</h6>

                    <div class="d-flex flex-column gap-2">

                        <a
                            href="https://facebook.com"
                            target="_blank"
                            rel="noopener"
                            class="text-decoration-none text-muted d-flex align-items-center gap-2"
                        >
                            Facebook
                        </a>

                        <a
                            href="https://tiktok.com"
                            target="_blank"
                            rel="noopener"
                            class="text-decoration-none text-muted d-flex align-items-center gap-2"
                        >
                            TikTok
                        </a>

                        <a
                            href="https://instagram.com"
                            target="_blank"
                            rel="noopener"
                            class="text-decoration-none text-muted d-flex align-items-center gap-2"
                        >
                            Instagram
                        </a>

                    </div>

                </div>

            </div>

        </div>

        <div class="border-top py-3">

            <div class="container text-center text-muted small">

                © <?= date('Y') ?>
                Công ty TNHH Mạnh Dũng Sports.
                All rights reserved.

            </div>

        </div>

    </footer>

    <script src="<?= asset('js/bootstrap.js') ?>"></script>

</body>
</html>