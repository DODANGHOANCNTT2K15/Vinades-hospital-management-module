<?php
if (!defined('NV_IS_QLBENHVIEN_ADMIN')) {
    die('Stop!!!');
}

global $db, $db_config, $nv_Request, $module_name, $module_file, $global_config, $admin_info;

$table_patient = $db_config['prefix'] . "_ql_benhvien_benhnhan";

// Lấy ID bệnh nhân từ URL
$id = $nv_Request->get_int('id', 'get', 0);
$submit = $nv_Request->get_int('submit', 'post', 0);

if ($id <= 0) {
    die('ID không hợp lệ!');
}

// Khởi tạo XTemplate
$xtpl = new XTemplate(
    'patient_edit.tpl',
    NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file
);

// Lấy thông tin bệnh nhân hiện tại
$sql = "SELECT * FROM $table_patient WHERE id = " . $id;
$patient = $db->query($sql)->fetch();

if (!$patient) {
    die('Không tìm thấy bệnh nhân!');
}

$errors = [];

if ($submit) {
    // Lấy dữ liệu từ form
    $hoten = $nv_Request->get_title('hoten', 'post', '');
    $ngaysinh = $nv_Request->get_title('ngaysinh', 'post', '');
    $gioitinh = $nv_Request->get_title('gioitinh', 'post', '');
    $diachi = $nv_Request->get_title('diachi', 'post', '');
    $sdt = $nv_Request->get_title('sdt', 'post', '');
    $email = $nv_Request->get_title('email', 'post', '');

    // Validate cơ bản
    if (empty($hoten)) {
        $errors[] = '⚠️ Vui lòng nhập họ tên bệnh nhân.';
    }
    if (!empty($ngaysinh) && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $ngaysinh)) {
        $errors[] = '⚠️ Ngày sinh không hợp lệ. Định dạng YYYY-MM-DD.';
    }
    if ($gioitinh !== '0' && $gioitinh !== '1') {
        $errors[] = '⚠️ Giới tính không hợp lệ.';
    }
    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = '⚠️ Email không hợp lệ.';
    }

    if (empty($errors)) {
        // Cập nhật dữ liệu bệnh nhân
        $stmt = $db->prepare("
            UPDATE $table_patient SET 
                hoten = :hoten, 
                ngaysinh = :ngaysinh, 
                gioitinh = :gioitinh, 
                diachi = :diachi, 
                sdt = :sdt,
                email = :email
            WHERE id = :id
        ");
        $stmt->bindParam(':hoten', $hoten, PDO::PARAM_STR);
        $stmt->bindParam(':ngaysinh', $ngaysinh, PDO::PARAM_STR);
        $stmt->bindParam(':gioitinh', $gioitinh, PDO::PARAM_STR);
        $stmt->bindParam(':diachi', $diachi, PDO::PARAM_STR);
        $stmt->bindParam(':sdt', $sdt, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        // Ghi log
        nv_insert_logs(NV_LANG_DATA, $module_name, 'Cập nhật bệnh nhân', 'ID: ' . $id, $admin_info['userid']);

        // Redirect về trang chi tiết bệnh nhân
        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?nv=' . $module_name . '&op=patient_detail&id=' . $id);
        exit();
    } else {
        $xtpl->assign('ERROR', implode('<br>', $errors));
        $xtpl->parse('main.error');
    }
}

// Gán giá trị hiện tại cho form
$xtpl->assign('ID', $id);
$xtpl->assign('HOTEN', htmlspecialchars($patient['hoten']));
$xtpl->assign('NGAYSINH', htmlspecialchars($patient['ngaysinh']));
$xtpl->assign('DIA_CHI', htmlspecialchars($patient['diachi']));
$xtpl->assign('SDT', htmlspecialchars($patient['sdt']));
$xtpl->assign('EMAIL', htmlspecialchars($patient['email']));

// Nạp CSS riêng cho trang chẩn đoán
$patient_edit_css = NV_BASE_SITEURL . 'modules/' . $module_file . '/css/patient_edit.css';
$xtpl->assign('PATIENT_EDIT_CSS', $patient_edit_css);

// Radio giới tính
$xtpl->assign('GIOITINH_MALE_CHECKED', $patient['gioitinh'] === 'male' ? 'checked' : '');
$xtpl->assign('GIOITINH_FEMALE_CHECKED', $patient['gioitinh'] === 'female' ? 'checked' : '');

// Link hành động và quay lại
$xtpl->assign('ACTION_LINK', NV_BASE_ADMINURL . 'index.php?nv=' . $module_name . '&op=patient_edit&id=' . $id);
$xtpl->assign('BACK_LINK', NV_BASE_ADMINURL . 'index.php?nv=' . $module_name . '&op=patient_detail&id=' . $id);

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
