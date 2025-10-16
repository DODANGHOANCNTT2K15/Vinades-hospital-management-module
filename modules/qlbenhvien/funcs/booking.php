<?php
if (!defined('NV_IS_MOD_QLBENHVIEN')) die('Stop!!!');

global $db, $db_config, $nv_Request, $user_info;

// Nếu chưa đăng nhập thì chuyển hướng về trang đăng nhập
if (empty($user_info['userid'])) {
    nv_redirect_location(NV_BASE_SITEURL . 'index.php?language=vi&nv=users&op=login');
}

// Bảng bệnh nhân
$table_benhnhan = $db_config['prefix'] . "_ql_benhvien_benhnhan";

// Tìm bệnh nhân theo userid
$sql = "SELECT * FROM " . $table_benhnhan . " WHERE userid = :userid";
$stmt = $db->prepare($sql);
$stmt->bindValue(':userid', $user_info['userid'], PDO::PARAM_INT);
$stmt->execute();
$benhnhan = $stmt->fetch();

// Nếu chưa có thì thêm mới vào bảng bệnh nhân
if (empty($benhnhan)) {
    // Nếu người dùng gửi form chọn giới tính lần đầu
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
        // Nếu chưa có bệnh nhân, hiển thị form nhập giới tính lần đầu
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

// Bảng lịch khám
$table_lichkham = $db_config['prefix'] . "_ql_benhvien_lichkham";
$notice = '';

// Khi người dùng gửi form đặt lịch
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

// --- Giao diện hiển thị ---
$contents  = '<h2>Đặt lịch khám bệnh viện</h2>' . $notice;

// Thông tin bệnh nhân
$contents .= '<div style="border:1px solid #ccc;padding:10px;margin-bottom:10px;">';
$contents .= '<h3>Thông tin bệnh nhân</h3>';
$contents .= '<p><strong>Họ tên:</strong> ' . htmlspecialchars($benhnhan['hoten']) . '</p>';
$contents .= '<p><strong>Email:</strong> ' . htmlspecialchars($benhnhan['email']) . '</p>';
if (isset($benhnhan['gioitinh'])) {
    $contents .= '<p><strong>Giới tính:</strong> ' . ($benhnhan['gioitinh'] == 1 ? 'Nam' : 'Nữ') . '</p>';
}
if (!empty($benhnhan['sdt'])) {
    $contents .= '<p><strong>SĐT:</strong> ' . htmlspecialchars($benhnhan['sdt']) . '</p>';
}
if (!empty($benhnhan['diachi'])) {
    $contents .= '<p><strong>Địa chỉ:</strong> ' . htmlspecialchars($benhnhan['diachi']) . '</p>';
}
$contents .= '</div>';

// Form đặt lịch
$contents .= '<form method="post">';
$contents .= '<p><label>Ngày khám: <input type="date" name="ngaykham" required></label></p>';
$contents .= '<p><label>Giờ khám: <input type="time" name="giokham" required></label></p>';
$contents .= '<p><label>Ghi chú: <textarea name="ghichu" rows="3" cols="40" placeholder="Nhập nội dung ghi chú..."></textarea></label></p>';
$contents .= '<p><button type="submit" name="submit">Đặt lịch</button></p>';
$contents .= '</form>';

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
