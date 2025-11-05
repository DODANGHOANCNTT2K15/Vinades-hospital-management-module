# 🏥 Module Quản Lý Bệnh Viện - NukeViet 4.5

## 📖 Giới thiệu
**Module Quản Lý Bệnh Viện** là một module được phát triển trên nền tảng **NukeViet 4.5**, giúp quản lý các hoạt động cơ bản của một bệnh viện như:
- Giới thiệu bệnh viện, đội ngũ bác sĩ, tin tức.
- Cho phép **bệnh nhân đặt lịch khám trực tuyến**.
- Quản lý thông tin bệnh nhân, bác sĩ, và lịch khám trong **giao diện quản trị (Admin)**.

---

## 🚀 Tính năng chính

### 🌐 Ngoài site (Giao diện người dùng)
| STT | Tên chức năng | Mô tả chi tiết | Đảm nhiệm |
|-----|----------------|----------------|------------|
| 1 | **Trang chủ bệnh viện** | Hiển thị thông tin giới thiệu, cơ sở vật chất, đội ngũ bác sĩ và tin tức mới nhất. | Long |
| 2 | **Tra cứu bác sĩ / chuyên khoa** | Cho phép người dùng tìm kiếm bác sĩ theo tên hoặc chuyên khoa. | Lan |
| 3 | **Đặt lịch khám trực tuyến** | Bệnh nhân chọn bác sĩ, ngày và giờ khám để gửi yêu cầu đặt lịch. | Hoàn |

---

### ⚙️ Admin (Giao diện quản trị)
| STT | Tên chức năng | Mô tả chi tiết | Đảm nhiệm |
|-----|----------------|----------------|------------|
| 1 | **Quản lý bệnh nhân** | Thêm, sửa, xóa và tìm kiếm thông tin bệnh nhân. | Khánh |
| 2 | **Quản lý bác sĩ** | Quản lý thông tin bác sĩ, chuyên khoa và trạng thái làm việc. | Lan |
| 3 | **Quản lý lịch khám** | Duyệt, hủy hoặc xem chi tiết các lịch khám do bệnh nhân đặt. | Hoàn |
| 4 | **Quản lý hồ sơ khám bệnh** | Ghi nhận chẩn đoán và đơn thuốc cơ bản (chưa cần kho thuốc). | Long |

---

## 🧑‍💻 Công nghệ sử dụng
- **Ngôn ngữ:** PHP, HTML, CSS, JavaScript  
- **Framework CMS:** [NukeViet 4.5](https://nukeviet.vn/)  
- **Cơ sở dữ liệu:** MySQL  
- **Giao diện quản trị:** Bootstrap 4 / 5 (tùy phiên bản NukeViet)

## 🧩 Cấu trúc module

```
modules/
└── qlbenhvien/
    ├── admin/
    │ ├── diagnosis.php 
    │ ├── diagnosis_detail.php 
    │ ├── diagnosis_edit.php 
    │ ├── diagnosis_list.php
    │ ├── doctor.php
    │ ├── doctor_add.php
    │ ├── doctor_detail.php
    │ ├── doctor_edit.php
    │ ├── main.php
    │ ├── patient.php
    │ ├── patient_add.php
    │ ├── patient_detail.php
    │ ├── patient_edit.php
    │ ├── schedule.php
    │ ├── schedule_add.php
    │ ├── schedule_edit.php
    │ ├── specialties.php
    │ ├── specialties_add.php
    │ └── specialties_edit.php
    ├── css/
    │ └── ...
    ├── funcs/
    │ ├── booking.php 
    │ ├── diagnosis_detail.php
    │ ├── doctor.php 
    │ ├── historyBooking.php  
    │ └── main.php
    ├── language/
    │ └── vi.php
    ├── functions.php
    ├── admin.functions.php
    ├── admin.menu.php
    ├── action_mysql.php
    ├── index.html
    ├── theme.php
    └── version.php
 
themes/
├── admin_default/
│   └── modules/
│       └── qlbenhvien/
│           ├── diagnosis.php 
│           ├── diagnosis_detail.php 
│           ├── diagnosis_edit.php 
│           ├── diagnosis_list.php
│           ├── doctor.php
│           ├── doctor_add.php
│           ├── doctor_detail.php
│           ├── doctor_edit.php
│           ├── main.php
│           ├── patient.php
│           ├── patient_add.php
│           ├── patient_detail.php
│           ├── patient_edit.php
│           ├── schedule.php
│           ├── schedule_add.php
│           ├── schedule_edit.php
│           ├── specialties.php
│           ├── specialties_add.php
│           └── specialties_edit.php
└── default/
    └── modules/
        └── qlbenhvien/
            ├── booking.php 
            ├── diagnosis_detail.php
            ├── doctor.php 
            ├── historyBooking.php  
            └── main.php
```
---

## 👥 Thành viên nhóm phát triển
| Họ và tên | Vai trò | Nhiệm vụ |
|------------|----------|-----------|
| **Long** | Developer | Trang chủ & Hồ sơ khám bệnh |
| **Lan** | Developer | Tra cứu bác sĩ & Quản lý bác sĩ |
| **Hoàn** | Leader | Đặt lịch khám & Quản lý lịch khám |
| **Khánh** | Developer | Quản lý bệnh nhân |

---


## 📅 Tiến độ dự án

Tuần 1 – 2: Thiết kế CSDL, tạo module cơ bản

Tuần 3 – 4 - 5: Phát triển chức năng frontend (user), phát triển giao diện và tính năng admin. 

Tuần 6: Kiểm thử, hoàn thiện và viết báo cáo
