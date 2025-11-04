<?php
if (!defined('NV_IS_QLBENHVIEN_ADMIN'))
    die('Stop!!!');

global $db, $db_config, $nv_Request, $module_name, $module_file, $global_config, $admin_info;

$table = $db_config['prefix'] . "_ql_benhvien_chandoan";
$table_schedule = $db_config['prefix'] . "_ql_benhvien_lichkham";
$table_patient = $db_config['prefix'] . "_ql_benhvien_benhnhan";
$table_doctor = $db_config['prefix'] . "_ql_benhvien_bacsi";

$xtpl = new XTemplate(
    'diagnosis_list.tpl',
    NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file
);

$xtpl->assign('MODULE_NAME', $module_name);

// --- Pagination ---
$page = $nv_Request->get_int('page', 'get', 1);
$per_page = 10;
$offset = ($page - 1) * $per_page;

// --- Lấy tham số lọc ---
$benhnhan_id = $nv_Request->get_int('benhnhan_id', 'get', 0);
$bacsi_id = $nv_Request->get_int('bacsi_id', 'get', 0);

// --- Lấy danh sách Bệnh nhân & Bác sĩ cho dropdown ---
$benhnhans = $db->query("SELECT id, hoten FROM $table_patient ORDER BY hoten ASC")->fetchAll();
$bacsis = $db->query("SELECT id, hoten FROM $table_doctor ORDER BY hoten ASC")->fetchAll();

// Gán selected cho dropdown
foreach ($benhnhans as &$bn) {
    $bn['selected'] = ($benhnhan_id == $bn['id']) ? 'selected' : '';
}
unset($bn);

foreach ($bacsis as &$bs) {
    $bs['selected'] = ($bacsi_id == $bs['id']) ? 'selected' : '';
}
unset($bs);

// Gán dropdown ra template
foreach ($benhnhans as $bn) {
    $xtpl->assign('BENHNHAN', $bn);
    $xtpl->parse('main.benhnhan');
}
foreach ($bacsis as $bs) {
    $xtpl->assign('BACSI', $bs);
    $xtpl->parse('main.bacsi');
}

// --- Xây dựng điều kiện WHERE ---
$where = [];
if ($benhnhan_id > 0) {
    $where[] = "lk.benhnhan_id = " . $benhnhan_id;
}
if ($bacsi_id > 0) {
    $where[] = "lk.bacsi_id = " . $bacsi_id;
}
$where_sql = (!empty($where)) ? "WHERE " . implode(" AND ", $where) : "";

// --- Đếm tổng bản ghi ---
$sql_count = "SELECT COUNT(*) FROM $table AS cd
              JOIN $table_schedule AS lk ON cd.schedule_id = lk.id
              $where_sql";

$total = $db->query($sql_count)->fetchColumn();

// --- Lấy danh sách chẩn đoán ---
$sql = "
    SELECT cd.id, cd.chandoan, cd.donthuoc, cd.ngaytao,
           lk.ngaykham,
           bn.hoten AS patient_name,
           bs.hoten AS doctor_name
    FROM $table AS cd
    JOIN $table_schedule AS lk ON cd.schedule_id = lk.id
    JOIN $table_patient AS bn ON lk.benhnhan_id = bn.id
    LEFT JOIN $table_doctor AS bs ON lk.bacsi_id = bs.id
    $where_sql
    ORDER BY cd.ngaytao DESC
    LIMIT $offset, $per_page
";

$diagnoses = $db->query($sql)->fetchAll();

// --- Hiển thị dữ liệu ---
if (!empty($diagnoses)) {
    foreach ($diagnoses as $d) {
        $d['ngaytao'] = date('d/m/Y H:i', strtotime($d['ngaytao']));
        $d['ngaykham'] = date('d/m/Y', strtotime($d['ngaykham']));

        $d['link_edit'] = NV_BASE_ADMINURL . 'index.php?nv=' . $module_name . '&op=diagnosis_edit&id=' . $d['id'];

        $d['link_delete'] = NV_BASE_ADMINURL . 'index.php?nv=' . $module_name . '&op=diagnosis_list&action=delete&id=' . $d['id'];

        $xtpl->assign('ROW', $d);
        $xtpl->parse('main.row');
    }
} else {
    $xtpl->parse('main.no_data');
}

// --- Xử lý xóa ---
$action = $nv_Request->get_title('action', 'get', '');
if ($action == 'delete') {
    $id = $nv_Request->get_int('id', 'get', 0);
    if ($id > 0) {
        $db->query("DELETE FROM $table WHERE id=" . $id);
        nv_insert_logs(NV_LANG_DATA, $module_name, 'Xóa chẩn đoán', 'ID: ' . $id, $admin_info['userid']);
    }
    Header("Location: " . NV_BASE_ADMINURL . "index.php?nv=$module_name&op=diagnosis_list");
    exit();
}

// --- Tổng số và phân trang ---
$total_info = 'Tổng số chẩn đoán: <strong>' . $total . '</strong>';
$xtpl->assign('TOTAL_INFO', $total_info);

if ($total > $per_page) {
    $base_url = NV_BASE_ADMINURL . 'index.php?nv=' . $module_name . '&op=diagnosis_list';
    if ($benhnhan_id > 0)
        $base_url .= '&benhnhan_id=' . $benhnhan_id;
    if ($bacsi_id > 0)
        $base_url .= '&bacsi_id=' . $bacsi_id;

    $generate_page = nv_generate_page($base_url, $total, $per_page, $page);
    $xtpl->assign('NV_GENERATE_PAGE', $generate_page);
    $xtpl->parse('main.pagination');
}

// --- Gán link thêm mới ---
$xtpl->assign('ADD_LINK', NV_BASE_ADMINURL . 'index.php?nv=' . $module_name . '&op=diagnosis');

// --- Render ---
$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
