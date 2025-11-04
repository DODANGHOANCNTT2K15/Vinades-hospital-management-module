<?php
if (!defined('NV_IS_MOD_QLBENHVIEN')) die('Stop!!!');

global $db, $db_config, $nv_Request, $module_info;

// --- Các filter từ request ---
$keyword = $nv_Request->get_title('keyword', 'get', '');
$chuyenkhoa_id = $nv_Request->get_int('chuyenkhoa_id', 'get', 0);
$page = $nv_Request->get_int('page', 'get', 1); // trang hiện tại

$limit = 3; // số bác sĩ/trang
$offset = ($page - 1) * $limit;

// --- Bảng ---
$table_bacsi = $db_config['prefix'] . "_ql_benhvien_bacsi";
$table_khoa  = $db_config['prefix'] . "_ql_benhvien_chuyenkhoa";

// --- Lấy danh sách chuyên khoa để filter ---
$sql_khoa = "SELECT id, tenchuyenkhoa FROM $table_khoa ORDER BY tenchuyenkhoa ASC";
$ds_khoa = $db->query($sql_khoa)->fetchAll(PDO::FETCH_ASSOC);

// --- Build WHERE clause ---
$where = [];
$params = [];

if (!empty($keyword)) {
    $where[] = "d.hoten LIKE :keyword";
    $params[':keyword'] = "%$keyword%";
}

if ($chuyenkhoa_id > 0) {
    $where[] = "d.chuyenkhoa_id = :khoa";
    $params[':khoa'] = $chuyenkhoa_id;
}

$where_sql = '';
if (!empty($where)) {
    $where_sql = "WHERE " . implode(" AND ", $where);
}

// --- Lấy tổng số bác sĩ để phân trang ---
$sql_count = "SELECT COUNT(*) FROM $table_bacsi AS d $where_sql";
$stmt_count = $db->prepare($sql_count);
$stmt_count->execute($params);
$total_records = $stmt_count->fetchColumn();
$total_pages = ceil($total_records / $limit);

// --- Lấy danh sách bác sĩ với LIMIT & OFFSET ---
$sql = "SELECT d.id, d.hoten, d.trinhdo, d.lichlamviec, d.email, d.sdt, k.tenchuyenkhoa
        FROM $table_bacsi AS d
        LEFT JOIN $table_khoa AS k ON k.id = d.chuyenkhoa_id
        $where_sql
        ORDER BY d.hoten ASC
        LIMIT :offset, :limit";

$stmt = $db->prepare($sql);

// Bind các param search
foreach ($params as $key => $val) {
    $stmt->bindValue($key, $val);
}

// Bind limit & offset
$stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
$stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);

$stmt->execute();
$ds_bacsi = $stmt->fetchAll(PDO::FETCH_ASSOC);

$xtpl = new XTemplate('doctor.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);

$doctor_css = NV_BASE_SITEURL . 'modules/' . $module_file . '/css/doctor.css';
$xtpl->assign('DOCTOR_CSS', $doctor_css);

// Gán filter
$xtpl->assign('KEYWORD', htmlspecialchars($keyword));

foreach ($ds_khoa as $khoa) {
    $khoa['selected'] = ($chuyenkhoa_id == $khoa['id']) ? 'selected' : '';
    $xtpl->assign('KHOA', $khoa);
    $xtpl->parse('main.khoa_option');
}

// Danh sách bác sĩ
foreach ($ds_bacsi as $bs) {

    if (empty($bs['hinhanh'])) {
        $bs['hinhanh'] = NV_BASE_SITEURL . 'modules/' . $module_file . '/images/macdinhbacsi.jpg';
    } else {
        // Nếu avatar lưu tên file trong DB
        $bs['hinhanh'] = NV_BASE_SITEURL . 'uploads/bacsi/' . $bs['hinhanh'];
    }
    $bs['hinhanh'] = NV_BASE_SITEURL . 'modules/' . $module_file . '/images/macdinhbacsi.jpg';
    $xtpl->assign('BACSILIST', $bs);
    $xtpl->parse('main.list.row');
}

// Phân trang
$xtpl->assign('CURRENT_PAGE', $page);
$xtpl->assign('TOTAL_PAGES', $total_pages);

// --- Tạo danh sách phân trang ---
// --- Pagination (Prev / Next) ---
if ($total_pages > 1) {
    $base_url = NV_BASE_SITEURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=doctor';
    if (!empty($keyword)) {
        $base_url .= '&keyword=' . urlencode($keyword);
    }
    if ($chuyenkhoa_id > 0) {
        $base_url .= '&chuyenkhoa_id=' . $chuyenkhoa_id;
    }

    $pagination = [];
    $hasPrev = false;
    $hasNext = false;

    if ($page > 1) {
        $pagination['prev_link'] = $base_url . '&page=' . ($page - 1);
        $hasPrev = true;
    }

    if ($page < $total_pages) {
        $pagination['next_link'] = $base_url . '&page=' . ($page + 1);
        $hasNext = true;
    }

    // Gán biến vào template
    $xtpl->assign('PAGE', $pagination);
    $xtpl->assign('CURRENT_PAGE', $page);
    $xtpl->assign('TOTAL_PAGES', $total_pages);

    // Parse các block con trong đúng thứ tự
    if ($hasPrev) {
        $xtpl->parse('main.pagination.prev');
    }
    if ($hasNext) {
        $xtpl->parse('main.pagination.next');
    }

    // Cuối cùng parse block pagination
    $xtpl->parse('main.pagination');
}


$xtpl->parse('main.list');
$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
