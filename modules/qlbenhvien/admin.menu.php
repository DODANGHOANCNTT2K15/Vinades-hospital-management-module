<?php
if (!defined('NV_ADMIN')) die('Stop!!!');

$submenu['schedule'] = 'Lịch khám bệnh';
$submenu['doctor'] = 'Đội ngũ y bác sĩ';
$submenu['specialties'] = 'Chuyên khoa';

$allow_func = ['main', 'schedule', 'schedule_add', 'schedule_edit', 'doctor', 'doctor_add', 'doctor_edit', 'specialties', 'specialties_add', 'specialties_edit'];
