<?php
if (!defined('NV_IS_QLBENHVIEN_ADMIN')) die('Stop!!!');

global $db, $db_config, $nv_Request, $module_name, $module_file, $global_config;

$table = $db_config['prefix'] . "_ql_benhvien_benhnhan";

// Khởi tạo XTemplate
$xtpl = new XTemplate(
    'patient_add.tpl',
    NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file
);

// Hàm chuẩn hóa ngày
function normalize_date_for_db($date_str)
{
    $date_str = trim($date_str);
    if (empty($date_str)) return '';

    if (preg_match('/^(\d{2})\/(\d{2})\/(\d{4})$/', $date_str, $m)) {
        return "{$m[3]}-{$m[2]}-{$m[1]}";
    }

    if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $date_str)) {
        return $date_str;
    }

    $t = strtotime($date_str);
    return $t ? date('Y-m-d', $t) : '';
}

// Nếu submit form
if ($nv_Request->isset_request('save', 'post')) {
    $hoten = $nv_Request->get_title('hoten', 'post', '');
    $ngaysinh_raw = $nv_Request->get_title('ngaysinh', 'post', '');
    $gioitinh = $nv_Request->get_int('gioitinh', 'post', 0);
    $diachi = $nv_Request->get_title('diachi', 'post', '');
    $sdt = $nv_Request->get_title('sdt', 'post', '');
    $email = $nv_Request->get_title('email', 'post', '');
    $userid = isset($admin_info['userid']) ? $admin_info['userid'] : 0;
    $ngaytao = date('Y-m-d H:i:s');

    $ngaysinh = normalize_date_for_db($ngaysinh_raw);

    $errors = [];

    if (empty($hoten)) $errors[] = '⚠️ Vui lòng nhập họ và tên.';
    if (empty($ngaysinh)) $errors[] = '⚠️ Ngày sinh không hợp lệ.';
    if (empty($sdt)) $errors[] = '⚠️ Vui lòng nhập số điện thoại.';

    if (empty($errors)) {
        $stmt = $db->prepare("
            INSERT INTO $table 
            (hoten, ngaysinh, gioitinh, diachi, sdt, email, userid, ngaytao)
            VALUES (:hoten, :ngaysinh, :gioitinh, :diachi, :sdt, :email, :userid, :ngaytao)
        ");
        $stmt->bindParam(':hoten', $hoten, PDO::PARAM_STR);
        $stmt->bindParam(':ngaysinh', $ngaysinh, PDO::PARAM_STR);
        $stmt->bindValue(':gioitinh', $gioitinh > 0 ? $gioitinh : null, PDO::PARAM_INT);
        $stmt->bindParam(':diachi', $diachi, PDO::PARAM_STR);
        $stmt->bindParam(':sdt', $sdt, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':userid', $userid, PDO::PARAM_INT);
        $stmt->bindParam(':ngaytao', $ngaytao, PDO::PARAM_STR);
        $stmt->execute();

        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?nv=' . $module_name . '&op=patient');
        exit;
    } else {
        $xtpl->assign('ERROR', implode('<br>', $errors));
        $xtpl->parse('main.error');
    }
}

// Nạp CSS riêng cho trang chẩn đoán
$patient_add_css = NV_BASE_SITEURL . 'modules/' . $module_file . '/css/patient_add.css';
$xtpl->assign('PATIENT_ADD_CSS', $patient_add_css);

$xtpl->assign('ACTION', NV_BASE_ADMINURL . 'index.php?nv=' . $module_name . '&op=patient_add');
$xtpl->assign('BACK_LINK', NV_BASE_ADMINURL . 'index.php?nv=' . $module_name . '&op=patient');

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
