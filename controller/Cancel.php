<?php
require_once('./header.php');

if(isset($_SESSION['user'])) {
    $email = $_SESSION['user']['email'];
    $mode = 'login';
} else {
    $email = '';
    $mode = 'guest';
}

if(isset($_SESSION['errMsg'])) {
    $errMsg = $_SESSION['errMsg'];
    unset($_SESSION['errMsg']);
} else {
    $errMsg = '';
}

$title = 'キャンセル';
include('../view/common/head.php');
include('../view/common/header.php');
include('../view/cancelForm.php');
?>
