<?php
/**
 * schedule_add.php: Thêm mới Lịch Khám Bệnh
 */

if (!defined('NV_IS_QLBENHVIEN_ADMIN')) {
    die('Stop!!!');
}

global $db, $db_config, $nv_Request, $module_name, $module_file, $global_config, $lang_module, $admin_info;

$page_title = $lang_module['schedule_add_title'] ?? 'Thêm Lịch Khám Mới';

$table = $db_config['prefix'] . "_ql_benhvien_lichkham";
$table_benhnhan = $db_config['prefix'] . "_ql_benhvien_benhnhan";
$table_bacsi = $db_config['prefix'] . "_ql_benhvien_bacsi";

$error = '';
$data = [
    'benhnhan_id' => 0,
    'bacsi_id' => 0,
    'ngaykham' => date('Y-m-d'),
    'giokham' => '08:00',
    'lydo' => '',
    'trangthai' => 'pending',
    'chandoan' => '',
    'donthuoc' => ''
];

// Lấy danh sách Bệnh nhân và Bác sĩ
$benhnhan_list = $db->query("SELECT id, hoten FROM " . $table_benhnhan . " ORDER BY hoten ASC")->fetchAll();
$bacsi_list = $db->query("SELECT id, hoten FROM " . $table_bacsi . " ORDER BY hoten ASC")->fetchAll();


// -------------------------
// Xử lý POST Form
// -------------------------
if ($nv_Request->isset_request('submit', 'post')) {
    $data['benhnhan_id'] = $nv_Request->get_int('benhnhan_id', 'post', 0);
    $data['bacsi_id'] = $nv_Request->get_int('bacsi_id', 'post', 0);
    $data['ngaykham'] = $nv_Request->get_string('ngaykham', 'post', '');
    $data['giokham'] = $nv_Request->get_string('giokham', 'post', '');
    $data['lydo'] = $nv_Request->get_editor('lydo', '', NV_ALLOWED_HTML_TAGS);
    $data['trangthai'] = $nv_Request->get_string('trangthai', 'post', 'pending');
    $data['chandoan'] = $nv_Request->get_editor('chandoan', '', NV_ALLOWED_HTML_TAGS);
    $data['donthuoc'] = $nv_Request->get_editor('donthuoc', '', NV_ALLOWED_HTML_TAGS);
    
    // Kiểm tra dữ liệu
    if ($data['benhnhan_id'] <= 0) {
        $error = 'Vui lòng chọn bệnh nhân.';
    } elseif (empty($data['ngaykham']) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $data['ngaykham'])) {
        $error = 'Ngày khám không hợp lệ.';
    } elseif (empty($data['giokham']) || !preg_match('/^\d{2}:\d{2}$/', $data['giokham'])) {
        $error = 'Giờ khám không hợp lệ.';
    }
    
    if (empty($error)) {
        try {
            $stmt = $db->prepare("INSERT INTO " . $table . " (
                benhnhan_id, bacsi_id, ngaykham, giokham, lydo, trangthai, chandoan, donthuoc
            ) VALUES (
                :benhnhan_id, :bacsi_id, :ngaykham, :giokham, :lydo, :trangthai, :chandoan, :donthuoc
            )");

            $stmt->bindParam(':benhnhan_id', $data['benhnhan_id'], PDO::PARAM_INT);
            $stmt->bindParam(':bacsi_id', $data['bacsi_id'], PDO::PARAM_INT);
            $stmt->bindParam(':ngaykham', $data['ngaykham']);
            $stmt->bindParam(':giokham', $data['giokham']);
            $stmt->bindParam(':lydo', $data['lydo']);
            $stmt->bindParam(':trangthai', $data['trangthai']);
            $stmt->bindParam(':chandoan', $data['chandoan']);
            $stmt->bindParam(':donthuoc', $data['donthuoc']);
            
            $stmt->execute();
            $id = $db->lastInsertId();
            
            nv_insert_logs(NV_LANG_DATA, $module_name, 'Thêm lịch khám', 'ID: ' . $id, $admin_info['userid']);
            
            // Chuyển hướng về trang chi tiết vừa tạo
            Header("Location: " . NV_BASE_ADMINURL . "index.php?nv=$module_name&op=schedule&action=detail&id=" . $id);
            exit();

        } catch (PDOException $e) {
            $error = "Lỗi Database: " . $e->getMessage();
        }
    }
}


// -------------------------
// Hiển thị Form
// -------------------------

$xtpl = new XTemplate(
    'schedule_add.tpl',
    NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file
);

// Hiển thị lỗi (nếu có)
if (!empty($error)) {
    $xtpl->assign('ERROR', $error);
    $xtpl->parse('main.error');
}

$xtpl->assign('FORM_ACTION', NV_BASE_ADMINURL . "index.php?nv=$module_name&op=schedule_add");
$xtpl->assign('DATA', $data);
$xtpl->assign('MODULE_NAME', $module_name);

// 1. Dữ liệu Bệnh nhân
$benhnhan_select = '<option value="0">--- Chọn Bệnh nhân ---</option>';
foreach ($benhnhan_list as $bn) {
    $selected = ($bn['id'] == $data['benhnhan_id']) ? ' selected="selected"' : '';
    $benhnhan_select .= '<option value="' . $bn['id'] . '"' . $selected . '>' . $bn['hoten'] . '</option>';
}
$xtpl->assign('BENHNHAN_SELECT', $benhnhan_select);

// 2. Dữ liệu Bác sĩ
$bacsi_select = '<option value="0">--- Không phân công ---</option>';
foreach ($bacsi_list as $bs) {
    $selected = ($bs['id'] == $data['bacsi_id']) ? ' selected="selected"' : '';
    $bacsi_select .= '<option value="' . $bs['id'] . '"' . $selected . '>' . $bs['hoten'] . '</option>';
}
$xtpl->assign('BACSI_SELECT', $bacsi_select);

// 3. Trạng thái
$xtpl->assign('STATUS_PENDING_CHECKED', ($data['trangthai'] == 'pending' ? 'checked="checked"' : ''));
$xtpl->assign('STATUS_CONFIRMED_CHECKED', ($data['trangthai'] == 'confirmed' ? 'checked="checked"' : ''));
$xtpl->assign('STATUS_CANCELLED_CHECKED', ($data['trangthai'] == 'cancelled' ? 'checked="checked"' : ''));

// 4. Editor cho Lý do khám
$xtpl->assign('LYDO_EDITOR', nv_editor_html('lydo', $data['lydo']));
$xtpl->assign('CHANDOAN_EDITOR', nv_editor_html('chandoan', $data['chandoan']));
$xtpl->assign('DONTHUOC_EDITOR', nv_editor_html('donthuoc', $data['donthuoc']));


$xtpl->parse('main');
$contents = $xtpl->text('main');

return nv_admin_theme($contents);