<?php

/**
 * @Project NUKEVIET 4.x
 * @Module QL_BENHVIEN
 * @Author Do Dang Hoan
 * @License GNU/GPL version 2 or any later version
 * @Createdate 15/10/2025
 */

if (!defined('NV_IS_FILE_MODULES')) {
    die('Stop!!!');
}

// ======================
// KHÔNG XÓA DỮ LIỆU KHI GỠ MODULE
// ======================

// Không khai báo mảng $sql_drop_module nữa
$sql_create_module = [];

// 1️⃣ Chuyên khoa
$sql_create_module[] = "
CREATE TABLE IF NOT EXISTS `" . $db_config['prefix'] . "_ql_benhvien_chuyenkhoa` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `tenchuyenkhoa` VARCHAR(100) NOT NULL,
  `mota` MEDIUMTEXT DEFAULT NULL,
  `trangthai` TINYINT(4) DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
";

// 2️⃣ Bác sĩ
$sql_create_module[] = "
CREATE TABLE IF NOT EXISTS `" . $db_config['prefix'] . "_ql_benhvien_bacsi` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `hoten` VARCHAR(100) NOT NULL,
  `ngaysinh` DATE DEFAULT NULL,
  `gioitinh` TINYINT(4) DEFAULT NULL,
  `chuyenkhoa_id` INT(11) DEFAULT NULL,
  `trinhdo` VARCHAR(100) DEFAULT NULL,
  `lichlamviec` MEDIUMTEXT DEFAULT NULL,
  `email` VARCHAR(100) DEFAULT NULL,
  `sdt` VARCHAR(15) DEFAULT NULL,
  `userid` MEDIUMINT(8) UNSIGNED DEFAULT NULL,
  `hinhanh` VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_bacsi_chuyenkhoa` (`chuyenkhoa_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
";

// 3️⃣ Bệnh nhân
$sql_create_module[] = "
CREATE TABLE IF NOT EXISTS `" . $db_config['prefix'] . "_ql_benhvien_benhnhan` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `hoten` VARCHAR(100) NOT NULL,
  `ngaysinh` DATE DEFAULT NULL,
  `gioitinh` TINYINT(4) DEFAULT NULL,
  `diachi` VARCHAR(255) DEFAULT NULL,
  `sdt` VARCHAR(15) DEFAULT NULL,
  `email` VARCHAR(100) DEFAULT NULL,
  `userid` MEDIUMINT(8) UNSIGNED DEFAULT NULL,
  `ngaytao` DATETIME DEFAULT CURRENT_TIMESTAMP(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
";

// 4️⃣ Lịch khám
$sql_create_module[] = "
CREATE TABLE IF NOT EXISTS `" . $db_config['prefix'] . "_ql_benhvien_lichkham` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `benhnhan_id` INT(11) DEFAULT NULL,
  `bacsi_id` INT(11) DEFAULT NULL,
  `ngaykham` DATE NOT NULL,
  `giokham` TIME NOT NULL,
  `trangthai` ENUM('pending','confirmed','cancelled') DEFAULT 'pending',
  `ghichu` MEDIUMTEXT DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_lichkham_benhnhan` (`benhnhan_id`),
  KEY `fk_lichkham_bacsi` (`bacsi_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
";

// 5️⃣ Hồ sơ khám
$sql_create_module[] = "
CREATE TABLE IF NOT EXISTS `" . $db_config['prefix'] . "_ql_benhvien_hosokham` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `lichkham_id` INT(11) NOT NULL,
  `ngaykham` DATETIME NOT NULL,
  `chandoan` TEXT DEFAULT NULL,
  `ketqua` TEXT DEFAULT NULL,
  `ketluan` TEXT DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
";
