<?php
/**
 * benhnhan.php: Quản lý Danh sách Bệnh nhân
 */

if (!defined('NV_IS_QLBENHVIEN_ADMIN')) {
    die('Stop!!!');
}

global $db, $db_config, $nv_Request, $module_name, $module_file, $global_config, $admin_info, $lang_module;

$table = $db_config['prefix'] . "_ql_benhvien_benhnhan";

$per_page = 15;
$page = $nv_Request->get_int('page', 'get', 1);
$action = $nv_Request->get_string('action', 'get', 'list');
$id = $nv_Request->get_int('id', 'get', 0);
$error = '';
$data = [];

$xtpl = new XTemplate(
    'benhnhan.tpl',
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
        'gioitinh' => 'nam',
        'ngaysinh' => '',
        'dienthoai' => '',
        'diachi' => ''
    ];

    $xtpl->assign('ACTION', $action);

    if ($action == 'edit' && $id > 0) {
        $data = $db->query("SELECT * FROM " . $table . " WHERE id=" . $id)->fetch();
        if (empty($data)) {
            Header("Location: " . NV_BASE_ADMINURL . "index.php?nv=$module_name&op=benhnhan");
            exit();
        }
        $xtpl->assign('TITLE', $lang_module['benhnhan_edit_title'] ?? 'Sửa thông tin Bệnh nhân');
    } else {
        $xtpl->assign('TITLE', $lang_module['benhnhan_add_title'] ?? 'Thêm Bệnh nhân mới');
    }
    
    // Xử lý POST Form
    if ($nv_Request->isset_request('submit', 'post')) {
        $data['hoten'] = $nv_Request->get_string('hoten', 'post', '');
        $data['gioitinh'] = $nv_Request->get_string('gioitinh', 'post', 'nam');
        $data['ngaysinh'] = $nv_Request->get_string('ngaysinh', 'post', '');
        $data['dienthoai'] = $nv_Request->get_string('dienthoai', 'post', '');
        $data['diachi'] = $nv_Request->get_string('diachi', 'post', '');

        if (empty($data['hoten'])) {
            $error = 'Họ tên không được để trống.';
        } elseif (empty($data['dienthoai'])) {
            $error = 'Số điện thoại không được để trống.';
        }
        
        if (empty($error)) {
            if ($action == 'add') {
                // Thêm mới
                $stmt = $db->prepare("INSERT INTO " . $table . " (hoten, gioitinh, ngaysinh, dienthoai, diachi) 
                                     VALUES (:hoten, :gioitinh, :ngaysinh, :dienthoai, :diachi)");
                
                $stmt->bindParam(':hoten', $data['hoten']);
                $stmt->bindParam(':gioitinh', $data['gioitinh']);
                $stmt->bindParam(':ngaysinh', $data['ngaysinh']);
                $stmt->bindParam(':dienthoai', $data['dienthoai']);
                $stmt->bindParam(':diachi', $data['diachi']);
                $stmt->execute();
                
                $id = $db->lastInsertId();
                nv_insert_logs(NV_LANG_DATA, $module_name, 'Thêm Bệnh nhân', 'ID: ' . $id, $admin_info['userid']);
            } else {
                // Chỉnh sửa
                $stmt = $db->prepare("UPDATE " . $table . " SET hoten=:hoten, gioitinh=:gioitinh, ngaysinh=:ngaysinh, dienthoai=:dienthoai, diachi=:diachi WHERE id=:id");
                
                $stmt->bindParam(':hoten', $data['hoten']);
                $stmt->bindParam(':gioitinh', $data['gioitinh']);
                $stmt->bindParam(':ngaysinh', $data['ngaysinh']);
                $stmt->bindParam(':dienthoai', $data['dienthoai']);
                $stmt->bindParam(':diachi', $data['diachi']);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
                
                nv_insert_logs(NV_LANG_DATA, $module_name, 'Sửa Bệnh nhân', 'ID: ' . $id, $admin_info['userid']);
            }
            
            Header("Location: " . NV_BASE_ADMINURL . "index.php?nv=$module_name&op=benhnhan");
            exit();
        }
    }

    // Hiển thị Form
    $xtpl->assign('DATA', $data);
    $xtpl->assign('FORM_ACTION', NV_BASE_ADMINURL . "index.php?nv=$module_name&op=benhnhan&action=$action" . ($id > 0 ? "&id=$id" : ""));
    $xtpl->assign('GIOITINH_NAM_CHECKED', ($data['gioitinh'] == 'nam' ? 'checked="checked"' : ''));
    $xtpl->assign('GIOITINH_NU_CHECKED', ($data['gioitinh'] == 'nu' ? 'checked="checked"' : ''));
    
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
    // Kiểm tra xem bệnh nhân này có lịch khám nào không trước khi xóa
    $check_lichkham = $db->query("SELECT COUNT(*) FROM " . $db_config['prefix'] . "_ql_benhvien_lichkham WHERE benhnhan_id=" . $id)->fetchColumn();
    
    if ($check_lichkham > 0) {
        nv_info_die($lang_module['error_title'] ?? 'Lỗi', 'Không thể xóa bệnh nhân này vì còn liên kết với ' . $check_lichkham . ' lịch khám.', NV_BASE_ADMINURL . "index.php?nv=$module_name&op=benhnhan");
    } else {
        $db->query("DELETE FROM " . $table . " WHERE id=" . $id);
        nv_insert_logs(NV_LANG_DATA, $module_name, 'Xóa Bệnh nhân', 'ID: ' . $id, $admin_info['userid']);
        Header("Location: " . NV_BASE_ADMINURL . "index.php?nv=$module_name&op=benhnhan");
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

    $xtpl->assign('ADD_LINK', NV_BASE_ADMINURL . 'index.php?nv=' . $module_name . '&op=benhnhan&action=add');
    
    foreach ($result as $row) {
        $row['stt'] = (($page - 1) * $per_page) + ++$i;
        $row['ngaysinh_vn'] = !empty($row['ngaysinh']) ? date('d/m/Y', strtotime($row['ngaysinh'])) : '-';
        $row['gioitinh_text'] = ($row['gioitinh'] == 'nam') ? 'Nam' : 'Nữ';
        
        $row['link_edit'] = NV_BASE_ADMINURL . "index.php?nv=$module_name&op=benhnhan&action=edit&id=" . $row['id'];
        $row['link_delete'] = NV_BASE_ADMINURL . "index.php?nv=$module_name&op=benhnhan&action=delete&id=" . $row['id'];

        $xtpl->assign('ROW', $row);
        $xtpl->parse('main.list.row');
    }

    // Phân trang
    $base_url = NV_BASE_ADMINURL . "index.php?nv=$module_name&op=benhnhan";
    $generate_page = nv_generate_page($base_url, $total, $per_page, $page);
    if (!empty($generate_page)) {
        $xtpl->assign('NV_GENERATE_PAGE', $generate_page);
        $xtpl->parse('main.list.pagination');
    }

    $xtpl->assign('TOTAL_INFO', "Tổng cộng $total bệnh nhân");
    $xtpl->parse('main.list');
}

// Chuẩn bị nội dung để trả về main.php
$xtpl->parse('main');
$contents = $xtpl->text('main');

// Trả về nội dung đã được đóng khung giao diện Admin
return nv_admin_theme($contents);