<?php
if (!defined('NV_IS_QLBENHVIEN_ADMIN')) die('Stop!!!');

global $db, $db_config, $nv_Request, $module_name, $module_file, $global_config, $admin_info;

$table = $db_config['prefix'] . "_ql_benhvien_chuyenkhoa";
$per_page = 10;
$page = $nv_Request->get_int('page', 'get', 1);
$action = $nv_Request->get_string('action', 'get', 'list');

// Lấy từ khóa tìm kiếm (nếu có)
$keyword = $nv_Request->get_string('keyword', 'get', '');

// =============================
// 1️⃣ Xử lý xóa chuyên khoa
// =============================
if ($action == 'delete') {
    $id = $nv_Request->get_int('id', 'get', 0);
    if ($id > 0) {
        $checkss = $nv_Request->get_string('checkss', 'get', '');
        if ($checkss == md5($id . NV_CHECK_SESSION)) {
            $db->query("DELETE FROM " . $table . " WHERE id=" . $id);
            nv_insert_logs(NV_LANG_DATA, $module_name, 'Xóa chuyên khoa', 'ID: ' . $id, $admin_info['userid']);
        }
    }
    Header("Location: " . NV_BASE_ADMINURL . "index.php?nv=$module_name&op=specialties");
    exit();
}

// =============================
// 2️⃣ Hiển thị danh sách chuyên khoa
// =============================

// Tạo điều kiện WHERE nếu có tìm kiếm
$where = '';
$params = [];
if (!empty($keyword)) {
    $keyword_escaped = $db->quote("%" . $keyword . "%");
    $where = " WHERE tenchuyenkhoa LIKE " . $keyword_escaped;
}

// Đếm tổng số bản ghi theo điều kiện tìm kiếm
$total = $db->query("SELECT COUNT(*) FROM " . $table . $where)->fetchColumn();

// Lấy danh sách theo trang và điều kiện tìm kiếm
$sql = "SELECT * FROM " . $table . $where . " ORDER BY id DESC
        LIMIT " . (($page - 1) * $per_page) . ", $per_page";

$result = $db->query($sql)->fetchAll();

// Khởi tạo template
$xtpl = new XTemplate(
    'specialties.tpl',
    NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file
);

$xtpl->assign('ADD_LINK', NV_BASE_ADMINURL . "index.php?nv=$module_name&op=specialties_add");
$xtpl->assign('KEYWORD', htmlspecialchars($keyword, ENT_QUOTES));
$xtpl->assign('MODULE_NAME', $module_name);

if(!empty($result)){
    foreach ($result as $row) {
        $row['trangthai_text'] = ($row['trangthai'] == 1) ? 'Hoạt động' : 'Ngừng hoạt động';
        $row['link_edit'] = NV_BASE_ADMINURL . "index.php?nv=$module_name&op=specialties_edit&id=" . $row['id'];
        $row['link_delete'] = NV_BASE_ADMINURL . "index.php?nv=$module_name&op=specialties&action=delete&id=" . $row['id'] . "&checkss=" . md5($row['id'] . NV_CHECK_SESSION);

        $xtpl->assign('ROW', $row);
        $xtpl->parse('main.list.row');
    }
} else {
    $xtpl->parse('main.list.no_data');
}


// Phân trang - nhớ giữ tham số keyword trong URL phân trang
$base_url = NV_BASE_ADMINURL . "index.php?nv=$module_name&op=specialties";
if (!empty($keyword)) {
    $base_url .= '&keyword=' . urlencode($keyword);
}
$generate_page = nv_generate_page($base_url, $total, $per_page, $page);
if (!empty($generate_page)) {
    $xtpl->assign('NV_GENERATE_PAGE', $generate_page);
    $xtpl->parse('main.list.pagination');
}

$xtpl->assign('TOTAL_INFO', "Tổng cộng $total chuyên khoa");
$xtpl->parse('main.list');
$xtpl->parse('main');

$contents = $xtpl->text('main');
echo nv_admin_theme($contents);
