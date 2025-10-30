<?php
/**
 * main.php: File điều hướng chính cho Admin Module qlbenhvien
 */

if (!defined('NV_IS_QLBENHVIEN_ADMIN')) {
    die('Stop!!!');
}

global $nv_Request, $module_name, $global_config, $lang_module;

// Thêm op benhnhan làm op mặc định nếu không có op nào được chỉ định
$op = $nv_Request->get_string('op', 'variable', 'schedule');

// Danh sách các op (chức năng) có trong module Admin
$array_h = [
    // Chức năng Lịch Khám
    'schedule' => [
        'title' => $lang_module['schedule_title'] ?? 'Lịch khám bệnh',
        'content' => ''
    ],
    'schedule_add' => [
        'title' => $lang_module['schedule_add_title'] ?? 'Thêm Lịch khám',
        'content' => ''
    ],
    'schedule_edit' => [
        'title' => $lang_module['schedule_edit_title'] ?? 'Sửa Lịch khám',
        'content' => ''
    ],
    // Chức năng Bệnh nhân
    'benhnhan' => [
        'title' => $lang_module['benhnhan_title'] ?? 'Quản lý Bệnh nhân',
        'content' => ''
    ],
    // Chức năng Bác sĩ
    'bacsi' => [
        'title' => $lang_module['bacsi_title'] ?? 'Quản lý Bác sĩ',
        'content' => ''
    ],
];

// Nếu op không có trong danh sách, chuyển về schedule
if (!isset($array_h[$op])) {
    $op = 'schedule';
}

// Gán tiêu đề trang
$page_title = $array_h[$op]['title'];

/**
 * Hàm gọi nội dung của các file PHP Admin (schedule.php, benhnhan.php, v.v.)
 * @param string $op Tên file chức năng (ví dụ: schedule)
 * @return string (Nội dung HTML đã được xử lý)
 */
function nv_admin_view_contents($op)
{
    global $module_name;
    
    ob_start(); // Bắt đầu bộ đệm đầu ra
    
    $filename = NV_ROOTDIR . '/modules/' . $module_name . '/admin/' . $op . '.php';

    if (file_exists($filename)) {
        require_once $filename;
    } else {
        // Trường hợp lỗi: file chức năng không tồn tại
        echo nv_admin_theme('<div class="alert alert-danger">Lỗi: File Admin không tồn tại: ' . $op . '.php</div>');
    }
    
    $contents = ob_get_clean();
    return $contents;
}

// Lấy nội dung chính
$contents = nv_admin_view_contents($op);

// Giao diện Admin chính thức
include NV_ROOTDIR . '/includes/header.php';
echo $contents; 
include NV_ROOTDIR . '/includes/footer.php';