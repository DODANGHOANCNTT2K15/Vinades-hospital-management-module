<?php

if (!defined('NV_MAINFILE')) {
    die('Stop!!!');
}

$module_version = [
    'name' => 'qlbenhvien',  // ✅ PHẢI TRÙNG với tên thư mục
    'modfuncs' => 'main,dat_lich', // hoặc 'main,booking' tùy bạn đặt tên file
    'submenu' => 'dat_lich',       // hoặc 'booking'
    'is_sysmod' => 0,
    'virtual' => 1,
    'version' => '4.5.01',
    'date' => 'Wed, 15 Oct 2025 00:00:00 GMT',
    'author' => 'Do Dang Hoan <dodanghoan@example.com>',
    'note' => 'Module quản lý bệnh viện - đặt lịch khám',
    'uploads_dir' => [
        $module_name,
        $module_name . '/doctors'
    ],
    'files_dir' => []
];
