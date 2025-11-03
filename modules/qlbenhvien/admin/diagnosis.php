<?php
if (!defined('NV_IS_QLBENHVIEN_ADMIN'))
    die('Stop!!!');

global $db, $db_config, $nv_Request, $module_name, $module_file, $global_config;

$table = $db_config['prefix'] . "_ql_benhvien_chandoan";
$table_schedule = $db_config['prefix'] . "_ql_benhvien_lichkham";

$xtpl = new XTemplate(
    'diagnosis.tpl',
    NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file
);

if ($nv_Request->isset_request('save', 'post')) {
    $schedule_id = $nv_Request->get_int('schedule_id', 'post', 0);
    $chandoan = $nv_Request->get_title('chandoan', 'post', '');
    $donthuoc = $nv_Request->get_title('donthuoc', 'post', '');

    $errors = [];

    if (empty($schedule_id))
        $errors[] = '⚠️ Vui lòng chọn lịch khám.';
    if (empty($chandoan))
        $errors[] = '⚠️ Vui lòng nhập chẩn đoán.';

    if (empty($errors)) {
        // Lấy thông tin lịch khám để kiểm tra tồn tại
        $schedule = $db->query("SELECT * FROM $table_schedule WHERE id = " . $schedule_id)->fetch();
        if (!$schedule) {
            $errors[] = '❌ Lịch khám không tồn tại.';
        } else {
            // Chèn chẩn đoán
            $stmt = $db->prepare("
                INSERT INTO $table (schedule_id, chandoan, donthuoc, ngaytao)
                VALUES (:schedule_id, :chandoan, :donthuoc, :ngaytao)
            ");
            $ngaytao = date('Y-m-d H:i:s');
            $stmt->bindParam(':schedule_id', $schedule_id, PDO::PARAM_INT);
            $stmt->bindParam(':chandoan', $chandoan, PDO::PARAM_STR);
            $stmt->bindParam(':donthuoc', $donthuoc, PDO::PARAM_STR);
            $stmt->bindParam(':ngaytao', $ngaytao, PDO::PARAM_STR);
            $stmt->execute();

            // Cập nhật trạng thái lịch khám thành 'diagnosed'
            $update = $db->prepare("
                UPDATE $table_schedule
                SET trangthai = 'diagnosed'
                WHERE id = :schedule_id
            ");
            $update->bindParam(':schedule_id', $schedule_id, PDO::PARAM_INT);
            $update->execute();

            nv_redirect_location(NV_BASE_ADMINURL . 'index.php?nv=' . $module_name . '&op=diagnosis');
            exit;
        }
    }

    if (!empty($errors)) {
        $xtpl->assign('ERROR', implode('<br>', $errors));
        $xtpl->parse('main.error');
    }
}


// Lấy danh sách lịch khám (hiển thị tên bệnh nhân + bác sĩ + ngày)
$sql = "
    SELECT lk.id, bn.hoten AS patient_name, bs.hoten AS doctor_name, lk.ngaykham
    FROM $table_schedule AS lk
    JOIN " . $db_config['prefix'] . "_ql_benhvien_benhnhan AS bn ON lk.benhnhan_id = bn.id
    JOIN " . $db_config['prefix'] . "_ql_benhvien_bacsi AS bs ON lk.bacsi_id = bs.id
    ORDER BY lk.ngaykham DESC
";

$schedules = $db->query($sql)->fetchAll();

foreach ($schedules as $s) {
    $s['ngaykham'] = date('d/m/Y', strtotime($s['ngaykham']));
    $s['label'] = "{$s['patient_name']} - {$s['doctor_name']} ({$s['ngaykham']})";
    $xtpl->assign('SCHEDULE', $s);
    $xtpl->parse('main.schedule_option');
}

$xtpl->assign('ACTION', NV_BASE_ADMINURL . 'index.php?nv=' . $module_name . '&op=diagnosis');
$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
?>