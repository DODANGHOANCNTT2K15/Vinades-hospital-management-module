<?php
if (!defined('NV_IS_QLBENHVIEN_ADMIN')) die('Stop!!!');

global $db, $db_config, $nv_Request;

$table = $db_config['prefix'] . "_ql_benhvien_lichkham";
$per_page = 20;
$page = $nv_Request->get_int('page', 'get', 1);
$action = $nv_Request->get_string('action', 'get', 'list');
$contents = '';

// Hiển thị danh sách lịch khám
if ($action == 'list') {
    $table_benhnhan = $db_config['prefix'] . "_ql_benhvien_benhnhan";
    $table_bacsi = $db_config['prefix'] . "_ql_benhvien_bacsi";

    $sql = "SELECT lk.*, bn.hoten AS ten_benhnhan, bs.hoten AS ten_bacsi
            FROM " . $table . " AS lk
            LEFT JOIN " . $table_benhnhan . " AS bn ON lk.benhnhan_id = bn.id
            LEFT JOIN " . $table_bacsi . " AS bs ON lk.bacsi_id = bs.id
            ORDER BY lk.id DESC
            LIMIT " . (($page - 1) * $per_page) . ", " . $per_page;

    $result = $db->query($sql)->fetchAll();

    $contents .= '<h2>Danh sách lịch khám</h2>';
    $contents .= '<p><a class="btn btn-primary" href="' . NV_BASE_ADMINURL . 'index.php?nv=' . $module_name . '&op=schedule&action=add">+ Thêm lịch mới</a></p>';

    $contents .= '<table class="table table-striped table-bordered">';
    $contents .= '<thead><tr>
                    <th>ID</th>
                    <th>Bệnh nhân ID</th>
                    <th>Tên bệnh nhân</th>
                    <th>Bác sĩ ID</th>
                    <th>Tên bác sĩ</th>
                    <th>Ngày khám</th>
                    <th>Giờ</th>
                    <th>Trạng thái</th>
                    <th>Ghi chú</th>
                    <th>Hành động</th>
                 </tr></thead><tbody>';

    foreach ($result as $row) {
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
        $contents .= '<td>' . htmlspecialchars(isset($row['bacsi_id']) ? $row['bacsi_id'] : '-') . '</td>';
        $contents .= '<td>' . htmlspecialchars(isset($row['ten_bacsi']) ? $row['ten_bacsi'] : '-') . '</td>';
        $contents .= '<td>' . htmlspecialchars($row['ngaykham']) . '</td>';
        $contents .= '<td>' . htmlspecialchars($row['giokham']) . '</td>';
        $contents .= '<td>' . $status_label . '</td>';
        $contents .= '<td>' . htmlspecialchars($row['ghichu']) . '</td>';
        $contents .= '<td>
                        <a href="' . NV_BASE_ADMINURL . 'index.php?nv=' . $module_name . '&op=schedule&action=edit&id=' . $row['id'] . '">Sửa</a> | 
                        <a href="' . NV_BASE_ADMINURL . 'index.php?nv=' . $module_name . '&op=schedule&action=delete&id=' . $row['id'] . '" onclick="return confirm(\'Xóa lịch này?\')">Xóa</a>
                      </td>';
        $contents .= '</tr>';
    }

    $contents .= '</tbody></table>';
}

// Thêm mới lịch khám
elseif ($action == 'add') {
    // Lấy danh sách bệnh nhân
    $table_benhnhan = $db_config['prefix'] . "_ql_benhvien_benhnhan";
    $sql_benhnhan = "SELECT id, hoten FROM " . $table_benhnhan . " ORDER BY hoten ASC";
    $list_benhnhan = $db->query($sql_benhnhan)->fetchAll();

    // Lấy danh sách bác sĩ
    $table_bacsi = $db_config['prefix'] . "_ql_benhvien_bacsi";
    $sql_bacsi = "SELECT id, hoten FROM " . $table_bacsi . " ORDER BY hoten ASC";
    $list_bacsi = $db->query($sql_bacsi)->fetchAll();

    $contents .= '<h3>Thêm lịch khám thủ công</h3>';
    $contents .= '<form method="post">';

    // Dropdown chọn bệnh nhân
    $contents .= '<p><label>Bệnh nhân: 
        <select name="benhnhan_id" required>
            <option value="">-- Chọn bệnh nhân --</option>';
    foreach ($list_benhnhan as $bn) {
        $contents .= '<option value="' . $bn['id'] . '">' . htmlspecialchars($bn['hoten']) . '</option>';
    }
    $contents .= '</select></label></p>';

    // Dropdown chọn bác sĩ
    $contents .= '<p><label>Bác sĩ (tùy chọn): 
        <select name="bacsi_id">
            <option value="0">-- Chưa chọn bác sĩ --</option>';
    foreach ($list_bacsi as $bs) {
        $contents .= '<option value="' . $bs['id'] . '">' . htmlspecialchars($bs['hoten']) . '</option>';
    }
    $contents .= '</select></label></p>';

    // Ngày, giờ, ghi chú
    $contents .= '<p><label>Ngày khám: <input type="date" name="ngaykham" required></label></p>';
    $contents .= '<p><label>Giờ khám: <input type="time" name="giokham" required></label></p>';
    $contents .= '<p><label>Ghi chú:</label><br><textarea name="ghichu" rows="3" cols="50"></textarea></p>';

    $contents .= '<p><button type="submit" name="submit">Thêm lịch</button></p>';
    $contents .= '</form>';

    // Xử lý khi submit form
    if ($nv_Request->isset_request('submit', 'post')) {
        $benhnhan_id = $nv_Request->get_int('benhnhan_id', 'post', 0);
        $bacsi_id    = $nv_Request->get_int('bacsi_id', 'post', 0);
        $ngaykham    = $nv_Request->get_string('ngaykham', 'post', '');
        $giokham     = $nv_Request->get_string('giokham', 'post', '');
        $ghichu      = $nv_Request->get_title('ghichu', 'post', '');

        if ($benhnhan_id > 0 && !empty($ngaykham) && !empty($giokham)) {
            $sql = "INSERT INTO " . $table . " (benhnhan_id, bacsi_id, ngaykham, giokham, trangthai, ghichu)
                    VALUES (:benhnhan_id, :bacsi_id, :ngaykham, :giokham, 'pending', :ghichu)";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':benhnhan_id', $benhnhan_id, PDO::PARAM_INT);
            if ($bacsi_id > 0) $stmt->bindParam(':bacsi_id', $bacsi_id, PDO::PARAM_INT);
            else $stmt->bindValue(':bacsi_id', null, PDO::PARAM_NULL);
            $stmt->bindParam(':ngaykham', $ngaykham, PDO::PARAM_STR);
            $stmt->bindParam(':giokham', $giokham, PDO::PARAM_STR);
            $stmt->bindParam(':ghichu', $ghichu, PDO::PARAM_STR);
            $stmt->execute();

            $contents .= '<p style="color:green;">Đã thêm lịch khám thành công!</p>';
        } else {
            $contents .= '<p style="color:red;">Vui lòng nhập đầy đủ thông tin bắt buộc.</p>';
        }
    }
}


// Xóa lịch khám
elseif ($action == 'delete') {
    $id = $nv_Request->get_int('id', 'get', 0);
    if ($id > 0) {
        $db->query("DELETE FROM " . $table . " WHERE id=" . $id);
        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?nv=' . $module_name . '&op=schedule');
    }
}

// Chỉnh sửa lịch khám
elseif ($action == 'edit') {
    $id = $nv_Request->get_int('id', 'get', 0);
    $row = $db->query("SELECT * FROM " . $table . " WHERE id=" . $id)->fetch();

    if (empty($row)) {
        $contents .= '<p style="color:red;">Không tìm thấy lịch khám.</p>';
    } else {
        $table_bacsi = $db_config['prefix'] . "_ql_benhvien_bacsi";
        $sql_bacsi = "SELECT id, hoten FROM " . $table_bacsi . " ORDER BY hoten ASC";
        $list_bacsi = $db->query($sql_bacsi)->fetchAll();

        $contents .= '<h3>Cập nhật lịch khám</h3>';
        $contents .= '<form method="post">';
        $contents .= '<p>Bệnh nhân ID: <strong>' . $row['benhnhan_id'] . '</strong></p>';

        $contents .= '<p><label>Bác sĩ: 
            <select name="bacsi_id">
                <option value="0">-- Chưa chọn bác sĩ --</option>';
        foreach ($list_bacsi as $bs) {
            $selected = ($row['bacsi_id'] == $bs['id']) ? ' selected' : '';
            $contents .= '<option value="' . $bs['id'] . '"' . $selected . '>' . htmlspecialchars($bs['hoten']) . '</option>';
        }
        $contents .= '</select></label></p>';

        $contents .= '<p><label>Trạng thái:
            <select name="trangthai">
                <option value="pending"' . ($row['trangthai'] == 'pending' ? ' selected' : '') . '>Chờ xác nhận</option>
                <option value="confirmed"' . ($row['trangthai'] == 'confirmed' ? ' selected' : '') . '>Đã xác nhận</option>
                <option value="cancelled"' . ($row['trangthai'] == 'cancelled' ? ' selected' : '') . '>Đã hủy</option>
            </select></label></p>';

        $contents .= '<p><label>Ghi chú: <input type="text" name="ghichu" value="' . htmlspecialchars($row['ghichu']) . '"></label></p>';
        $contents .= '<p><button type="submit" name="save">Lưu thay đổi</button></p>';
        $contents .= '</form>';

        if ($nv_Request->isset_request('save', 'post')) {
            $bacsi_id = $nv_Request->get_int('bacsi_id', 'post', 0);
            $trangthai = $nv_Request->get_string('trangthai', 'post', 'pending');
            $ghichu = $nv_Request->get_title('ghichu', 'post', '');

            $sql = "UPDATE " . $table . " SET 
                        bacsi_id = :bacsi_id,
                        trangthai = :trangthai,
                        ghichu = :ghichu
                    WHERE id = :id";
            $stmt = $db->prepare($sql);
            if ($bacsi_id > 0) $stmt->bindParam(':bacsi_id', $bacsi_id, PDO::PARAM_INT);
            else $stmt->bindValue(':bacsi_id', null, PDO::PARAM_NULL);
            $stmt->bindParam(':trangthai', $trangthai, PDO::PARAM_STR);
            $stmt->bindParam(':ghichu', $ghichu, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $contents .= '<p style="color:green;">Cập nhật thành công!</p>';
        }
    }
}

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
