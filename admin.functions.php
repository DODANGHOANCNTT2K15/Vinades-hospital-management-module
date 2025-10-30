<?php
if (!defined('NV_ADMIN') or !defined('NV_MAINFILE') or !defined('NV_IS_MODADMIN')) {
    die('Stop!!!');
}

// Đánh dấu rằng đây là khu vực quản trị module qlbenhvien
define('NV_IS_QLBENHVIEN_ADMIN', true);
