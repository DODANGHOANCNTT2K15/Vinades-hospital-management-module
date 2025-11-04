<?php
if (!defined('NV_IS_MOD_QLBENHVIEN')) die('Stop!!!');

global $db, $db_config, $user_info, $module_info, $module_file, $nv_Request;

// --- Nếu chưa đăng nhập thì chuyển tới trang đăng nhập ---
if (empty($user_info['userid'])) {
    nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA
        . '&' . NV_NAME_VARIABLE . '=users&' . NV_OP_VARIABLE . '=login');
    exit();
}

// --- Lấy ID bệnh nhân từ userid ---
$table_benhnhan = $db_config['prefix'] . '_ql_benhvien_benhnhan';
$stmt = $db->prepare("SELECT id FROM " . $table_benhnhan . " WHERE userid = :userid");
$stmt->bindValue(':userid', $user_info['userid'], PDO::PARAM_INT);
$stmt->execute();
$benhnhan = $stmt->fetch();

$lichkham_list = [];
$total_pages = 1;
$page = $nv_Request->get_int('page', 'get', 1);

if ($benhnhan) {
    $benhnhan_id = $benhnhan['id'];

    // --- Phân trang ---
    $limit = 5;
    $offset = ($page - 1) * $limit;

    // --- Tổng số bản ghi ---
    $sql_count = "SELECT COUNT(*) FROM " . $db_config['prefix'] . "_ql_benhvien_lichkham 
                  WHERE benhnhan_id = :benhnhan_id";
    $stmt = $db->prepare($sql_count);
    $stmt->bindValue(':benhnhan_id', $benhnhan_id, PDO::PARAM_INT);
    $stmt->execute();
    $total_records = $stmt->fetchColumn();
    $total_pages = max(ceil($total_records / $limit), 1);

    // --- Lấy dữ liệu có LIMIT & OFFSET ---
    $sql = "SELECT lk.id, lk.ngaykham, lk.giokham, lk.ghichu, lk.trangthai,
                   IFNULL(bs.hoten, 'Đang sắp xếp') AS bacsi
            FROM " . $db_config['prefix'] . "_ql_benhvien_lichkham AS lk
            LEFT JOIN " . $db_config['prefix'] . "_ql_benhvien_bacsi AS bs ON lk.bacsi_id = bs.id
            WHERE lk.benhnhan_id = :benhnhan_id
            ORDER BY lk.ngaykham DESC, lk.giokham DESC, lk.id DESC
            LIMIT :limit OFFSET :offset";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':benhnhan_id', $benhnhan_id, PDO::PARAM_INT);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    while ($row = $stmt->fetch()) {
        $row['ngaykham'] = date('d/m/Y', strtotime($row['ngaykham']));
        $row['giokham'] = substr($row['giokham'], 0, 5);

        switch ($row['trangthai']) {
            case 'confirmed':
                $row['trangthai_text'] = '✅ Đã xác nhận';
                $row['class'] = 'confirmed';
                break;
            case 'cancelled':
                $row['trangthai_text'] = '❌ Đã hủy';
                $row['class'] = 'cancelled';
                break;
            case 'diagnosed':
                $row['trangthai_text'] = '✅ Đã chuẩn đoán';
                $row['class'] = 'diagnosed';
                break;
            default:
                $row['trangthai_text'] = '⏳ Chờ xác nhận';
                $row['class'] = 'pending';
        }

        $lichkham_list[] = $row;
    }
}

// --- Tạo biến phân trang ---
$prevPage = $page > 1 ? $page - 1 : 0;
$nextPage = $page < $total_pages ? $page + 1 : 0;

// --- Render bằng XTemplate ---
$xtpl = new XTemplate('historyBooking.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
$xtpl->assign('HISTORY_CSS', NV_BASE_SITEURL . 'modules/' . $module_file . '/css/historyBooking.css');
$xtpl->assign('CURRENT_PAGE', $page);
$xtpl->assign('TOTAL_PAGES', $total_pages);

if (!empty($lichkham_list)) {
    foreach ($lichkham_list as $lich) {
        $xtpl->assign('LICH', $lich);
        $xtpl->parse('main.row');
    }
} else {
    $xtpl->parse('main.empty');
}

// URL template cho trang chẩn đoán chi tiết, trong đó :id là chỗ sẽ thay thế id lịch khám
$diagnosis_detail_url_template = NV_BASE_SITEURL 
    . 'index.php?' 
    . NV_LANG_VARIABLE . '=' . NV_LANG_DATA 
    . '&' . NV_NAME_VARIABLE . '=' . $module_name 
    . '&' . NV_OP_VARIABLE . '=diagnosis_detail&id=';

// Truyền biến này vào tpl
$xtpl->assign('DIAGNOSIS_DETAIL_URL_TEMPLATE', $diagnosis_detail_url_template);

// --- Phân trang ---
if ($prevPage) {
    $xtpl->assign('PREV_PAGE', $prevPage);
    $xtpl->parse('main.pagination.prev');
}

if ($nextPage) {
    $xtpl->assign('NEXT_PAGE', $nextPage);
    $xtpl->parse('main.pagination.next');
}

if ($total_pages > 1) {
    $xtpl->parse('main.pagination');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
