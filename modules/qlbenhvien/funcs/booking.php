<?php
if (!defined('NV_IS_MOD_QLBENHVIEN')) die('Stop!!!');

global $db, $db_config, $nv_Request, $user_info;

if (empty($user_info['userid'])) {
    nv_redirect_location(NV_BASE_SITEURL . 'index.php?language=vi&nv=users&op=login');
}

$table_benhnhan = $db_config['prefix'] . "_ql_benhvien_benhnhan";

$sql = "SELECT * FROM " . $table_benhnhan . " WHERE userid = :userid";
$stmt = $db->prepare($sql);
$stmt->bindValue(':userid', $user_info['userid'], PDO::PARAM_INT);
$stmt->execute();
$benhnhan = $stmt->fetch();

if (empty($benhnhan)) {
    if ($nv_Request->isset_request('submit_info', 'post')) {
        $gioitinh = $nv_Request->get_int('gioitinh', 'post', 0);

        $insert = $db->prepare("
            INSERT INTO " . $table_benhnhan . " (hoten, email, userid, gioitinh, ngaytao)
            VALUES (:hoten, :email, :userid, :gioitinh, NOW())
        ");
        $insert->bindValue(':hoten', $user_info['full_name'], PDO::PARAM_STR);
        $insert->bindValue(':email', $user_info['email'], PDO::PARAM_STR);
        $insert->bindValue(':userid', $user_info['userid'], PDO::PARAM_INT);
        $insert->bindValue(':gioitinh', $gioitinh, PDO::PARAM_INT);
        $insert->execute();

        $benhnhan_id = $db->lastInsertId();
        $benhnhan = [
            'id' => $benhnhan_id,
            'hoten' => $user_info['full_name'],
            'email' => $user_info['email'],
            'gioitinh' => $gioitinh,
            'diachi' => '',
            'sdt' => ''
        ];
    } else {
        // form giới tính lần đầu — vẫn giữ inline hoặc tách riêng form_gioitinh.tpl
        $contents  = '<h2>Thông tin cá nhân</h2>';
        $contents .= '<p>Xin chào <strong>' . htmlspecialchars($user_info['full_name']) . '</strong>, bạn cần cung cấp giới tính để hoàn tất hồ sơ bệnh nhân.</p>';
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

$table_lichkham = $db_config['prefix'] . "_ql_benhvien_lichkham";
$notice = '';

if ($nv_Request->isset_request('submit', 'post')) {
    $ngaykham = $nv_Request->get_string('ngaykham', 'post', '');
    $giokham = $nv_Request->get_string('giokham', 'post', '');
    $ghichu  = $nv_Request->get_title('ghichu', 'post', '');

    if (!empty($ngaykham) && !empty($giokham)) {
        $sql = "
            INSERT INTO " . $table_lichkham . " 
            (benhnhan_id, bacsi_id, ngaykham, giokham, trangthai, ghichu)
            VALUES (:benhnhan_id, NULL, :ngaykham, :giokham, 'pending', :ghichu)
        ";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':benhnhan_id', $benhnhan_id, PDO::PARAM_INT);
        $stmt->bindParam(':ngaykham', $ngaykham, PDO::PARAM_STR);
        $stmt->bindParam(':giokham', $giokham, PDO::PARAM_STR);
        $stmt->bindParam(':ghichu', $ghichu, PDO::PARAM_STR);
        $stmt->execute();

        $notice = '<p style="color:green;">✅ Đặt lịch khám thành công! Hãy chờ bệnh viện sắp xếp bác sĩ.</p>';
    } else {
        $notice = '<p style="color:red;">⚠️ Vui lòng nhập đầy đủ ngày và giờ khám!</p>';
    }
}

// --- Render giao diện bằng XTemplate ---
$xtpl = new XTemplate('booking.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);

$xtpl->assign('NOTICE', $notice);
$xtpl->assign('HOTEN', htmlspecialchars($benhnhan['hoten']));
$xtpl->assign('EMAIL', htmlspecialchars($benhnhan['email']));

if (isset($benhnhan['gioitinh'])) {
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
