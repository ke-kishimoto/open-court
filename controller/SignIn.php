<?php

$btnClass = 'btn btn-primary';
$btnLiteral = 'ログイン';

if(isset($_SESSION['errMsg'])) {
    $errMsg = $_SESSION['errMsg'];
    unset($_SESSION['errMsg']);
} else {
    $errMsg = '';
}

$title = 'ログイン';

include('./header.php');

include('../head.php');
include('../header.php');
include('../signIn.php');

?>