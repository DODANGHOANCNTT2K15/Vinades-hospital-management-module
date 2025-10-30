<?php
if (!defined('NV_IS_MOD_QLBENHVIEN')) die('Stop!!!');

global $db, $db_config, $nv_Request, $user_info;

if (empty($user_info['userid'])) {
    nv_redirect_location(NV_BASE_SITEURL . 'index.php?language=vi&nv=users&op=login');
}

$table_benhnhan = $db_config['prefix'] . "_ql_benhvien_benhnhan";

// Lấy thông tin bệnh nhân theo userid
$sql = "SELECT * FROM " . $table_benhnhan . " WHERE userid = :userid";
$stmt = $db->prepare($sql);
$stmt->bindValue(':userid', $user_info['userid'], PDO::PARAM_INT);
$stmt->execute();
$benhnhan = $stmt->fetch();

// --- Nếu chưa có bản ghi bệnh nhân thì tạo tự động dựa vào $user_info ---
if (empty($benhnhan)) {
    $gioitinh_user = isset($user_info['gender']) ? (int)$user_info['gender'] : 1;

    $insert = $db->prepare("
        INSERT INTO " . $table_benhnhan . " (hoten, email, userid, gioitinh, ngaytao)
        VALUES (:hoten, :email, :userid, :gioitinh, NOW())
    ");
    $insert->bindValue(':hoten', $user_info['full_name'], PDO::PARAM_STR);
    $insert->bindValue(':email', $user_info['email'], PDO::PARAM_STR);
    $insert->bindValue(':userid', $user_info['userid'], PDO::PARAM_INT);
    $insert->bindValue(':gioitinh', $gioitinh_user, PDO::PARAM_INT);

    try {
        $insert->execute();
        $benhnhan_id = $db->lastInsertId();
        $benhnhan = [
            'id' => $benhnhan_id,
            'hoten' => $user_info['full_name'],
            'email' => $user_info['email'],
            'gioitinh' => $gioitinh_user,
            'diachi' => '',
            'sdt' => ''
        ];
    } catch (PDOException $e) {
        $notice = '<p style="color:red;">⚠️ Không thể tạo hồ sơ bệnh nhân. Vui lòng thử lại sau.</p>';
        include NV_ROOTDIR . '/includes/header.php';
        echo nv_site_theme($notice);
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

    if (!empty($ngaykham) && !empty($giokham) && !empty($ghichu)) {
        // --- Chuẩn hóa định dạng ngày ---
        if (strpos($ngaykham, '/') !== false) {
            [$d, $m, $y] = explode('/', $ngaykham);
            $ngaykham = "$y-$m-$d";
        }

        // --- Giới hạn thời gian: chỉ cho đặt từ sau 2 ngày, trong 7 ngày kế tiếp ---
        $minDate = date('Y-m-d', strtotime('+2 days'));
        $maxDate = date('Y-m-d', strtotime('+8 days'));

        if ($ngaykham < $minDate) {
            $notice = '<p style="color:red;">⚠️ Ngày khám phải sau ít nhất 2 ngày kể từ hôm nay.</p>';
        } elseif ($ngaykham > $maxDate) {
            $notice = '<p style="color:red;">⚠️ Bạn chỉ có thể đặt lịch trong 7 ngày kế tiếp (tính từ sau 2 ngày nữa).</p>';
        } else {
            // --- Nếu hợp lệ, lưu lịch ---
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

            nv_redirect_location(
                NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . 
                '&' . NV_NAME_VARIABLE . '=' . $module_name . 
                '&' . NV_OP_VARIABLE . '=historyBooking'
            );
            exit();
        }
    } else {
        $notice = '<p style="color:red;">⚠️ Vui lòng nhập đầy đủ ngày, giờ khám và các triệu chứng!</p>';
    }
}

// --- Render giao diện bằng XTemplate ---
$xtpl = new XTemplate('booking.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);

// Nạp CSS riêng cho trang booking
$booking_css = NV_BASE_SITEURL . 'modules/' . $module_file . '/css/booking.css';
$xtpl->assign('BOOKING_CSS', $booking_css);
$xtpl->assign('NOTICE', $notice);
$xtpl->assign('HOTEN', htmlspecialchars($benhnhan['hoten']));
$xtpl->assign('EMAIL', htmlspecialchars($benhnhan['email']));

// ✅ Tạo dropdown gồm 7 ngày, bắt đầu từ sau 2 ngày kể từ hôm nay
$startDate = strtotime('+2 days');
for ($i = 0; $i < 7; $i++) {
    $date = date('Y-m-d', strtotime("+$i day", $startDate));
    $label = date('d/m/Y', strtotime($date));
    $xtpl->assign('NGAYKHAM_VALUE', $date);
    $xtpl->assign('NGAYKHAM_TEXT', $label);
    $xtpl->parse('main.ngaykham');
}

// --- Tạo dropdown giờ khám ---
$giokham_options = [];

// Khung sáng 7h - 11h
for ($h = 7; $h <= 11; $h++) {
    $giokham = sprintf("%02d:00", $h);
    $giokham_options[] = ['value' => $giokham, 'text' => $giokham];
}

// Khung chiều 13h - 17h
for ($h = 13; $h <= 17; $h++) {
    $giokham = sprintf("%02d:00", $h);
    $giokham_options[] = ['value' => $giokham, 'text' => $giokham];
}

// Gán vào XTemplate
foreach ($giokham_options as $option) {
    $xtpl->assign('GIOKHAM_VALUE', $option['value']);
    $xtpl->assign('GIOKHAM_TEXT', $option['text']);
    $xtpl->parse('main.giokham');
}

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
