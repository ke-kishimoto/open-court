<?php
require_once('./header.php');

$btnClass = 'btn btn-primary';
$btnLiteral = 'ログイン';

if(isset($_SESSION['errMsg'])) {
    $errMsg = $_SESSION['errMsg'];
    unset($_SESSION['errMsg']);
} else {
    $errMsg = '';
}

$title = 'ログイン';
include('../view/common/head.php');
include('../view/common/header.php');
include('../view/signIn.php');

?>