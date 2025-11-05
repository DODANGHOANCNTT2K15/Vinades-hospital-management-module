<?php
if (!defined('NV_IS_QLBENHVIEN_ADMIN')) {
    die('Stop!!!');
}

global $db, $db_config, $nv_Request, $module_name, $module_file, $global_config, $admin_info;

$table_doctor = $db_config['prefix'] . "_ql_benhvien_bacsi";
$table_spec   = $db_config['prefix'] . "_ql_benhvien_chuyenkhoa";
$table_users  = $db_config['prefix'] . "_users";

$id = $nv_Request->get_int('id', 'get', 0);
$submit = $nv_Request->get_int('submit', 'post', 0);

if ($id <= 0) {
    die('ID không hợp lệ!');
}

$sql = "SELECT * FROM $table_doctor WHERE id = " . $id;
$doctor = $db->query($sql)->fetch();

if (!$doctor) {
    die('Không tìm thấy bác sĩ!');
}

$xtpl = new XTemplate(
    'doctor_edit.tpl',
    NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file
);

$errors = [];

// ✅ Xử lý khi submit
if ($submit) {
    $hoten = $nv_Request->get_title('hoten', 'post', '');
    $ngaysinh = $nv_Request->get_title('ngaysinh', 'post', '');
    $gioitinh = $nv_Request->get_title('gioitinh', 'post', '');
    $chuyenkhoa_id = $nv_Request->get_int('chuyenkhoa_id', 'post', 0);
    $trinhdo = $nv_Request->get_title('trinhdo', 'post', '');
    $lichlamviec = $nv_Request->get_title('lichlamviec', 'post', '');
    $email = $nv_Request->get_title('email', 'post', '');
    $sdt = $nv_Request->get_title('sdt', 'post', '');
    $userid = $nv_Request->get_int('userid', 'post', 0);

    // 🧩 Kiểm tra dữ liệu
    if (empty($hoten)) {
        $errors[] = '⚠️ Vui lòng nhập họ tên bác sĩ.';
    }
    if (!empty($ngaysinh) && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $ngaysinh)) {
        $errors[] = '⚠️ Ngày sinh không hợp lệ (YYYY-MM-DD).';
    }
    if (!in_array($gioitinh, ['1', '2', '3'])) {
        $errors[] = '⚠️ Giới tính không hợp lệ.';
    }
    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = '⚠️ Email không hợp lệ.';
    }

    if (empty($errors)) {
        $stmt = $db->prepare("
            UPDATE $table_doctor SET 
                hoten = :hoten,
                ngaysinh = :ngaysinh,
                gioitinh = :gioitinh,
                chuyenkhoa_id = :chuyenkhoa_id,
                trinhdo = :trinhdo,
                lichlamviec = :lichlamviec,
                email = :email,
                sdt = :sdt,
                userid = :userid
            WHERE id = :id
        ");

        $stmt->bindParam(':hoten', $hoten, PDO::PARAM_STR);
        $stmt->bindParam(':ngaysinh', $ngaysinh, PDO::PARAM_STR);
        $stmt->bindParam(':gioitinh', $gioitinh, PDO::PARAM_STR);
        $stmt->bindParam(':chuyenkhoa_id', $chuyenkhoa_id, PDO::PARAM_INT);
        $stmt->bindParam(':trinhdo', $trinhdo, PDO::PARAM_STR);
        $stmt->bindParam(':lichlamviec', $lichlamviec, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':sdt', $sdt, PDO::PARAM_STR);
        $stmt->bindParam(':userid', $userid, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        nv_insert_logs(NV_LANG_DATA, $module_name, 'Cập nhật bác sĩ', 'ID: ' . $id, $admin_info['userid']);

        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?nv=' . $module_name . '&op=doctor');
        exit();
    } else {
        $xtpl->assign('ERROR', implode('<br>', $errors));
        $xtpl->parse('main.error');
    }
}

// Nạp CSS riêng cho trang chẩn đoán
$doctor_edit_css = NV_BASE_SITEURL . 'modules/' . $module_file . '/css/doctor_edit.css';
$xtpl->assign('DOCTOR_EDIT_CSS', $doctor_edit_css);

// 🧾 Gán dữ liệu ra form
$xtpl->assign('ID', $id);
$xtpl->assign('HOTEN', htmlspecialchars($doctor['hoten']));
$xtpl->assign('NGAYSINH', htmlspecialchars($doctor['ngaysinh']));
$xtpl->assign('TRINHDO', htmlspecialchars($doctor['trinhdo']));
$xtpl->assign('LICHLAMVIEC', htmlspecialchars($doctor['lichlamviec']));
$xtpl->assign('EMAIL', htmlspecialchars($doctor['email']));
$xtpl->assign('SDT', htmlspecialchars($doctor['sdt']));

// Giới tính radio
$xtpl->assign('GT1', $doctor['gioitinh'] == 1 ? 'checked' : '');
$xtpl->assign('GT2', $doctor['gioitinh'] == 2 ? 'checked' : '');
$xtpl->assign('GT3', $doctor['gioitinh'] == 3 ? 'checked' : '');

// Dropdown chuyên khoa
$sql = "SELECT id, tenchuyenkhoa FROM $table_spec ORDER BY tenchuyenkhoa ASC";
foreach ($db->query($sql)->fetchAll() as $spec) {
    $spec['selected'] = ($doctor['chuyenkhoa_id'] == $spec['id']) ? 'selected' : '';
    $xtpl->assign('SPEC', $spec);
    $xtpl->parse('main.spec_option');
}

// Dropdown user (User ID)
$sql_user = "SELECT userid, username FROM $table_users ORDER BY username ASC";
foreach ($db->query($sql_user)->fetchAll() as $user) {
    $user['selected'] = ($doctor['userid'] == $user['userid']) ? 'selected' : '';
    $user['display'] = $user['userid'] . " - " . $user['username'];
    $xtpl->assign('USER', $user);
    $xtpl->parse('main.user_option');
}

// Link hành động
$xtpl->assign('ACTION_LINK', NV_BASE_ADMINURL . 'index.php?nv=' . $module_name . '&op=doctor_edit&id=' . $id);
$xtpl->assign('BACK_LINK', NV_BASE_ADMINURL . 'index.php?nv=' . $module_name . '&op=doctor');

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
