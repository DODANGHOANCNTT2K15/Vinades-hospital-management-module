<?php
if (!defined('NV_SYSTEM')) die('Stop!!!');

define('NV_IS_MOD_QLBENHVIEN', true);

/**
 * Kiểm tra xem bác sĩ có rảnh trong khung giờ đó không
 * @param int $bacsi_id
 * @param string $ngaykham (YYYY-mm-dd)
 * @param string $giokham (HH:MM:SS)
 * @return bool true nếu trống, false nếu đã có lịch
 */
function qlbenhvien_is_timeslot_free($bacsi_id, $ngaykham, $giokham)
{
    global $db, $db_config;

    // Tên bảng đúng với cơ sở dữ liệu của bạn
    $table = $db_config['prefix'] . "_benhvien_lichkham";

    // Kiểm tra trùng lịch cùng bác sĩ, cùng ngày, cùng giờ
    $sql = "SELECT COUNT(*) FROM " . $table . " 
            WHERE bacsi_id = :bacsi_id 
            AND ngaykham = :ngaykham 
            AND giokham = :giokham 
            AND trangthai IN ('pending','confirmed')";
    
    $sth = $db->prepare($sql);
    $sth->bindValue(':bacsi_id', (int)$bacsi_id, PDO::PARAM_INT);
    $sth->bindValue(':ngaykham', $ngaykham, PDO::PARAM_STR);
    $sth->bindValue(':giokham', $giokham, PDO::PARAM_STR);
    $sth->execute();

    return ($sth->fetchColumn() == 0);
}
