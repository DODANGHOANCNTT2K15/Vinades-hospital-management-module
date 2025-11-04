<?php
if (!defined('NV_IS_QLBENHVIEN_ADMIN')) {
    die('Stop!!!');
}

global $db, $db_config, $nv_Request, $module_name, $module_file, $global_config, $admin_info;

$table = $db_config['prefix'] . "_ql_benhvien_lichkham";
$table_benhnhan = $db_config['prefix'] . "_ql_benhvien_benhnhan";
$table_bacsi = $db_config['prefix'] . "_ql_benhvien_bacsi";

$per_page = 10;
$page = $nv_Request->get_int('page', 'get', 1);
$action = $nv_Request->get_string('action', 'get', 'list');

// XỬ LÝ LỌC RIÊNG - CHUYỂN HƯỚNG VỀ LIST CÓ THAM SỐ
if ($action == 'filter') {
    $benhnhan_id = $nv_Request->get_int('benhnhan_id', 'get', 0);
    $bacsi_id = $nv_Request->get_int('bacsi_id', 'get', 0);

    $url = NV_BASE_ADMINURL . "index.php?nv=$module_name&op=schedule&action=list";
    if ($benhnhan_id > 0) {
        $url .= "&benhnhan_id=" . $benhnhan_id;
    }
    if ($bacsi_id > 0) {
        $url .= "&bacsi_id=" . $bacsi_id;
    }

    header("Location: $url");
    exit();
}

$xtpl = new XTemplate(
    'schedule.tpl',
    NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file
);

$xtpl->assign('ADD_LINK', NV_BASE_ADMINURL . 'index.php?nv=' . $module_name . '&op=schedule_add');
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('MODULE_NAME', $module_name);

// -------------------------
// 1️⃣ Danh sách lịch khám (lọc theo Bệnh nhân & Bác sĩ)
// -------------------------
if ($action == 'list') {
    // Lấy giá trị lọc từ request
    $benhnhan_id = $nv_Request->get_int('benhnhan_id', 'get', 0);
    $bacsi_id = $nv_Request->get_int('bacsi_id', 'get', 0);

    // Lấy danh sách Bệnh nhân & Bác sĩ cho dropdown
    $benhnhans = $db->query("
        SELECT id, hoten 
        FROM " . $table_benhnhan . " 
        ORDER BY hoten ASC
    ")->fetchAll();

    $bacsis = $db->query("
        SELECT id, hoten 
        FROM " . $table_bacsi . " 
        ORDER BY hoten ASC
    ")->fetchAll();

    // Gán selected cho dropdown
    foreach ($benhnhans as &$bn) {
        $bn['selected'] = ($benhnhan_id == $bn['id']) ? 'selected' : '';
    }
    unset($bn);

    foreach ($bacsis as &$bs) {
        $bs['selected'] = ($bacsi_id == $bs['id']) ? 'selected' : '';
    }
    unset($bs);

    // Gán dropdown ra template
    foreach ($benhnhans as $bn) {
        $xtpl->assign('BENHNHAN', $bn);
        $xtpl->parse('main.list.benhnhan');
    }
    foreach ($bacsis as $bs) {
        $xtpl->assign('BACSI', $bs);
        $xtpl->parse('main.list.bacsi');
    }

    // Tạo điều kiện lọc linh hoạt
    $where = [];
    if ($benhnhan_id > 0) {
        $where[] = "lk.benhnhan_id = " . $benhnhan_id;
    }
    if ($bacsi_id > 0) {
        $where[] = "lk.bacsi_id = " . $bacsi_id;
    }
    $where_sql = (!empty($where)) ? "WHERE " . implode(" AND ", $where) : "";

    // Đếm tổng số bản ghi phù hợp
    $sql_count = "
        SELECT COUNT(*) 
        FROM " . $table . " AS lk
        LEFT JOIN " . $table_benhnhan . " AS bn ON lk.benhnhan_id = bn.id
        LEFT JOIN " . $table_bacsi . " AS bs ON lk.bacsi_id = bs.id
        $where_sql
    ";
    $total = $db->query($sql_count)->fetchColumn();

    // Lấy danh sách lịch khám theo trang
    $sql = "
        SELECT lk.*, 
               bn.hoten AS ten_benhnhan, 
               bs.hoten AS ten_bacsi
        FROM " . $table . " AS lk
        LEFT JOIN " . $table_benhnhan . " AS bn ON lk.benhnhan_id = bn.id
        LEFT JOIN " . $table_bacsi . " AS bs ON lk.bacsi_id = bs.id
        $where_sql
        ORDER BY lk.id DESC
        LIMIT " . (($page - 1) * $per_page) . ", " . $per_page;

    $result = $db->query($sql)->fetchAll();

    // Xử lý dữ liệu hiển thị
    if (!empty($result)) {
        foreach ($result as $row) {
            $row['ngaykham'] = (!empty($row['ngaykham']) && strtotime($row['ngaykham']))
                ? date('d/m/Y', strtotime($row['ngaykham']))
                : '-';

            switch ($row['trangthai']) {
                case 'pending':
                    $row['trangthai_text'] = 'Chờ xác nhận';
                    break;
                case 'confirmed':
                    $row['trangthai_text'] = 'Đã xác nhận';
                    break;
                case 'cancelled':
                    $row['trangthai_text'] = 'Đã hủy';
                    break;
                case 'diagnosed':
                    $row['trangthai_text'] = 'Đã chẩn đoán';
                    break;
                default:
                    $row['trangthai_text'] = '-';
            }

            $row['ten_benhnhan'] = !empty($row['ten_benhnhan']) ? $row['ten_benhnhan'] : '-';
            $row['ten_bacsi'] = !empty($row['ten_bacsi']) ? $row['ten_bacsi'] : 'Chưa phân công';

            $row['link_detail'] = NV_BASE_ADMINURL . "index.php?nv=$module_name&op=schedule&action=detail&id=" . $row['id'];
            $row['link_edit'] = NV_BASE_ADMINURL . "index.php?nv=$module_name&op=schedule_edit&id=" . $row['id'];
            $row['link_delete'] = NV_BASE_ADMINURL . "index.php?nv=$module_name&op=schedule&action=delete&id=" . $row['id'];

            $xtpl->assign('ROW', $row);
            $xtpl->parse('main.list.row');
        }
    } else {
        $xtpl->parse('main.list.no_data'); // Dòng này hiển thị thông báo khi không có dữ liệu
    }

    // Phân trang
    $base_url = NV_BASE_ADMINURL . "index.php?nv=$module_name&op=schedule&action=list";
    if ($benhnhan_id > 0)
        $base_url .= "&benhnhan_id=" . $benhnhan_id;
    if ($bacsi_id > 0)
        $base_url .= "&bacsi_id=" . $bacsi_id;

    $generate_page = nv_generate_page($base_url, $total, $per_page, $page);
    if (!empty($generate_page)) {
        $xtpl->assign('NV_GENERATE_PAGE', $generate_page);
        $xtpl->parse('main.list.pagination');
    }

    $xtpl->assign('TOTAL_INFO', "Tổng cộng $total lịch khám");
    $xtpl->parse('main.list');
}

// -------------------------
// 2️⃣ Xem chi tiết lịch khám
// -------------------------
elseif ($action == 'detail') {
    $id = $nv_Request->get_int('id', 'get', 0);

    $sql = "SELECT lk.*, bn.hoten AS ten_benhnhan, bs.hoten AS ten_bacsi
            FROM " . $table . " AS lk
            LEFT JOIN " . $table_benhnhan . " AS bn ON lk.benhnhan_id = bn.id
            LEFT JOIN " . $table_bacsi . " AS bs ON lk.bacsi_id = bs.id
            WHERE lk.id=" . $id;
    $detail = $db->query($sql)->fetch();

    if (empty($detail)) {
        $xtpl->assign('MESSAGE', 'Không tìm thấy lịch khám.');
        $xtpl->parse('main.error');
    } else {
        $detail['ngaykham_vn'] = !empty($detail['ngaykham']) && strtotime($detail['ngaykham'])
            ? date('d/m/Y', strtotime($detail['ngaykham']))
            : '-';

        $detail['ten_benhnhan'] = !empty($detail['ten_benhnhan']) ? $detail['ten_benhnhan'] : '-';
        $detail['ten_bacsi'] = !empty($detail['ten_bacsi']) ? $detail['ten_bacsi'] : 'Chưa phân công';

        switch ($detail['trangthai']) {
            case 'pending':
                $detail['trangthai_text'] = 'Chờ xác nhận';
                break;
            case 'confirmed':
                $detail['trangthai_text'] = 'Đã xác nhận';
                break;
            case 'cancelled':
                $detail['trangthai_text'] = 'Đã hủy';
                break;
            case 'diagnosed':
                $detail['trangthai_text'] = 'Đã chẩn đoán';
                break;
            default:
                $detail['trangthai_text'] = 'Không rõ';
        }

        $xtpl->assign('MODULE_NAME', $module_name);
        $xtpl->assign('ROW', $detail);

        $xtpl->assign('LINK_CONFIRM', NV_BASE_ADMINURL . "index.php?nv=$module_name&op=schedule&action=confirm&id=" . $id);
        $xtpl->assign('LINK_CANCEL', NV_BASE_ADMINURL . "index.php?nv=$module_name&op=schedule&action=cancel&id=" . $id);
        $xtpl->assign('LINK_EDIT', NV_BASE_ADMINURL . "index.php?nv=$module_name&op=schedule_edit&id=" . $id);
        $xtpl->assign('LINK_DELETE', NV_BASE_ADMINURL . "index.php?nv=$module_name&op=schedule&action=delete&id=" . $id);
        $xtpl->assign('BACK_LINK', NV_BASE_ADMINURL . "index.php?nv=$module_name&op=schedule");

        $xtpl->parse('main.detail');
    }
}

// -------------------------
// 3️⃣ Xác nhận, Hủy, Xóa
// -------------------------
elseif ($action == 'confirm') {
    $id = $nv_Request->get_int('id', 'get', 0);
    $db->query("UPDATE " . $table . " SET trangthai='confirmed' WHERE id=" . $id);
    Header("Location: " . NV_BASE_ADMINURL . "index.php?nv=$module_name&op=schedule&action=detail&id=" . $id);
    exit();
} elseif ($action == 'cancel') {
    $id = $nv_Request->get_int('id', 'get', 0);
    $db->query("UPDATE " . $table . " SET trangthai='cancelled' WHERE id=" . $id);
    Header("Location: " . NV_BASE_ADMINURL . "index.php?nv=$module_name&op=schedule&action=detail&id=" . $id);
    exit();
} elseif ($action == 'delete') {
    $id = $nv_Request->get_int('id', 'get', 0);
    if ($id > 0) {
        $db->query("DELETE FROM " . $table . " WHERE id=" . $id);
        nv_insert_logs(NV_LANG_DATA, $module_name, 'Xóa lịch khám', 'ID: ' . $id, $admin_info['userid']);
    }
    Header("Location: " . NV_BASE_ADMINURL . "index.php?nv=$module_name&op=schedule");
    exit();
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
