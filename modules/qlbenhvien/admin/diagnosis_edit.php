<?php
if (!defined('NV_IS_QLBENHVIEN_ADMIN')) {
    die('Stop!!!');
}

global $db, $db_config, $nv_Request, $module_name, $module_file, $global_config, $admin_info;

// Tên bảng
$table = $db_config['prefix'] . "_ql_benhvien_chandoan";
$table_schedule = $db_config['prefix'] . "_ql_benhvien_lichkham";
$table_patient = $db_config['prefix'] . "_ql_benhvien_benhnhan";
$table_doctor = $db_config['prefix'] . "_ql_benhvien_bacsi";

// Lấy ID chẩn đoán từ URL
$id = $nv_Request->get_int('id', 'get', 0);
$submit = $nv_Request->get_int('submit', 'post', 0);

if ($id <= 0) {
    die('ID không hợp lệ!');
}

// Tạo XTemplate
$xtpl = new XTemplate(
    'diagnosis_edit.tpl',
    NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file
);

// -------------------------
// 1️⃣ Lấy dữ liệu chẩn đoán và lịch khám liên quan
// -------------------------
$sql = "
    SELECT cd.*, 
           lk.ngaykham, 
           bn.hoten AS patient_name, 
           bs.hoten AS doctor_name
    FROM $table AS cd
    INNER JOIN $table_schedule AS lk ON cd.schedule_id = lk.id
    INNER JOIN $table_patient AS bn ON lk.benhnhan_id = bn.id
    LEFT JOIN $table_doctor AS bs ON lk.bacsi_id = bs.id
    WHERE cd.id = :id
";
$stmt = $db->prepare($sql);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$row = $stmt->fetch();

if (empty($row)) {
    die('Không tìm thấy chẩn đoán hoặc lịch khám liên quan!');
}

// -------------------------
// 2️⃣ Nếu submit form
// -------------------------
if ($submit) {
    $chandoan = $nv_Request->get_string('chandoan', 'post', '');
    $donthuoc = $nv_Request->get_string('donthuoc', 'post', '');

    if (empty(trim($chandoan))) {
        nv_redirect_location(NV_BASE_ADMINURL . "index.php?nv=$module_name&op=diagnosis_edit&id=$id&error=diagnosis");
    } else {
        // Cập nhật (không sửa schedule_id, không sửa ngaytao)
        $stmt = $db->prepare("
            UPDATE $table SET 
                chandoan = :chandoan,
                donthuoc = :donthuoc
            WHERE id = :id
        ");
        $stmt->bindParam(':chandoan', $chandoan, PDO::PARAM_STR);
        $stmt->bindParam(':donthuoc', $donthuoc, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        // Ghi log
        nv_insert_logs(NV_LANG_DATA, $module_name, 'Cập nhật chẩn đoán', 'ID: ' . $id, $admin_info['userid']);

        Header("Location: " . NV_BASE_ADMINURL . "index.php?nv=$module_name&op=diagnosis_list");
        exit();
    }
}


// -------------------------
// 3️⃣ Gán dữ liệu ra template
// -------------------------
$xtpl->assign('ID', $id);
$xtpl->assign('CHANDOAN', htmlspecialchars($row['chandoan']));
$xtpl->assign('DONTHUOC', htmlspecialchars($row['donthuoc']));
$xtpl->assign('NGAYTAO', !empty($row['ngaytao']) ? $row['ngaytao'] : date('Y-m-d'));

// Nạp CSS riêng cho trang chẩn đoán
$diagnoses_edit_css = NV_BASE_SITEURL . 'modules/' . $module_file . '/css/diagnosis_edit.css';
$xtpl->assign('DIAGNOSES_EDIT_CSS', $diagnoses_edit_css);

// Hiển thị thông tin lịch khám (không sửa)
$xtpl->assign('NGAYKHAM', date('d/m/Y', strtotime($row['ngaykham'])));
$xtpl->assign('BENHNHAN', htmlspecialchars($row['patient_name']));
$xtpl->assign('BACSI', htmlspecialchars($row['doctor_name'] ?: 'Chưa phân công'));

// Liên kết hành động và quay lại
$xtpl->assign('ACTION_LINK', NV_BASE_ADMINURL . "index.php?nv=$module_name&op=diagnosis_edit&id=$id");
$xtpl->assign('BACK_LINK', NV_BASE_ADMINURL . "index.php?nv=$module_name&op=diagnosis_list");

$xtpl->parse('main');
$contents = $xtpl->text('main');

// Hiển thị
include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
