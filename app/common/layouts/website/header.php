<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link rel="stylesheet" href="<?= asset('css/bootstrap.css') ?>">
</head>

<body>

<header class="border-bottom bg-white">

    <div class="container">

        <nav class="navbar navbar-expand-lg navbar-light py-3">

            <!-- Logo -->
            <a class="navbar-brand fw-bold me-4"
               style="color: #26750e"
               href="<?= route() ?>">
                MDB SHOP
            </a>

            <!-- Toggle -->
            <button class="navbar-toggler"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#mainNavbar">

                <span class="navbar-toggler-icon"></span>

            </button>

            <div class="collapse navbar-collapse" id="mainNavbar">

                <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                    <li class="nav-item">
                        <a
                            class="nav-link <?= active_menu_exact('/') ?>"
                            href="<?= route() ?>"
                        >
                            Trang chủ
                        </a>
                    </li>

                    <li class="nav-item">
                        <a
                            class="nav-link <?= active_menu('product') ?>"
                            href="<?= route('product') ?>"
                        >
                            Sản phẩm
                        </a>
                    </li>

                    <li class="nav-item">
                        <a
                            class="nav-link <?= active_menu('string') ?>"
                            href="<?= route('string') ?>"
                        >
                            Căng cước
                        </a>
                    </li>

                    <li class="nav-item">
                        <a
                            class="nav-link <?= active_menu('affiliate') ?>"
                            href="<?= route('affiliate') ?>"
                        >
                            CTV
                        </a>
                    </li>

                    <li class="nav-item">
                        <a
                            class="nav-link <?= active_menu('career') ?>"
                            href="<?= route('career') ?>"
                        >
                            Tuyển dụng
                        </a>
                    </li>

                </ul>

                <!-- SEARCH -->
                <form
                    class="d-flex flex-grow-1 mx-lg-4 mb-3 mb-lg-0 border border-success rounded overflow-hidden"
                    method="get"
                    action="<?= route('product') ?>"
                >

                    <input
                        class="form-control border-0 shadow-none"
                        type="search"
                        name="keyword"
                        placeholder="Tìm kiếm đồ cầu lông..."
                        value="<?= htmlspecialchars($_GET['keyword'] ?? '') ?>"
                    >

                    <button
                        class="btn btn-sm rounded-0 d-flex align-items-center justify-content-center px-3"
                        type="submit"
                    >

                        <svg xmlns="http://www.w3.org/2000/svg"
                             width="18"
                             height="18"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="1.5"
                             stroke-linecap="round"
                             stroke-linejoin="round"
                             viewBox="0 0 24 24">

                            <circle cx="11" cy="11" r="7"></circle>
                            <path d="M20 20l-3.5-3.5"></path>

                        </svg>

                    </button>

                </form>

                <!-- ACTIONS -->
                <div class="d-flex gap-2">



                    <a href="<?= route('cart') ?>"
                       class="btn btn-success btn-sm">
                        Giỏ hàng
                    </a>

                </div>

            </div>

        </nav>

    </div>

</header>