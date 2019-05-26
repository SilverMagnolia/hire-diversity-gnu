<?php
// https 접속이 아닐시 강제로 리다이렉트

if(!isset($_SERVER["HTTPS"])) {
	header('Location: https://www.hirediversity.kr');
}

if (!defined('_GNUBOARD_')) exit; // Unable to access direct pages
?>
