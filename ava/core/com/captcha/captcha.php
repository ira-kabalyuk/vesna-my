<?
session_start();
include($_SERVER['DOCUMENT_ROOT']."/#smart/#core/com/captcha/core_class.php");
$captcha = new Com_captcha_core(5);
$captcha -> display();
$_SESSION['captcha'] = $captcha -> getString();
?>