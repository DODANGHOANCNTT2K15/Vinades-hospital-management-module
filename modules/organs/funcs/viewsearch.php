<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES., JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES ., JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Dec 3, 2010  11:32:04 AM
 */

if (!defined('NV_IS_MOD_ORGAN'))
    die('Stop!!!');

$where = [];
$page_title = $lang_module['search_submit'] . NV_TITLEBAR_DEFIS . $module_info['site_title'];
$is_search = false;
$key_words = $module_info['keywords'];
$page_url = $base_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;
$per_page = $arr_config['per_page'];
$page = $nv_Request->get_int('page', 'post,get', 1);

$array_search = [
    'oid' => $nv_Request->get_int('oid', 'get', 0),
    'q' => $nv_Request->get_title('q', 'post,get'),
    'e' => $nv_Request->get_title('e', 'post,get', ''),
    'p' => $nv_Request->get_title('p', 'post,get', '')
];

if (!empty($array_search['q'])) {
    $where[] = 'name LIKE \'%' . $db->dblikeescape($array_search['q']) . '%\'';
    $base_url .= '&amp;q=' . urlencode($array_search['q']);
    $is_search = true;
}
if (!empty($array_search['e'])) {
    $where[] = 'email LIKE \'%' . $db->dblikeescape($array_search['e']) . '%\'';
    $base_url .= '&amp;e=' . urlencode($array_search['e']);
    $is_search = true;
}
if (!empty($array_search['p'])) {
    $where[] = '(mobile LIKE \'%' . $db->dblikeescape($array_search['p']) . '%\' OR phone LIKE \'%' . $db->dblikeescape($array_search['p']) . '%\')';
    $base_url .= '&amp;q=' . urlencode($array_search['p']);
    $is_search = true;
}
if (!empty($array_search['oid'])) {
    $where[] = 'organid = ' . intval($array_search['oid']);
    $base_url .= '&amp;oid=' . intval($array_search['oid']);
    $is_search = true;
}

if ($page > 1) {
    $page_url .= '&amp;page=' . intval($page);
}

if ($is_search) {
    $nv_BotManager->setPrivate();
}
$page_url = $base_url;

$canonicalUrl = getCanonicalUrl($page_url);

// Fetch Limit
$db->sqlreset()
    ->select('COUNT(*)')
    ->from(NV_PREFIXLANG . '_' . $module_data . '_person');

if (!empty($where)) {
    $db->where(implode(' AND ', $where));
}

$sth = $db->prepare($db->sql());

$sth->execute();
$num_items = $sth->fetchColumn();

betweenURLs($page, ceil($num_items / $per_page), $base_url, '&page=', $prevPage, $nextPage);

$db->select('*')
    ->order('weight')
    ->limit($per_page)
    ->offset(($page - 1) * $per_page);

$sth = $db->prepare($db->sql());
$sth->execute();

$person_data = [];
while ($view = $sth->fetch()) {
    if (!empty($view['photo']) and file_exists(NV_ROOTDIR . '/' . NV_FILES_DIR . '/' . $module_upload . '/' . $view['photo'])) {
        $view['photo'] = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_upload . '/' . $view['photo'];
    } elseif (!empty($view['photo']) and file_exists(NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $view['photo'])) {
        $view['photo'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $view['photo'];
    } else {
        $view['photo'] = NV_STATIC_URL . 'themes/' . $module_info['template'] . '/images/' . $module_info['module_theme'] . '/no-avatar.jpg';
    }

    $view['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=person/' . $global_organ_rows[$view['organid']]['alias'] . '-' . $view['organid'] . '/' . change_alias($view['name']) . '-' . $view['personid'];
    $view['birthday'] = date('d/m/Y', $view['birthday']);

    $person_data[$view['personid']] = $view;
}

$html_pages = nv_generate_page($base_url, $num_items, $per_page, $page);
$contents = searchresult($person_data, $html_pages, $array_search);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
