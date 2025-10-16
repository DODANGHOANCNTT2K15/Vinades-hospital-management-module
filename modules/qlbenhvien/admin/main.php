<?php
if (!defined('NV_IS_QLBENHVIEN_ADMIN')) die('Stop!!!');

global $db, $db_config;

// Tên bảng trong CSDL
$table_lichkham = $db_config['prefix'] . "_ql_benhvien_lichkham";
$table_benhnhan = $db_config['prefix'] . "_ql_benhvien_benhnhan";
$table_bacsi    = $db_config['prefix'] . "_ql_benhvien_bacsi";

// Lấy 20 lịch khám mới nhất, JOIN để lấy tên bệnh nhân và bác sĩ
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

$contents = '<h2>Danh sách lịch khám</h2>';
$contents .= '<table class="table table-striped table-bordered">';
$contents .= '<thead>
<tr>
    <th>ID</th>
    <th>ID Bệnh nhân</th>
    <th>Tên bệnh nhân</th>
    <th>Tên bác sĩ</th>
    <th>Ngày khám</th>
    <th>Giờ khám</th>
    <th>Trạng thái</th>
    <th>Ghi chú</th>
</tr>
</thead><tbody>';

foreach ($result as $row) {
    // Xác định trạng thái
    switch ($row['trangthai']) {
        case 'pending':
            $status_label = 'Chờ xác nhận';
            break;
        case 'confirmed':
            $status_label = 'Đã xác nhận';
            break;
        case 'cancelled':
            $status_label = 'Đã hủy';
            break;
        default:
            $status_label = 'Không rõ';
    }

    $contents .= '<tr>';
    $contents .= '<td>' . $row['id'] . '</td>';
    $contents .= '<td>' . htmlspecialchars($row['benhnhan_id']) . '</td>';
    $contents .= '<td>' . htmlspecialchars(isset($row['ten_benhnhan']) ? $row['ten_benhnhan'] : '-') . '</td>';
    $contents .= '<td>' . htmlspecialchars(isset($row['ten_bacsi']) ? $row['ten_bacsi'] : '-') . '</td>';
    $contents .= '<td>' . htmlspecialchars($row['ngaykham']) . '</td>';
    $contents .= '<td>' . htmlspecialchars($row['giokham']) . '</td>';
    $contents .= '<td>' . htmlspecialchars($status_label) . '</td>';
    $contents .= '<td>' . htmlspecialchars($row['ghichu']) . '</td>';
    $contents .= '</tr>';
}

$contents .= '</tbody></table>';

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
