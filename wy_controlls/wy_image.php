<?php
include_once 'wy_common.php';
include_once 'wy_class_captcha.php';

$captcha = new Captcha();

$captcha->chars_number = 8;
$captcha->font_size = 14;
$captcha->tt_font = '../wy_files/css/fonts/verdana.ttf';

$captcha->show_image(132, 30);
?>