<?php
if (!defined('NV_IS_MOD_QLBENHVIEN')) die('Stop!!!');

$xtpl = new XTemplate('home.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
$xtpl->assign('BASE_URL', NV_BASE_SITEURL);

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
