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

include('../view/head.php');
include('../view/header.php');
include('../view/signIn.php');

?>