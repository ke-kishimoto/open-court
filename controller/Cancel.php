<?php
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

include('./header.php');

include('../head.php');
include('../header.php');
include('../cancelForm.php');
?>
