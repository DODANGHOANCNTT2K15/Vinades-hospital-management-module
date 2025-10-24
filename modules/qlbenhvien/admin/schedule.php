<?php
if (!defined('NV_IS_QLBENHVIEN_ADMIN')) die('Stop!!!');

global $db, $db_config, $nv_Request, $module_name, $module_file, $global_config;

$table = $db_config['prefix'] . "_ql_benhvien_lichkham";
$per_page = 20;
$page = $nv_Request->get_int('page', 'get', 1);
$action = $nv_Request->get_string('action', 'get', 'list');

// Khởi tạo XTemplate
$xtpl = new XTemplate(
    'schedule.tpl',
    NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file
);

$table_benhnhan = $db_config['prefix'] . "_ql_benhvien_benhnhan";
$table_bacsi = $db_config['prefix'] . "_ql_benhvien_bacsi";

/*----------------------------------------
| 1. Danh sách lịch khám
-----------------------------------------*/
if ($action == 'list') {
    $sql = "SELECT lk.*, bn.hoten AS ten_benhnhan, bs.hoten AS ten_bacsi
            FROM " . $table . " AS lk
            LEFT JOIN " . $table_benhnhan . " AS bn ON lk.benhnhan_id = bn.id
            LEFT JOIN " . $table_bacsi . " AS bs ON lk.bacsi_id = bs.id
            ORDER BY lk.id DESC
            LIMIT " . (($page - 1) * $per_page) . ", " . $per_page;
    $result = $db->query($sql)->fetchAll();

    foreach ($result as $row) {
        switch ($row['trangthai']) {
            case 'pending': $row['trangthai_text'] = 'Chờ xác nhận'; break;
            case 'confirmed': $row['trangthai_text'] = 'Đã xác nhận'; break;
            case 'cancelled': $row['trangthai_text'] = 'Đã hủy'; break;
            default: $row['trangthai_text'] = 'Không rõ';
        }

        $row['link_edit'] = NV_BASE_ADMINURL . 'index.php?nv=' . $module_name . '&op=schedule&action=edit&id=' . $row['id'];
        $row['link_delete'] = NV_BASE_ADMINURL . 'index.php?nv=' . $module_name . '&op=schedule&action=delete&id=' . $row['id'];
        $xtpl->assign('ROW', $row);
        $xtpl->parse('main.list.row');
    }

    $xtpl->assign('ADD_LINK', NV_BASE_ADMINURL . 'index.php?nv=' . $module_name . '&op=schedule&action=add');
    $xtpl->parse('main.list');
}

/*----------------------------------------
| 2. Thêm mới lịch khám
-----------------------------------------*/
elseif ($action == 'add') {
    // Lấy danh sách bệnh nhân
    $list_benhnhan = $db->query("SELECT id, hoten FROM $table_benhnhan ORDER BY hoten ASC")->fetchAll();
    foreach ($list_benhnhan as $bn) {
        $xtpl->assign('BN', $bn);
        $xtpl->parse('main.add.benhnhan');
    }

    // Lấy danh sách bác sĩ
    $list_bacsi = $db->query("SELECT id, hoten FROM $table_bacsi ORDER BY hoten ASC")->fetchAll();
    foreach ($list_bacsi as $bs) {
        $xtpl->assign('BS', $bs);
        $xtpl->parse('main.add.bacsi');
    }

    // Khi submit form
    if ($nv_Request->isset_request('submit', 'post')) {
        $benhnhan_id = $nv_Request->get_int('benhnhan_id', 'post', 0);
        $bacsi_id = $nv_Request->get_int('bacsi_id', 'post', 0);
        $ngaykham = $nv_Request->get_string('ngaykham', 'post', '');
        $giokham = $nv_Request->get_string('giokham', 'post', '');
        $ghichu = $nv_Request->get_title('ghichu', 'post', '');

        if ($benhnhan_id > 0 && !empty($ngaykham) && !empty($giokham)) {
            $sql = "INSERT INTO $table (benhnhan_id, bacsi_id, ngaykham, giokham, trangthai, ghichu)
                    VALUES (:benhnhan_id, :bacsi_id, :ngaykham, :giokham, 'pending', :ghichu)";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':benhnhan_id', $benhnhan_id, PDO::PARAM_INT);
            $stmt->bindParam(':bacsi_id', $bacsi_id, PDO::PARAM_INT);
            $stmt->bindParam(':ngaykham', $ngaykham);
            $stmt->bindParam(':giokham', $giokham);
            $stmt->bindParam(':ghichu', $ghichu);
            $stmt->execute();

            $xtpl->assign('MESSAGE', '✅ Thêm lịch khám thành công!');
        } else {
            $xtpl->assign('MESSAGE', '⚠️ Vui lòng nhập đầy đủ thông tin bắt buộc.');
        }
        $xtpl->parse('main.add.message');
    }

    $xtpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php?nv=' . $module_name . '&op=schedule&action=add');
    $xtpl->parse('main.add');
}

/*----------------------------------------
| 3. Sửa lịch khám
-----------------------------------------*/
elseif ($action == 'edit') {
    $id = $nv_Request->get_int('id', 'get', 0);
    $row = $db->query("SELECT * FROM $table WHERE id=$id")->fetch();

    if (empty($row)) {
        $xtpl->assign('MESSAGE', 'Không tìm thấy lịch khám.');
        $xtpl->parse('main.error');
    } else {
        $list_bacsi = $db->query("SELECT id, hoten FROM $table_bacsi ORDER BY hoten ASC")->fetchAll();
        foreach ($list_bacsi as $bs) {
            $bs['selected'] = ($bs['id'] == $row['bacsi_id']) ? 'selected' : '';
            $xtpl->assign('BS', $bs);
            $xtpl->parse('main.edit.bacsi');
        }

        $xtpl->assign('ROW', $row);
        $xtpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php?nv=' . $module_name . '&op=schedule&action=edit&id=' . $id);

        if ($nv_Request->isset_request('save', 'post')) {
            $bacsi_id = $nv_Request->get_int('bacsi_id', 'post', 0);
            $trangthai = $nv_Request->get_string('trangthai', 'post', 'pending');
            $ghichu = $nv_Request->get_title('ghichu', 'post', '');

            $stmt = $db->prepare("UPDATE $table SET bacsi_id=:bacsi_id, trangthai=:trangthai, ghichu=:ghichu WHERE id=:id");
            $stmt->bindParam(':bacsi_id', $bacsi_id);
            $stmt->bindParam(':trangthai', $trangthai);
            $stmt->bindParam(':ghichu', $ghichu);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            $xtpl->assign('MESSAGE', '✅ Cập nhật thành công!');
            $xtpl->parse('main.edit.message');
        }

        $xtpl->parse('main.edit');
    }
}

/*----------------------------------------
| 4. Xóa lịch khám
-----------------------------------------*/
elseif ($action == 'delete') {
    $id = $nv_Request->get_int('id', 'get', 0);
    if ($id > 0) {
        $db->query("DELETE FROM $table WHERE id=$id");
        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?nv=' . $module_name . '&op=schedule');
    }
}

/*----------------------------------------
| Render template
-----------------------------------------*/
$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
