<?php
if (!defined('NV_IS_QLBENHVIEN_ADMIN'))
    die('Stop!!!');

global $db, $db_config, $nv_Request, $module_name, $module_file, $global_config;

$table = $db_config['prefix'] . "_ql_benhvien_lichkham";
$table_benhnhan = $db_config['prefix'] . "_ql_benhvien_benhnhan";
$table_bacsi = $db_config['prefix'] . "_ql_benhvien_bacsi";

$per_page = 15;
$page = $nv_Request->get_int('page', 'get', 1);
$action = $nv_Request->get_string('action', 'get', 'list');

// Khởi tạo XTemplate
$xtpl = new XTemplate(
    'schedule.tpl',
    NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file
);

$xtpl->assign('ADD_LINK', NV_BASE_ADMINURL . 'index.php?nv=' . $module_name . '&op=schedule_add');

// Danh sách lịch khám
if ($action == 'list') {
    // Đếm tổng số bản ghi
    $total = $db->query("SELECT COUNT(*) FROM " . $table)->fetchColumn();

    // Lấy danh sách có phân trang
    $sql = "SELECT lk.*, bn.hoten AS ten_benhnhan, bs.hoten AS ten_bacsi
            FROM " . $table . " AS lk
            LEFT JOIN " . $table_benhnhan . " AS bn ON lk.benhnhan_id = bn.id
            LEFT JOIN " . $table_bacsi . " AS bs ON lk.bacsi_id = bs.id
            ORDER BY lk.id DESC
            LIMIT " . (($page - 1) * $per_page) . ", " . $per_page;
    $result = $db->query($sql)->fetchAll();

    // Duyệt dữ liệu
    foreach ($result as $row) {
        // Định dạng ngày khám sang dd/mm/yyyy
        if (!empty($row['ngaykham']) && strtotime($row['ngaykham'])) {
            $row['ngaykham'] = date('d/m/Y', strtotime($row['ngaykham']));
        } else {
            $row['ngaykham'] = '-';
        }

        // Xử lý trạng thái
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
            default:
                $row['trangthai_text'] = 'Không rõ';
        }

        // Xử lý tên bệnh nhân, bác sĩ
        $row['ten_benhnhan'] = !empty($row['ten_benhnhan']) ? $row['ten_benhnhan'] : '-';
        $row['ten_bacsi'] = !empty($row['ten_bacsi']) ? $row['ten_bacsi'] : 'Chưa phân công';

        // Link thao tác
        $row['link_edit'] = NV_BASE_ADMINURL . 'index.php?nv=' . $module_name . '&op=schedule&action=edit&id=' . $row['id'];
        $row['link_delete'] = NV_BASE_ADMINURL . 'index.php?nv=' . $module_name . '&op=schedule&action=delete&id=' . $row['id'];

        $xtpl->assign('ROW', $row);
        $xtpl->parse('main.list.row');
    }

    // Hiển thị phân trang
    $base_url = NV_BASE_ADMINURL . 'index.php?nv=' . $module_name . '&op=schedule';
    $generate_page = nv_generate_page($base_url, $total, $per_page, $page);
    if (!empty($generate_page)) {
        $xtpl->assign('NV_GENERATE_PAGE', $generate_page);
        $xtpl->parse('main.list.pagination');
    }

    // Hiển thị tổng số bản ghi
    $from = ($page - 1) * $per_page + 1;
    $to = min($page * $per_page, $total);
    $xtpl->assign('TOTAL_INFO', "Đang xem $from – $to / Tổng $total lịch khám");
    $xtpl->parse('main.list.total_info');

    $xtpl->parse('main.list');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
