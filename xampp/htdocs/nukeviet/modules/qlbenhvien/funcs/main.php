<?php
if (!defined('NV_IS_MOD_QLBENHVIEN')) die('Stop!!!');

global $module_name, $module_file, $module_info;

// --- Chuẩn bị dữ liệu truyền ra template ---
$link_booking = NV_BASE_SITEURL . 'index.php?nv=' . $module_name . '&op=booking';

// --- Gọi giao diện bằng XTemplate ---
$xtpl = new XTemplate('main.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);

$xtpl->assign('LINK_BOOKING', $link_booking);

$xtpl->parse('main');
$contents = $xtpl->text('main');

// --- Hiển thị ra ngoài site ---
include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
