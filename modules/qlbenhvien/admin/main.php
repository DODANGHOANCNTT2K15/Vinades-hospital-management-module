<?php
if (!defined('NV_IS_QLBENHVIEN_ADMIN')) die('Stop!!!');

global $db, $db_config;

// Tên bảng theo đúng CSDL của bạn
$table_lichkham = $db_config['prefix'] . "_ql_benhvien_lichkham";

// Lấy dữ liệu 20 lịch khám mới nhất
$sql = "SELECT * FROM " . $table_lichkham . " ORDER BY ngaykham DESC, giokham ASC LIMIT 0,20";
$result = $db->query($sql)->fetchAll();

$contents = '<h2>Danh sách lịch khám</h2>';
$contents .= '<table class="table table-striped">';
$contents .= '<thead><tr><th>ID</th><th>Bệnh nhân ID</th><th>Bác sĩ ID</th><th>Ngày khám</th><th>Giờ khám</th><th>Trạng thái</th><th>Ghi chú</th></tr></thead><tbody>';

foreach ($result as $row) {
    $contents .= '<tr>';
    $contents .= '<td>' . $row['id'] . '</td>';
    $contents .= '<td>' . htmlspecialchars($row['benhnhan_id']) . '</td>';
    $contents .= '<td>' . htmlspecialchars($row['bacsi_id']) . '</td>';
    $contents .= '<td>' . htmlspecialchars($row['ngaykham']) . '</td>';
    $contents .= '<td>' . htmlspecialchars($row['giokham']) . '</td>';
    $contents .= '<td>' . htmlspecialchars($row['trangthai']) . '</td>';
    $contents .= '<td>' . htmlspecialchars($row['ghichu']) . '</td>';
    $contents .= '</tr>';
}

$contents .= '</tbody></table>';

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
