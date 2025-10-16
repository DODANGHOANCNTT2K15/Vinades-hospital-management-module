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
