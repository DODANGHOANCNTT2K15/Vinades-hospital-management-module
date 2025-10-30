<?php
/**
 * bacsi.php: Quản lý Danh sách Bác sĩ
 */

if (!defined('NV_IS_QLBENHVIEN_ADMIN')) {
    die('Stop!!!');
}

global $db, $db_config, $nv_Request, $module_name, $module_file, $global_config, $admin_info, $lang_module;

$table = $db_config['prefix'] . "_ql_benhvien_bacsi";

$per_page = 15;
$page = $nv_Request->get_int('page', 'get', 1);
$action = $nv_Request->get_string('action', 'get', 'list');
$id = $nv_Request->get_int('id', 'get', 0);
$error = '';
$data = [];

$xtpl = new XTemplate(
    'bacsi.tpl',
    NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file
);

// -------------------------
// Xử lý Thêm/Sửa (action=add/edit)
// -------------------------
if (in_array($action, ['add', 'edit'])) {
    
    // Khởi tạo dữ liệu mặc định/lấy dữ liệu sửa
    $data = [
        'id' => 0,
        'hoten' => '',
        'chuyenkhoa' => '',
        'dienthoai' => '',
        'email' => ''
    ];

    $xtpl->assign('ACTION', $action);

    if ($action == 'edit' && $id > 0) {
        $data = $db->query("SELECT * FROM " . $table . " WHERE id=" . $id)->fetch();
        if (empty($data)) {
            Header("Location: " . NV_BASE_ADMINURL . "index.php?nv=$module_name&op=bacsi");
            exit();
        }
        $xtpl->assign('TITLE', $lang_module['bacsi_edit_title'] ?? 'Sửa thông tin Bác sĩ');
    } else {
        $xtpl->assign('TITLE', $lang_module['bacsi_add_title'] ?? 'Thêm Bác sĩ mới');
    }
    
    // Xử lý POST Form
    if ($nv_Request->isset_request('submit', 'post')) {
        $data['hoten'] = $nv_Request->get_string('hoten', 'post', '');
        $data['chuyenkhoa'] = $nv_Request->get_string('chuyenkhoa', 'post', '');
        $data['dienthoai'] = $nv_Request->get_string('dienthoai', 'post', '');
        $data['email'] = $nv_Request->get_string('email', 'post', '');

        if (empty($data['hoten'])) {
            $error = 'Họ tên không được để trống.';
        } elseif (empty($data['chuyenkhoa'])) {
            $error = 'Chuyên khoa không được để trống.';
        } elseif (empty($data['dienthoai'])) {
            $error = 'Số điện thoại không được để trống.';
        }
        
        if (empty($error)) {
            if ($action == 'add') {
                // Thêm mới
                $stmt = $db->prepare("INSERT INTO " . $table . " (hoten, chuyenkhoa, dienthoai, email) 
                                     VALUES (:hoten, :chuyenkhoa, :dienthoai, :email)");
                
                $stmt->bindParam(':hoten', $data['hoten']);
                $stmt->bindParam(':chuyenkhoa', $data['chuyenkhoa']);
                $stmt->bindParam(':dienthoai', $data['dienthoai']);
                $stmt->bindParam(':email', $data['email']);
                $stmt->execute();
                
                $id = $db->lastInsertId();
                nv_insert_logs(NV_LANG_DATA, $module_name, 'Thêm Bác sĩ', 'ID: ' . $id, $admin_info['userid']);
            } else {
                // Chỉnh sửa
                $stmt = $db->prepare("UPDATE " . $table . " SET hoten=:hoten, chuyenkhoa=:chuyenkhoa, dienthoai=:dienthoai, email=:email WHERE id=:id");
                
                $stmt->bindParam(':hoten', $data['hoten']);
                $stmt->bindParam(':chuyenkhoa', $data['chuyenkhoa']);
                $stmt->bindParam(':dienthoai', $data['dienthoai']);
                $stmt->bindParam(':email', $data['email']);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
                
                nv_insert_logs(NV_LANG_DATA, $module_name, 'Sửa Bác sĩ', 'ID: ' . $id, $admin_info['userid']);
            }
            
            Header("Location: " . NV_BASE_ADMINURL . "index.php?nv=$module_name&op=bacsi");
            exit();
        }
    }

    // Hiển thị Form
    $xtpl->assign('DATA', $data);
    $xtpl->assign('FORM_ACTION', NV_BASE_ADMINURL . "index.php?nv=$module_name&op=bacsi&action=$action" . ($id > 0 ? "&id=$id" : ""));
    
    if (!empty($error)) {
        $xtpl->assign('ERROR', $error);
        $xtpl->parse('main.form.error');
    }
    
    $xtpl->parse('main.form');
}

// -------------------------
// Xử lý Xóa (action=delete)
// -------------------------
elseif ($action == 'delete' && $id > 0) {
    // Kiểm tra xem bác sĩ này có lịch khám nào không trước khi xóa
    $check_lichkham = $db->query("SELECT COUNT(*) FROM " . $db_config['prefix'] . "_ql_benhvien_lichkham WHERE bacsi_id=" . $id)->fetchColumn();
    
    if ($check_lichkham > 0) {
        nv_info_die($lang_module['error_title'] ?? 'Lỗi', 'Không thể xóa bác sĩ này vì còn liên kết với ' . $check_lichkham . ' lịch khám.', NV_BASE_ADMINURL . "index.php?nv=$module_name&op=bacsi");
    } else {
        $db->query("DELETE FROM " . $table . " WHERE id=" . $id);
        nv_insert_logs(NV_LANG_DATA, $module_name, 'Xóa Bác sĩ', 'ID: ' . $id, $admin_info['userid']);
        Header("Location: " . NV_BASE_ADMINURL . "index.php?nv=$module_name&op=bacsi");
        exit();
    }
}

// -------------------------
// Danh sách (action=list)
// -------------------------
else {
    $total = $db->query("SELECT COUNT(*) FROM " . $table)->fetchColumn();

    $sql = "SELECT * FROM " . $table . " ORDER BY hoten ASC LIMIT " . (($page - 1) * $per_page) . ", " . $per_page;
    $result = $db->query($sql)->fetchAll();

    $xtpl->assign('ADD_LINK', NV_BASE_ADMINURL . 'index.php?nv=' . $module_name . '&op=bacsi&action=add');
    
    foreach ($result as $row) {
        $row['stt'] = (($page - 1) * $per_page) + ++$i;
        
        $row['link_edit'] = NV_BASE_ADMINURL . "index.php?nv=$module_name&op=bacsi&action=edit&id=" . $row['id'];
        $row['link_delete'] = NV_BASE_ADMINURL . "index.php?nv=$module_name&op=bacsi&action=delete&id=" . $row['id'];

        $xtpl->assign('ROW', $row);
        $xtpl->parse('main.list.row');
    }

    // Phân trang
    $base_url = NV_BASE_ADMINURL . "index.php?nv=$module_name&op=bacsi";
    $generate_page = nv_generate_page($base_url, $total, $per_page, $page);
    if (!empty($generate_page)) {
        $xtpl->assign('NV_GENERATE_PAGE', $generate_page);
        $xtpl->parse('main.list.pagination');
    }

    $xtpl->assign('TOTAL_INFO', "Tổng cộng $total bác sĩ");
    $xtpl->parse('main.list');
}

// Chuẩn bị nội dung để trả về main.php
$xtpl->parse('main');
$contents = $xtpl->text('main');

// Trả về nội dung đã được đóng khung giao diện Admin
return nv_admin_theme($contents);