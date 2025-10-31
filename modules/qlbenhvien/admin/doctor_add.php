<?php
if (!defined('NV_IS_QLBENHVIEN_ADMIN')) die('Stop!!!');

global $db, $db_config, $nv_Request, $module_name, $module_file, $global_config;

$table = $db_config['prefix'] . "_ql_benhvien_bacsi";
$table_khoa = $db_config['prefix'] . "_ql_benhvien_chuyenkhoa";

// Khởi tạo XTemplate
$xtpl = new XTemplate(
    'doctor_add.tpl',
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
    $chuyenkhoa_id = $nv_Request->get_int('chuyenkhoa_id', 'post', 0);
    $trinhdo = $nv_Request->get_title('trinhdo', 'post', '');
    $email = $nv_Request->get_title('email', 'post', '');
    $sdt = $nv_Request->get_title('sdt', 'post', '');
    $lichlamviec = $nv_Request->get_title('lichlamviec', 'post', '');

    $ngaysinh = normalize_date_for_db($ngaysinh_raw);

    $errors = [];

    if (empty($hoten)) $errors[] = '⚠️ Vui lòng nhập họ và tên.';
    if (empty($ngaysinh)) $errors[] = '⚠️ Ngày sinh không hợp lệ.';

    if (empty($errors)) {
        $stmt = $db->prepare("
            INSERT INTO $table 
            (hoten, ngaysinh, gioitinh, chuyenkhoa_id, trinhdo, email, sdt, lichlamviec)
            VALUES (:hoten, :ngaysinh, :gioitinh, :chuyenkhoa_id, :trinhdo, :email, :sdt, :lichlamviec)
        ");
        $stmt->bindParam(':hoten', $hoten, PDO::PARAM_STR);
        $stmt->bindParam(':ngaysinh', $ngaysinh, PDO::PARAM_STR);
        $stmt->bindValue(':gioitinh', $gioitinh > 0 ? $gioitinh : null, PDO::PARAM_INT);
        $stmt->bindValue(':chuyenkhoa_id', $chuyenkhoa_id > 0 ? $chuyenkhoa_id : null, PDO::PARAM_INT);
        $stmt->bindParam(':trinhdo', $trinhdo, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':sdt', $sdt, PDO::PARAM_STR);
        $stmt->bindParam(':lichlamviec', $lichlamviec, PDO::PARAM_STR);
        $stmt->execute();

        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?nv=' . $module_name . '&op=doctor');
        exit;
    } else {
        $xtpl->assign('ERROR', implode('<br>', $errors));
        $xtpl->parse('main.error');
    }
}

// Lấy danh sách chuyên khoa để dropdown
$sql_khoa = "SELECT id, tenchuyenkhoa FROM $table_khoa ORDER BY tenchuyenkhoa ASC";
$result_khoa = $db->query($sql_khoa)->fetchAll();
foreach ($result_khoa as $k) {
    $xtpl->assign('CHUYENKHOA', $k);
    $xtpl->parse('main.chuyenkhoa_option');
}

$xtpl->assign('ACTION', NV_BASE_ADMINURL . 'index.php?nv=' . $module_name . '&op=doctor_add');
$xtpl->assign('BACK_LINK', NV_BASE_ADMINURL . 'index.php?nv=' . $module_name . '&op=doctor');

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
