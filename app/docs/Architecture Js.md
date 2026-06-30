app/
└── Views/
    └── purchases/
        ├── create.php
        │   → HTML + API + JS khởi tạo Create
        │
        └── edit.php
            → HTML + API + JS khởi tạo Edit


public/
└── assets/
    └── js/

        ├── helpers/
        │   ├── api.js
        │   │   → wrapper fetch chung
        │   │
        │   ├── notify.js
        │   │   → toast, alert
        │   │
        │   ├── formatter.js
        │   │   → format tiền, ngày tháng
        │   │
        │   └── validator.js
        │       → validate dùng chung
        │
        ├── components/
        │   ├── autocomplete.js
        │   ├── modal.js
        │   └── loading.js
        │
        └── modules/
            └── purchases/

                ├── product.js
                │   → search sản phẩm
                │   → add/remove cart
                │   → update quantity
                │   → render cart
                │   → getItems()
                │
                ├── supplier.js
                │   → search supplier
                │   → select supplier
                │
                └── submit.js
                    → submit create
                    → submit update