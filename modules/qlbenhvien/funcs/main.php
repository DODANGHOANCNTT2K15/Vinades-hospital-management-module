<?php
if (!defined('NV_IS_MOD_QLBENHVIEN')) die('Stop!!!');

global $module_name, $module_file, $module_info;

// --- Tạo liên kết các trang ---
$link_booking = NV_BASE_SITEURL . 'index.php?nv=' . $module_name . '&op=booking';
$link_doctor = NV_BASE_SITEURL . 'index.php?nv=' . $module_name . '&op=doctor';

$xtpl = new XTemplate('main.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
$xtpl->assign('LINK_BOOKING', $link_booking);
$xtpl->assign('LINK_DOCTOR', $link_doctor);

// Nạp CSS riêng cho trang chẩn đoán
$main_funcs_css = NV_BASE_SITEURL . 'modules/' . $module_file . '/css/main_funcs.css';
$xtpl->assign('MAIN_FUNCS_CSS', $main_funcs_css);
$xtpl->parse('main');
$contents = $xtpl->text('main');

// --- Hiển thị ra site ---
include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
?>
