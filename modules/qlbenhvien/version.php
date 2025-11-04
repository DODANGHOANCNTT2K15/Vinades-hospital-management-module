<?php

if (!defined('NV_MAINFILE')) {
    die('Stop!!!');
}

$module_version = [
    'name' => 'qlbenhvien',  // ✅ PHẢI TRÙNG với tên thư mục
    'modfuncs' => 'main,booking,historyBooking,doctor,diagnosis_detail', // hoặc 'main,booking' tùy bạn đặt tên file
    'submenu' => 'booking,historyBooking,doctor',
    'is_sysmod' => 0,
    'virtual' => 1,
    'version' => '4.5.01',
    'date' => 'Wed, 15 Oct 2025 00:00:00 GMT',
    'author' => 'Do Dang Hoan <dodanghoan@example.com>',
    'note' => 'Module quản lý bệnh viện - đặt lịch khám',
    'uploads_dir' => [
        'qlbenhvien',
        'qlbenhvien/doctors'
    ],
    'files_dir' => []
];
