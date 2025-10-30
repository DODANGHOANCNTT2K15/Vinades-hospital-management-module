<?php
if (!defined('NV_IS_MOD_QLBENHVIEN')) die('Stop!!!');

$xtpl = new XTemplate('main.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);

$link_home = NV_BASE_SITEURL . 'index.php?language=vi&nv=' . $module_name . '&op=home';
$link_booking = NV_BASE_SITEURL . 'index.php?language=vi&nv=' . $module_name . '&op=booking';
$link_hoso = NV_BASE_SITEURL . 'index.php?language=vi&nv=' . $module_name . '&op=hosokham';

$xtpl->assign('LINK_HOME', $link_home);
$xtpl->assign('LINK_BOOKING', $link_booking);
$xtpl->assign('LINK_HOSO', $link_hoso);

try {
    $sql = "SELECT id, hoten, trinhdo, email, sdt FROM " . NV_PREFIXLANG . "_ql_benhvien_bacsi ORDER BY id ASC";
    $result = $db->query($sql);
    while ($row = $result->fetch()) {
        $xtpl->assign('DOCTOR', $row);
        $xtpl->parse('main.loop');
    }
} catch (Exception $e) {
    $xtpl->assign('SQL_ERROR', $e->getMessage());
    $xtpl->parse('main.sql_error');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
