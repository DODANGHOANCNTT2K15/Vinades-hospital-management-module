<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES., JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES ., JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Dec 3, 2010  11:32:04 AM
 */

if (!defined('NV_IS_MOD_ORGAN')) die('Stop!!!');

$page_title = $module_info['site_title'];
$key_words = $module_info['keywords'];
$id = $nv_Request->get_int('id', 'get', 0);
$page_url = $base_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&amp;id=' . $id;
$canonicalUrl = getCanonicalUrl($page_url);

$data_content = array();
$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_person WHERE personid=' . $id;
$data_content = $db->query($sql)->fetch();

$contents = viewper($data_content);

include NV_ROOTDIR . '/includes/header.php';
echo $contents;
include NV_ROOTDIR . '/includes/footer.php';