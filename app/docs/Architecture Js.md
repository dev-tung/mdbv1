public/
└── assets/
└── js/
│
├── helpers/
│ ├── api.js // Hàm request dùng chung.
│ ├── calculator.js // Hàm tính toán.
│ ├── formatter.js // Format tiền, ngày...
│ ├── validator.js // Validate dữ liệu.
│ ├── notify.js // Toast, Alert.
│ ├── storage.js // LocalStorage, SessionStorage.
│ ├── debounce.js // Debounce sự kiện.
│ ├── throttle.js // Throttle sự kiện.
│ └── utils.js // Hàm tiện ích.
│
├── components/
│ ├── modal.js // Modal dùng chung.
│ ├── loading.js // Loading dùng chung.
│ ├── table.js // Bảng dùng chung.
│ ├── pagination.js // Phân trang.
│ ├── autocomplete.js // Gợi ý tìm kiếm.
│ └── confirm.js // Hộp thoại xác nhận.
│
└── modules/
└── shop/
└── purchases/
form/
├── index.js // Điểm khởi chạy của module.
├── state.js // Quản lý trạng thái (State) của module.
├── api.js // Giao tiếp API của phiếu nhập.
├── service.js // Xử lý nghiệp vụ và cập nhật State.
├── controller.js // Điều phối luồng giữa Event, Service và Renderer.
├── renderer.js // Hiển thị dữ liệu và cập nhật giao diện.
└── event.js // Đăng ký và xử lý các sự kiện người dùng.
list/
├── index.js // Điểm khởi chạy của module.
├── state.js // Quản lý trạng thái (State) của module.
├── api.js // Giao tiếp API của phiếu nhập.
├── service.js // Xử lý nghiệp vụ và cập nhật State.
├── controller.js // Điều phối luồng giữa Event, Service và Renderer.
├── renderer.js // Hiển thị dữ liệu và cập nhật giao diện.
└── event.js // Đăng ký và xử lý các sự kiện người dùng.
