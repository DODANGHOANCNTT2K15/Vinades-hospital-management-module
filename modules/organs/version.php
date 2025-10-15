<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES., JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES ., JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Dec 3, 2010  11:24:58 AM
 */

$module_version = [
    'name' => 'Organs',
    'modfuncs' => 'main, vieworg, person, viewsearch',
    'submenu' => '',
    'is_sysmod' => 0,
    'virtual' => 1,
    'version' => '4.5.03',
    'date' => 'Wednesday, April 26, 2022 15:00:00 GMT+07:00',
    'author' => 'VINADES.,JSC <contact@vinades.vn>',
    'note' => '',
    'uploads_dir' => [
        $module_name,
        $module_name . '/' . date('Y_m')
    ]
];
