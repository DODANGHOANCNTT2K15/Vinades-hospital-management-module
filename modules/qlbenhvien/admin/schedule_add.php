<?php
if (!defined('NV_IS_QLBENHVIEN_ADMIN')) die('Stop!!!');

global $db, $db_config, $nv_Request, $module_name, $module_file, $global_config;

$table = $db_config['prefix'] . "_ql_benhvien_lichkham";
$table_benhnhan = $db_config['prefix'] . "_ql_benhvien_benhnhan";
$table_bacsi = $db_config['prefix'] . "_ql_benhvien_bacsi";
$table_khoa = $db_config['prefix'] . "_ql_benhvien_chuyenkhoa"; // bảng chứa tên khoa

// Khởi tạo XTemplate
$xtpl = new XTemplate(
    'schedule_add.tpl',
    NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file
);

// --- Hàm chuẩn hoá ngày về định dạng lưu DB (yyyy-mm-dd)
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

// Nếu người dùng gửi form
if ($nv_Request->isset_request('save', 'post')) {
    $benhnhan_id = $nv_Request->get_int('benhnhan_id', 'post', 0);
    $bacsi_id = $nv_Request->get_int('bacsi_id', 'post', 0);
    $ngaykham_raw = $nv_Request->get_title('ngaykham', 'post', '');
    $giokham = $nv_Request->get_title('giokham', 'post', '');
    $ghichu = $nv_Request->get_title('ghichu', 'post', '');

    $ngaykham = normalize_date_for_db($ngaykham_raw);

    $errors = [];

    // Kiểm tra dữ liệu bắt buộc
    if ($benhnhan_id <= 0) $errors[] = '⚠️ Vui lòng chọn bệnh nhân.';
    if (empty($ngaykham)) $errors[] = '⚠️ Ngày khám không hợp lệ.';
    if (empty($giokham)) $errors[] = '⚠️ Vui lòng chọn giờ khám.';

    // Logic kiểm tra ngày khám hợp lệ (giống booking)
    if (empty($errors)) {
        $minDate = date('Y-m-d', strtotime('+2 days'));
        $maxDate = date('Y-m-d', strtotime('+8 days'));

        if ($ngaykham < $minDate) {
            $errors[] = '⚠️ Ngày khám phải sau ít nhất 2 ngày kể từ hôm nay.';
        } elseif ($ngaykham > $maxDate) {
            $errors[] = '⚠️ Bạn chỉ có thể đặt lịch trong vòng 7 ngày tới (tính từ sau 2 ngày nữa).';
        }
    }

    // Nếu không có lỗi -> lưu DB
    if (empty($errors)) {
        $stmt = $db->prepare("
            INSERT INTO $table (benhnhan_id, bacsi_id, ngaykham, giokham, ghichu, trangthai)
            VALUES (:benhnhan_id, :bacsi_id, :ngaykham, :giokham, :ghichu, 'pending')
        ");
        $stmt->bindParam(':benhnhan_id', $benhnhan_id, PDO::PARAM_INT);
        $stmt->bindValue(':bacsi_id', $bacsi_id > 0 ? $bacsi_id : null, PDO::PARAM_INT);
        $stmt->bindParam(':ngaykham', $ngaykham, PDO::PARAM_STR);
        $stmt->bindParam(':giokham', $giokham, PDO::PARAM_STR);
        $stmt->bindParam(':ghichu', $ghichu, PDO::PARAM_STR);
        $stmt->execute();

        // Redirect về danh sách
        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?nv=' . $module_name . '&op=schedule');
        exit;
    } else {
        // Gán lỗi vào template
        $xtpl->assign('ERROR', implode('<br>', $errors));
        $xtpl->parse('main.error');
    }
}

// Lấy danh sách bệnh nhân
$sql_bn = "SELECT id, hoten, ngaysinh, gioitinh, diachi, sdt, email FROM $table_benhnhan ORDER BY hoten ASC";
$result_bn = $db->query($sql_bn)->fetchAll();
foreach ($result_bn as $bn) {
    $label = $bn['hoten'];
    if (!empty($bn['sdt'])) $label .= ' (' . $bn['sdt'] . ')';
    if (!empty($bn['ngaysinh'])) {
        $ts = strtotime($bn['ngaysinh']);
        if ($ts !== false) $label .= ' - ' . date('d/m/Y', $ts);
    }
    $bn['label'] = $label;
    $xtpl->assign('BENHNHAN', $bn);
    $xtpl->parse('main.benhnhan_option');
}

// --- Lấy danh sách tên khoa (để join hiển thị)
$khoa_map = [];
$sql_khoa = "SELECT id, tenchuyenkhoa FROM $table_khoa";
$result_khoa = $db->query($sql_khoa)->fetchAll();
foreach ($result_khoa as $k) {
    $khoa_map[$k['id']] = $k['tenchuyenkhoa'];
}

// Lấy danh sách bác sĩ (vẫn giữ nguyên logic cũ nhưng hiển thị tên khoa)
$sql_bs = "SELECT id, hoten, chuyenkhoa_id, trinhdo, lichlamviec, email, sdt FROM $table_bacsi ORDER BY hoten ASC";
$result_bs = $db->query($sql_bs)->fetchAll();
foreach ($result_bs as $bs) {
    $label = $bs['hoten'];
    if (!empty($bs['chuyenkhoa_id'])) {
        $tenchuyenkhoa = isset($khoa_map[$bs['chuyenkhoa_id']]) ? $khoa_map[$bs['chuyenkhoa_id']] : 'Chưa rõ';
        $label .= ' - Khoa: ' . $tenchuyenkhoa;
    } else {
        $label .= ' - Chưa phân khoa';
    }
    if (!empty($bs['sdt'])) $label .= ' (' . $bs['sdt'] . ')';

    $bs['label'] = $label;
    $xtpl->assign('BACSI', $bs);
    $xtpl->parse('main.bacsi_option');
}

// Dropdown ngày khám: 7 ngày (bắt đầu từ +2)
$startDate = strtotime('+2 days');
for ($i = 0; $i < 7; $i++) {
    $date = date('Y-m-d', strtotime("+$i day", $startDate));
    $label = date('d/m/Y', strtotime($date));
    $xtpl->assign('NGAYKHAM_VALUE', $date);
    $xtpl->assign('NGAYKHAM_TEXT', $label);
    $xtpl->parse('main.ngaykham');
}

// Dropdown giờ khám: sáng (7–11h) & chiều (13–17h)
for ($h = 7; $h <= 11; $h++) {
    $time = sprintf("%02d:00", $h);
    $xtpl->assign('GIOKHAM_VALUE', $time);
    $xtpl->assign('GIOKHAM_TEXT', $time);
    $xtpl->parse('main.giokham');
}
for ($h = 13; $h <= 17; $h++) {
    $time = sprintf("%02d:00", $h);
    $xtpl->assign('GIOKHAM_VALUE', $time);
    $xtpl->assign('GIOKHAM_TEXT', $time);
    $xtpl->parse('main.giokham');
}

// Nạp CSS riêng cho trang chẩn đoán
$schedule_add_css = NV_BASE_SITEURL . 'modules/' . $module_file . '/css/schedule_add.css';
$xtpl->assign('SCHEDULE_ADD_CSS', $schedule_add_css);

$xtpl->assign('ACTION', NV_BASE_ADMINURL . 'index.php?nv=' . $module_name . '&op=schedule_add');
$xtpl->assign('BACK_LINK', NV_BASE_ADMINURL . 'index.php?nv=' . $module_name . '&op=schedule');

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
