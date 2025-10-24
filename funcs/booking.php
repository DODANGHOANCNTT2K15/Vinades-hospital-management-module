<?php
if (!defined('NV_IS_MOD_QLBENHVIEN')) die('Stop!!!');

global $db, $db_config, $nv_Request, $user_info, $module_info, $module_file;

// Nếu chưa đăng nhập, chuyển sang trang login
if (empty($user_info['userid'])) {
    nv_redirect_location(NV_BASE_SITEURL . 'index.php?language=vi&nv=users&op=login');
}

// Các tên bảng
$table_benhnhan = $db_config['prefix'] . "_ql_benhvien_benhnhan";
$table_lichkham  = $db_config['prefix'] . "_ql_benhvien_lichkham";

$notice = '';
// Lấy thông tin bệnh nhân nếu có
$stmt = $db->prepare("SELECT * FROM " . $table_benhnhan . " WHERE userid = :userid LIMIT 1");
$stmt->bindValue(':userid', $user_info['userid'], PDO::PARAM_INT);
$stmt->execute();
$benhnhan = $stmt->fetch();

// Nếu chưa có hồ sơ bệnh nhân, cho nhập giới tính (đơn giản)
if (empty($benhnhan)) {
    if ($nv_Request->isset_request('submit_info', 'post')) {
        $gioitinh = $nv_Request->get_int('gioitinh', 'post', 0);
        $insert = $db->prepare("INSERT INTO " . $table_benhnhan . " (hoten, email, userid, gioitinh, ngaytao) VALUES (:hoten, :email, :userid, :gioitinh, NOW())");
        $insert->bindValue(':hoten', $user_info['full_name'], PDO::PARAM_STR);
        $insert->bindValue(':email', $user_info['email'], PDO::PARAM_STR);
        $insert->bindValue(':userid', $user_info['userid'], PDO::PARAM_INT);
        $insert->bindValue(':gioitinh', $gioitinh, PDO::PARAM_INT);
        $insert->execute();
        $benhnhan_id = $db->lastInsertId();
        // nạp lại $benhnhan
        $stmt = $db->prepare("SELECT * FROM " . $table_benhnhan . " WHERE id = :id LIMIT 1");
        $stmt->bindValue(':id', $benhnhan_id, PDO::PARAM_INT);
        $stmt->execute();
        $benhnhan = $stmt->fetch();
    } else {
        // hiển thị form yêu cầu thông tin (đơn giản)
        $contents  = '<h2>Hoàn tất hồ sơ bệnh nhân</h2>';
        $contents .= '<p>Xin chào <strong>' . htmlspecialchars($user_info['full_name']) . '</strong>. Vui lòng chọn giới tính để hoàn tất hồ sơ.</p>';
        $contents .= '<form method="post">';
        $contents .= '<p><label><input type="radio" name="gioitinh" value="1" required> Nam</label> ';
        $contents .= '<label><input type="radio" name="gioitinh" value="0"> Nữ</label></p>';
        $contents .= '<p><button type="submit" name="submit_info">Lưu thông tin</button></p>';
        $contents .= '</form>';
        include NV_ROOTDIR . '/includes/header.php';
        echo nv_site_theme($contents);
        include NV_ROOTDIR . '/includes/footer.php';
        exit();
    }
} else {
    $benhnhan_id = $benhnhan['id'];
}

// Xử lý đặt lịch
if ($nv_Request->isset_request('submit', 'post')) {
    $ngaykham = $nv_Request->get_string('ngaykham', 'post', '');
    $giokham  = $nv_Request->get_string('giokham', 'post', '');
    $ghichu   = $nv_Request->get_title('ghichu', 'post', '');
    if (!empty($ngaykham) && !empty($giokham)) {
        $stmt = $db->prepare("INSERT INTO " . $table_lichkham . " (benhnhan_id, bacsi_id, ngaykham, giokham, trangthai, ghichu) VALUES (:benhnhan_id, NULL, :ngaykham, :giokham, 'pending', :ghichu)");
        $stmt->bindParam(':benhnhan_id', $benhnhan_id, PDO::PARAM_INT);
        $stmt->bindParam(':ngaykham', $ngaykham, PDO::PARAM_STR);
        $stmt->bindParam(':giokham', $giokham, PDO::PARAM_STR);
        $stmt->bindParam(':ghichu', $ghichu, PDO::PARAM_STR);
        $stmt->execute();
        $notice = '<p style="color:green;">✅ Đặt lịch khám thành công! Chờ xác nhận từ bệnh viện.</p>';
    } else {
        $notice = '<p style="color:red;">⚠️ Vui lòng chọn ngày và giờ khám.</p>';
    }
}

// Hiển thị form + thông tin bệnh nhân
$xtpl = new XTemplate('booking.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);

$xtpl->assign('NOTICE', $notice);
$xtpl->assign('HOTEN', isset($benhnhan['hoten']) ? htmlspecialchars($benhnhan['hoten']) : htmlspecialchars($user_info['full_name']));
$xtpl->assign('EMAIL', isset($benhnhan['email']) ? htmlspecialchars($benhnhan['email']) : htmlspecialchars($user_info['email']));

if (!empty($benhnhan['gioitinh'])) {
    $xtpl->assign('GIOITINH', $benhnhan['gioitinh'] == 1 ? 'Nam' : 'Nữ');
    $xtpl->parse('main.gioitinh');
}
if (!empty($benhnhan['sdt'])) {
    $xtpl->assign('SDT', htmlspecialchars($benhnhan['sdt']));
    $xtpl->parse('main.sdt');
}
if (!empty($benhnhan['diachi'])) {
    $xtpl->assign('DIACHI', htmlspecialchars($benhnhan['diachi']));
    $xtpl->parse('main.diachi');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
