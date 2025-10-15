<?php

if (!defined('NV_IS_MOD_QLHS'))
    die('Stop!!!');

$contents = nv_qlhs_main();

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
