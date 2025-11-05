<?php
if (!defined('NV_IS_QLBENHVIEN_ADMIN')) {
    die('Stop!!!');
}

global $db, $db_config, $nv_Request, $module_name, $module_file, $global_config, $admin_info;

// Tên bảng
$table = $db_config['prefix'] . "_ql_benhvien_lichkham";
$table_benhnhan = $db_config['prefix'] . "_ql_benhvien_benhnhan";
$table_bacsi = $db_config['prefix'] . "_ql_benhvien_bacsi";
$table_chuyenkhoa = $db_config['prefix'] . "_ql_benhvien_chuyenkhoa";

// Lấy ID từ URL
$id = $nv_Request->get_int('id', 'get', 0);
$submit = $nv_Request->get_int('submit', 'post', 0);

$xtpl = new XTemplate(
    'schedule_edit.tpl',
    NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file
);

if ($id <= 0) {
    die('ID không hợp lệ!');
}

// -------------------------
// 1️⃣ Lấy dữ liệu lịch khám
// -------------------------
$sql = "SELECT * FROM " . $table . " WHERE id=" . $id;
$row = $db->query($sql)->fetch();

if (empty($row)) {
    die('Không tìm thấy lịch khám!');
}

// -------------------------
// 2️⃣ Nếu có submit form
// -------------------------
if ($submit) {
    $benhnhan_id = $nv_Request->get_int('benhnhan_id', 'post', 0);
    $bacsi_id = $nv_Request->get_int('bacsi_id', 'post', 0);
    $ngaykham = $nv_Request->get_title('ngaykham', 'post', '');
    $giokham = $nv_Request->get_title('giokham', 'post', '');
    $ghichu = $nv_Request->get_title('ghichu', 'post', '');
    $trangthai = $nv_Request->get_title('trangthai', 'post', 'pending');

    $stmt = $db->prepare("
        UPDATE $table 
        SET benhnhan_id=:benhnhan_id, bacsi_id=:bacsi_id, ngaykham=:ngaykham, giokham=:giokham, ghichu=:ghichu, trangthai=:trangthai 
        WHERE id=:id
    ");
    $stmt->bindParam(':benhnhan_id', $benhnhan_id, PDO::PARAM_INT);
    if ($bacsi_id > 0) {
        $stmt->bindParam(':bacsi_id', $bacsi_id, PDO::PARAM_INT);
    } else {
        $stmt->bindValue(':bacsi_id', null, PDO::PARAM_NULL);
    }
    $stmt->bindParam(':ngaykham', $ngaykham, PDO::PARAM_STR);
    $stmt->bindParam(':giokham', $giokham, PDO::PARAM_STR);
    $stmt->bindParam(':ghichu', $ghichu, PDO::PARAM_STR);
    $stmt->bindParam(':trangthai', $trangthai, PDO::PARAM_STR);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    // Ghi log
    nv_insert_logs(NV_LANG_DATA, $module_name, 'Cập nhật lịch khám', 'ID: ' . $id, $admin_info['userid']);

    // Chuyển hướng về chi tiết lịch khám
    Header("Location: " . NV_BASE_ADMINURL . "index.php?nv=$module_name&op=schedule&action=detail&id=" . $id);
    exit();
}

// -------------------------
// 3️⃣ Lấy danh sách bệnh nhân và bác sĩ
// -------------------------

// Danh sách bệnh nhân
$patients = $db->query("SELECT id, hoten, sdt FROM $table_benhnhan ORDER BY hoten ASC")->fetchAll();

// Danh sách bác sĩ (JOIN với chuyên khoa)
$sql_doctors = "
    SELECT b.id, b.hoten, c.tenchuyenkhoa 
    FROM $table_bacsi AS b
    LEFT JOIN $table_chuyenkhoa AS c ON b.chuyenkhoa_id = c.id
    ORDER BY b.hoten ASC
";
$doctors = $db->query($sql_doctors)->fetchAll();

// -------------------------
// 4️⃣ Gán giá trị cho form
// -------------------------
$xtpl->assign('ID', $id);
$xtpl->assign('NGAYKHAM', !empty($row['ngaykham']) ? $row['ngaykham'] : '');
$xtpl->assign('GIOKHAM', !empty($row['giokham']) ? $row['giokham'] : '');
$xtpl->assign('GHICHU', $row['ghichu']);

// Dropdown bệnh nhân
foreach ($patients as $p) {
    $p['selected'] = ($p['id'] == $row['benhnhan_id']) ? 'selected="selected"' : '';
    $xtpl->assign('PATIENT', $p);
    $xtpl->parse('main.patient_option');
}

// Dropdown bác sĩ
$xtpl->assign('BS_SELECTED', $row['bacsi_id'] == 0 ? 'selected' : '');
foreach ($doctors as $d) {
    $d['selected'] = ($d['id'] == $row['bacsi_id']) ? 'selected="selected"' : '';
    $d['label'] = $d['hoten'] . (!empty($d['tenchuyenkhoa']) ? ' (' . $d['tenchuyenkhoa'] . ')' : '');
    $xtpl->assign('DOCTOR', $d);
    $xtpl->parse('main.doctor_option');
}

// Dropdown trạng thái
$status_list = [
    'pending' => 'Chờ xác nhận',
    'confirmed' => 'Đã xác nhận',
    'cancelled' => 'Đã hủy'
];
foreach ($status_list as $key => $text) {
    $xtpl->assign('STATUS_KEY', $key);
    $xtpl->assign('STATUS_TEXT', $text);
    $xtpl->assign('STATUS_SELECTED', ($row['trangthai'] == $key) ? 'selected="selected"' : '');
    $xtpl->parse('main.status_option');
}

// Nạp CSS riêng cho trang chẩn đoán
$schedule_edit_css = NV_BASE_SITEURL . 'modules/' . $module_file . '/css/schedule_edit.css';
$xtpl->assign('SCHEDULE_EDIT_CSS', $schedule_edit_css);

// Liên kết hành động
$xtpl->assign('ACTION_LINK', NV_BASE_ADMINURL . "index.php?nv=$module_name&op=schedule_edit&id=$id");
$xtpl->assign('BACK_LINK', NV_BASE_ADMINURL . "index.php?nv=$module_name&op=schedule&action=detail&id=$id");

$xtpl->parse('main');
$contents = $xtpl->text('main');

// Hiển thị
include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
