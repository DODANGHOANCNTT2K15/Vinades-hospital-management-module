<?php
if (!defined('NV_IS_QLBENHVIEN_ADMIN')) die('Stop!!!');

global $db, $db_config, $nv_Request, $module_name, $module_file, $global_config;

$table = $db_config['prefix'] . "_ql_benhvien_chandoan";
$table_schedule = $db_config['prefix'] . "_ql_benhvien_lichkham";
$table_patient = $db_config['prefix'] . "_ql_benhvien_benhnhan";
$table_doctor = $db_config['prefix'] . "_ql_benhvien_bacsi";

$xtpl = new XTemplate(
    'diagnosis_list.tpl',
    NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file
);

// --- Pagination ---
$page = $nv_Request->get_int('page', 'get', 1);
$per_page = 10;
$offset = ($page - 1) * $per_page;

// --- Đếm tổng số bản ghi ---
$total = $db->query("SELECT COUNT(*) FROM $table")->fetchColumn();

// --- Lấy danh sách chẩn đoán + thông tin liên quan ---
$sql = "
    SELECT cd.id, cd.chandoan, cd.donthuoc, cd.ngaytao,
           lk.ngaykham,
           bn.hoten AS patient_name,
           bs.hoten AS doctor_name
    FROM $table AS cd
    JOIN $table_schedule AS lk ON cd.schedule_id = lk.id
    JOIN $table_patient AS bn ON lk.benhnhan_id = bn.id
    LEFT JOIN $table_doctor AS bs ON lk.bacsi_id = bs.id
    ORDER BY cd.ngaytao DESC
    LIMIT $offset, $per_page
";

$diagnoses = $db->query($sql)->fetchAll();

// --- Hiển thị dữ liệu ---
if (!empty($diagnoses)) {
    foreach ($diagnoses as $d) {
        $d['ngaytao'] = date('d/m/Y H:i', strtotime($d['ngaytao']));
        $d['ngaykham'] = date('d/m/Y', strtotime($d['ngaykham']));

        // ✅ Thêm link sửa và xóa
        $d['link_edit'] = NV_BASE_ADMINURL . 'index.php?nv=' . $module_name . '&op=diagnosis&id=' . $d['id'] . '&action=edit';
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
