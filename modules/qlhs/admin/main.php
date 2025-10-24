<?php
if (!defined('NV_IS_FILE_ADMIN')) die('Stop!!!');

$xtpl = new XTemplate('main.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);

// gán từng key ngôn ngữ để tránh lỗi kiểu
if (isset($lang_module) && is_array($lang_module)) {
    foreach ($lang_module as $k => $v) {
        $xtpl->assign('LANG.' . $k, $v);
    }
}

if (isset($lang_global) && is_array($lang_global)) {
    foreach ($lang_global as $k => $v) {
        $xtpl->assign('GLANG.' . $k, $v);
    }
}

// các gán khác
$xtpl->assign('CONTENT', 'Đây là khu vực quản trị module QLHS');

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
