<?php
if (!defined('NV_ADMIN')) die('Stop!!!');

$submenu['schedule'] = 'Lịch khám bệnh';
$submenu['doctor'] = 'Đội ngũ y bác sĩ';
$submenu['specialties'] = 'Chuyên khoa';
$submenu['patient'] = 'Bệnh nhân';
$submenu['diagnosis'] = 'Chẩn đoán & đơn thuốc';
$submenu['diagnosis_list'] = 'Danh sách chẩn đoán & đơn thuốc';


$allow_func = ['main', 'schedule', 'schedule_add', 'schedule_edit', 'doctor', 'doctor_add', 'doctor_edit', 'specialties', 'specialties_add', 'specialties_edit', 'patient', 'patient_add', 'patient_edit', 'patient_detail', 'diagnosis', 'diagnosis_add', 'diagnosis_edit', 'diagnosis_list'];
