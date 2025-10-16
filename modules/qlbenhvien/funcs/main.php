<?php
if (!defined('NV_IS_MOD_QLBENHVIEN')) die('Stop!!!');

// Trang chính của module ngoài site
$contents  = '<h2>Hệ thống quản lý bệnh viện</h2>';
$contents .= '<p>Chào mừng bạn đến với hệ thống đặt lịch khám trực tuyến.</p>';
$contents .= '<p><a href="' . NV_BASE_SITEURL . 'index.php?nv=' . $module_name . '&op=booking">→ Đặt lịch khám ngay</a></p>';

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
