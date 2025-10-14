<?php

if (!defined('NV_IS_MOD_QLHS'))
    die('Stop!!!');

function nv_qlhs_main()
{
    global $module_file, $module_info;

    $template = file_exists(NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file . '/main.tpl')
        ? $module_info['template']
        : 'default';

    $xtpl = new XTemplate('main.tpl', NV_ROOTDIR . '/themes/' . $template . '/modules/' . $module_file);
    $xtpl->assign('TITLE', 'Chào mừng đến module Quản lý học sinh');
    $xtpl->assign('CONTENT', 'Đây là giao diện ngoài site của module.');

    $xtpl->parse('main');
    return $xtpl->text('main');
}
