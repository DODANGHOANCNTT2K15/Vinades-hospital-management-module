<?php
if (!defined('NV_IS_QLBENHVIEN_ADMIN')) die('Stop!!!');

global $db, $db_config, $module_name;

// Khai báo đường dẫn đến file template
$xtpl = new XTemplate('main.tpl', NV_ROOTDIR . '/themes/admin_default/modules/' . $module_name);

// Tên các bảng
$table_lichkham = $db_config['prefix'] . "_ql_benhvien_lichkham";
$table_benhnhan = $db_config['prefix'] . "_ql_benhvien_benhnhan";
$table_bacsi    = $db_config['prefix'] . "_ql_benhvien_bacsi";

// Lấy dữ liệu
$sql = "
    SELECT 
        lk.id,
        lk.benhnhan_id,
        bn.hoten AS ten_benhnhan,
        bs.hoten AS ten_bacsi,
        lk.ngaykham,
        lk.giokham,
        lk.trangthai,
        lk.ghichu
    FROM " . $table_lichkham . " AS lk
    LEFT JOIN " . $table_benhnhan . " AS bn ON lk.benhnhan_id = bn.id
    LEFT JOIN " . $table_bacsi . " AS bs ON lk.bacsi_id = bs.id
    ORDER BY lk.id DESC
    LIMIT 0, 20
";
$result = $db->query($sql)->fetchAll();

// Gán dữ liệu cho từng dòng
foreach ($result as $row) {
    switch ($row['trangthai']) {
        case 'pending':
            $row['trangthai_label'] = 'Chờ xác nhận';
            break;
        case 'confirmed':
            $row['trangthai_label'] = 'Đã xác nhận';
            break;
        case 'cancelled':
            $row['trangthai_label'] = 'Đã hủy';
            break;
        default:
            $row['trangthai_label'] = 'Không rõ';
    }

    // Nếu tên bệnh nhân hoặc bác sĩ null thì hiển thị "-"
    $row['ten_benhnhan'] = !empty($row['ten_benhnhan']) ? $row['ten_benhnhan'] : '-';
    $row['ten_bacsi']    = !empty($row['ten_bacsi']) ? $row['ten_bacsi'] : '-';

    // Gán biến cho template
    $xtpl->assign('ROW', $row);
    $xtpl->parse('main.loop');
}

// Gán tiêu đề bảng
$xtpl->assign('TITLE', 'Danh sách lịch khám');

// Parse khối chính
$xtpl->parse('main');

// Xuất nội dung
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
